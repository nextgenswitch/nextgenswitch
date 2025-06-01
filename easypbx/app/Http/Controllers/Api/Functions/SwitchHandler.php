<?php

namespace App\Http\Controllers\Api\Functions;
use App\Http\Controllers\Api\FunctionCall;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Api\VoiceResponse;
use App\Http\Controllers\Api\ActionParser;
use App\Models\Organization;
use App\Models\Call;
use App\Models\CallRecord;
use App\Models\Trunk;
use App\Models\Func;
use App\Models\Setting;
use App\Models\SipUser;
use App\Models\SipChannel;
use App\Models\User;
use App\Enums\CallStatusEnum;
use App\Notifications\PushNotification;
use Illuminate\Support\Facades\Redis;
use App\Tts\Tts;
use Exception;


class SwitchHandler{
    public static function sip_user_validate($data){

        $organization = Organization::where( "domain", $data['domain'] )->first();

        if (  ! $organization ) {
            $organization = Organization::where( "is_default", 1 )->first();
        }

        if ( $organization ) {
            $sip_user = SipUser::where( "username", $data['user'] )->where('status',1)->where('peer',0)->where( "organization_id", $organization->id )->first();

            if ( $sip_user ) {
                if($sip_user->allow_ip){
                    $allowIpArr = explode(',', $sip_user->allow_ip);

                    if( in_array($data['srcip'], $allowIpArr) == false){
                        return ['error'=>true];
                    }
                }

                return ['id' => $sip_user->id, 'password' => $sip_user->password, 'host' => $organization->domain, 'md5_hash' => base64_encode( md5( $data['user'] . ":" . $data['realm'] . ":" . $sip_user->password, true ) )];
            }

        }
        
        
        return ['error'=>true];
    }

    
    public static function is_ip_blocked($data){
        //Log::info( $data);
        $ip = $data['srcip'];
        
        $milliseconds = intval(microtime(true) * 1000);
        
        $count = Redis::eval(
            "redis.call('set','ipblock:$ip:$milliseconds',1) redis.call('expire','ipblock:$ip:$milliseconds',60)  return #redis.call('keys', 'ipblock:$ip:*')",
            0
        );
        $blocked = false;
        if($count > 15){
            $blocked = true;
        }
        return ["blocked"=>$blocked,'count'=>$count,'expire'=>600];

    }

    public static function sip_user_outbound($data) {
        $trunks = Trunk::all();

        foreach ( $trunks as $trunk ) {
            FunctionCall::reg_channel( $trunk->sip_user_id );
        }

        return ['error' => false];
    }

    public static function sip_user_sms($data){
        //info("new sms request came");
        //info($data);
        $sip_channel = SipUser::find( $data['channel_id'] );
        if(!$sip_channel) ['success' => false];
        $data['organization_id'] =$sip_channel->organization_id; 
        FunctionCall::send_sms($data);
        return ['success' => true];
    }

    public static function sip_user_channel_notify($data) {
        SipChannel::where( "sip_user_id", $data['channel_id'] )->where( "location", $data['location'] )->delete();
        $sip_user = SipUser::find( $data['channel_id'] );
        if ( $sip_user && $data['expire'] > 0 ) {
            SipChannel::create(
                ['sip_user_id'    => $data['channel_id'],
                    'organization_id' => $sip_user->organization_id,
                    'location'        => $data['location'],
                    'expire'          => $data['expire'],
                    'ua'              => isset( $data['ua'] ) ? $data['ua'] : "Unknown",
                ] );
        }

        

        return ['error' => false];
    }

    public static function call_record_update($data) {
        $call = Call::find( $data['call_id'] );

        if (  ! $call ) {
            goto out;
        }

        CallRecord::create( ['call_id' => $data['call_id'], 'dial_call_id' => $data['bridge_call_id'], 'organization_id' => $call->organization_id, 'record_path' => $data['record_file']] );
        out:
        return ['error' => true];
    }

    public static function call_incoming($data) {
        $sip_channel = SipUser::find( $data['channel_id'] );
        if(!$sip_channel) return;
        $actions     = ActionParser::parse( $sip_channel->organization_id, FunctionCall::processDestination( $data['to'], $sip_channel, $data['from'] ) );
        $call_data   = ['organization_id' => $sip_channel->organization_id, 'destination' => $data['to'], 'channel' => $data['channel'], 'caller_id' => $data['from'], 'sip_user_id' => $data['channel_id'], 'status' => 0, 'connect_time' => now(), 'duration' => 0, 'uas' => true];
        $call     = Call::create( $call_data );
        

        if ( sizeof( $actions ) > 0 ) {
            $code = 180;
        } else {
            $code = 404;
            //$call->status = CallStatusEnum::Failed;
            //$call->update();
            
        }
        //info("On incoming call " . $call->id);
        //info($actions);

        return ['call_id' => $call->id, 'code' => $code, 'reason' => FunctionCall::sip_code_to_msg( $code ), 'actions' => $actions];

    }

    public static function call_dial($data) {
        $call = Call::find( $data['call_id'] );

        if (  ! $call ) {
            goto out;
        }

        if ( isset( $data['enqueue'] ) ) {
            $method = ( isset( $data['enqueue']['method'] ) ) ? $data['enqueue']['method'] : "POST";
            $post   = ['name' => $data['to'], 'call_id' => $call->id];

            if ( strtoupper( $method ) == 'POST' ) {
                $response = Http::asForm()->post( $data['enqueue']['url'], $post );
            } else {
                $response = Http::get( $data['enqueue']['url'], $post );
            }

            if ( $response->failed() ) {
                goto out;
            }

            //$this->dial_queue($data["to"]);
            $xmlelem = VoiceResponse::getElementFromXml( $response->body() );
            $actions = ActionParser::parse( $call->organization_id, $xmlelem );

            return ['call_id' => $call->id, 'actions' => $actions];

        } elseif ( isset( $data['queue'] ) ) {
            $actions = [];
            $method  = ( isset( $data['queue']['method'] ) ) ? $data['queue']['method'] : "POST";
            $post    = ['name' => $data['to'], 'call_id' => $call->id];

            if ( strtoupper( $method ) == 'POST' ) {
                $response = Http::asForm()->post( $data['queue']['url'], $post );
            } else {
                $response = Http::get( $data['queue']['url'], $post );
            }

            if ( $response->failed() ) {
                goto out;
            }

            $xmlelem = VoiceResponse::getElementFromXml( $response->body() );
            $actions = ActionParser::parse( $call->organization_id, $xmlelem );

            return ['call_id' => $call->id, 'actions' => $actions];
        }

        $response = new VoiceResponse();
        $response->bridge( $data['call_id'] );
        
        $dial = ['to' => $data['to'], "organization_id" => $call->organization->id, 'from' => $data['from'], 'responseXml' => $response->xml()];
        if(isset($data['channel_id'])) $dial['channel_id'] = $data['channel_id'];
        if(isset($data['statusCallback']))  $dial['statusCallback'] = $data['statusCallback'];
        if(isset($data['callerId']))  $dial['from'] = $data['callerId'];
        //info($dial);
        $call_data = FunctionCall::send_call($dial);
        info('dial result');
        info($call_data);

        if ( isset( $call_data['call_id'] ) && $call_data['status-code'] < CallStatusEnum::Disconnected->value ) {
            $call = Call::find( $call_data['call_id'] );
            $call->update( ['parent_call_id' => $data['call_id']] );
            return $call_data;
        }

       
        out:
        return ['error' => true];
    }

    public static function call_transfer($data) {
        //Log::debug("call transfer request");
        //Log::debug($data);
        $call = Call::find( $data['call_id'] );

        if (  ! $call ) {
            goto out;
        }

        $response = FunctionCall::getOutboundRoutes( $data['to'], $call->organization_id );
       // Log::debug("modifying call with response");
        //Log::debug($response->xml());
        $response = FunctionCall::modify_call( $data['call_id'], [ 'responseXml' => $response->xml()]);
        return $response;
     
        out:
        //Log::debug("call transfer failed");
        return ['error' => true];
    }

    public static function call_update($data) {

        if (  ! isset( $data['call_id'] ) ) {
            goto out;
        }

        $call = Call::find( $data['call_id'] );

        if (  ! $call ) {
            goto out;
        }

        if ( $data['connect_time'] > 0 ) {
            $call->connect_time = date( "Y-m-d H:i:s", $data['connect_time'] );
        }

        if ( $data['ringing_time'] > 0 ) {
            $call->ringing_time = date( "Y-m-d H:i:s", $data['ringing_time'] );
        }

        if ( $data['establish_time'] > 0 ) {
            $call->establish_time = date( "Y-m-d H:i:s", $data['establish_time'] );
        }

        if ( $data['disconnect_time'] > 0 ) {
            $call->disconnect_time = date( "Y-m-d H:i:s", $data['disconnect_time'] );
        }

        $call->status  = $data['status'];
        $call->channel = $data['channel'];

        if ( $call->status == CallStatusEnum::Disconnected ) {

            if ( $data['establish_time'] == 0 ) {

                if ( $call->uas ) {
                    if($data['disconnect_code'] == 104) 
                        $call->status = CallStatusEnum::Cancelled;
                    else
                        $call->status = CallStatusEnum::Failed;
                } else {

                    if ( $data['disconnect_code'] >= 400 ) {
                        $call->status = CallStatusEnum::Busy;
                    } else {
                        $call->status = CallStatusEnum::NoAnswer;
                    }

                }

            }

        }

        if ( $data['establish_time'] > 0 && $data['disconnect_time'] > 0 ) {
            $call->duration = $data['disconnect_time'] - $data['establish_time'];
        }

        $call->save();

        $statusCallback = Cache::get("CallStatusCallback:" .$call->id,[]);
        if(isset($statusCallback['method']) && isset($statusCallback['url'])){
          $post = CallHandler::prepare_call_json($call);
          if($statusCallback['method'] == 'POST') Http::post($statusCallback['url'],$post);
          else Http::get($statusCallback['url'],$post);
          if($call->status->value >= CallStatusEnum::Disconnected->value) Cache::forget("CallStatusCallback:" .$call->id);
        }

        

        return ['error' => false];
        out:
        return ['error' => true];
    }

    public static function speech_to_text($data) {
        $call = Call::find($data['call_id']);
       /*  $org_id = $call->organization_id;
        $tts_profile = TtsProfile::where('type',1)->where(function ($query) use ($org_id) {
            $query->where("organization_id", $org_id)
                  ->orWhere("organization_id", 0);
        })->orderBy( "is_default", 'DESC' )->first();

 */
        $text = Tts::speechToText( storage_path( 'app/public/' . $data['voice'] ) ,$call->organization_id);
        Log::info( "Speech to text result" );
        Log::info( $text );
        out:

        if ( $text ) {
            return $text;
        } else {
            return ['error' => true];
        }

    }

    
    public static function sms($data){
        return FunctionCall::send_sms($data);
    }

    public static function call_url_request($data) {

        if (  ! isset( $data['call_id'] ) ) {
            goto out;
        }

        $call = Call::find( $data['call_id'] );

        if (  ! $call ) {
            goto out;
        }

        $post = $data['body'];

        $url    = $data['url'];
        //info("url on action request " . $url);
        $method = isset( $data['method'] ) ? $data['method'] : "POST";


        $post = array_merge($post,['event_from'=>$call->caller_id,'event_to'=>$call->destination,'event_call_id'=>$call->id]);
        //info("Final post");
        //info($post);
         
        if ( strtoupper( $method ) == 'POST' ) {
            $response = Http::asForm()->post( $url, $post );
        } else {
            $response = Http::get( $url, $post );
        }

        if ( $response->failed() ) {
            goto out;
        }

        $xmlelem = VoiceResponse::getElementFromXml( $response->body(), $url );

        if ( $xmlelem === false ) {
            goto out;
        }

        if (  ! isset( $xmlelem['url'] ) ) {
            $xmlelem->addAttribute( "url", $url );
        }

        $actions = ActionParser::parse( $call->organization_id, $xmlelem );



        return ['call_id' => $call->id, 'actions' => $actions];
        out:
        return ['error' => true];
    }
    public static function worker_run($data) {
       // info("on worker run");
        //info($data);
        $url    = $data['url'];
        $timeout = intval(isset($data['timeout'])?$data['timeout']:60);
       // info('timeout val ' . $timeout);
        $method = isset( $data['method'] ) ? $data['method'] : "POST";
        if ( strtoupper( $method ) == 'POST' ) {
            $response = Http::timeout($timeout)->asForm()->post( $url,  isset($data['data'] )?$data['data']:[] );
        } else {
            $response = Http::timeout($timeout)->get( $url,isset($data['data'] )?$data['data']:[]);
        }
        if ( $response->failed() )
            return ['error'=>true];

        return json_decode($response->body(),true);
    }

    public static function config($data) {
        Func::truncate();
        //dd(config('easypbx.core_functions'));
        Func::insert(config('easypbx.core_functions'));
        
        $config = Setting::getSettings( 'switch' );
        SipChannel::truncate();
       // Queue::where( "status", "<", QueueStatusEnum::Bridged->value )->update( ['status' => QueueStatusEnum::Hangup] );
        Call::where( "status", "<", CallStatusEnum::Disconnected->value )->update( ['status' => CallStatusEnum::Failed] );
        $config['license'] = config('licence');
        setPermissionsTeamId(1);
        $user = User::role('Super Admin')->first();
        if($user)
        $user->notify(new PushNotification([
            'type' => 0,
            'code'=>11,
            'msg' => __('Switch has been started')
        ]));

        return $config;
    }



}