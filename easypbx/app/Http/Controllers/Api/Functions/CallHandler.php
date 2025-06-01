<?php

namespace App\Http\Controllers\Api\Functions;
use App\Http\Controllers\Api\FunctionCall;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Api\VoiceResponse;
use App\Http\Controllers\Api\ActionParser;
use App\Models\Extension;
use App\Models\Organization;
use App\Models\Call;
use App\Models\CallRecord;
use App\Models\OutboundRoute;
use App\Models\SipUser;
use App\Enums\CallStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Exception;


class CallHandler{

      
    
    public static function createOutbound($request){
            
        $channels = FunctionCall::getOutboundChannels($request->input( 'organization_id' ),$request->input( 'to' ));
        foreach($channels as $id=>$dest){
             $data = $request->input();
             $data['to'] = $dest;
             $data['channel_id'] = $id;
             $request = Request::create('/', 'POST',$data);
             $call  = self::create($request);
             if(isset($call['error']) || $call['status-code'] >= CallStatusEnum::Disconnected->value)
               continue;
             else
                return $call; 
        }

        return false;

    }

    public static function create($request){

        $call            = null;
        $err_code        = 0;
        $to              = $request->input( 'to' );
        $destination     = $to;
        $domain          = $request->input( 'domain' );
        $organization_id = $request->input( 'organization_id' );
        $from            = $request->input( 'from' );
        //$from_name       = $request->input( 'fromName' );
        $channel_id      = (int) $request->input( 'channel_id' );
        $status_callback = $request->input( 'statusCallback' );
        $timeout = (int) $request->input( 'timeout' );
       
        if(empty($from)) $from = 'easypbx';
        //if(empty($from_name)) $from_name =  $from;
        $xmlelem = null;
        if (  ! empty( $request->input( 'response' ) ) ) {
            $xmlelem = VoiceResponse::getElementFromXml( "", $request->input( 'response' ),['event_from'=>$from,'event_to'=>$to]  );
        } elseif (  ! empty( $request->input( 'responseXml' ) ) ) {
            $xmlelem = VoiceResponse::getElementFromXml( $request->input( 'responseXml' ) );
        }

    /*     $err_code = -5;
        $call_count = Call::where('status','<',CallStatusEnum::Disconnected->value)->count();
        if($call_count >= config('licence.call_limit',2))     goto out;
         */
       

        

        $err_code = 1;

        if (  ! $xmlelem ) {
            goto out;
        }

        $organization = null;

        if (  ! empty( $organization_id ) ) {
            $organization = Organization::find( $organization_id );
        } elseif (  ! empty( $domain ) ) {
            $organization = Organization::where( "domain", $domain )->first();
        }

        $err_code = 2;

        if (  ! $organization ) {
            goto out;
        }


        
        $err_code = -4;
        $call_count = Call::where('organization_id', $organization->id)->where('status','<',CallStatusEnum::Disconnected->value)->count();
        // info('total active calls ' . $call_count);
        if($organization->call_limit != 0 && $call_count > $organization->call_limit) goto out;

        if (isset($organization->expire_date) && Carbon::parse($organization->expire_date)->isPast()) goto out;



        $err_code = 3;
        if ( empty( $channel_id ) ) {
           
            $extension = Extension::where( "code", $to )->where( "organization_id", $organization->id )->where( "extension_type", 1 )->first();
            if ( $extension ) {              
                $channel_id  = $extension->destination_id;
            } else {
                $call = self::createOutbound($request);
                if($call == false) goto out;
                else return $call;
                
               /*  $oroutes = OutboundRoute::where( "organization_id", $organization->id )->where( "is_active", true )->get();

                foreach ( $oroutes as $oroute ) {
                    if ( FunctionCall::matchPattern( $oroute->pattern, $to ) ) {
                        
                        $channel_id = $oroute->trunk->sip_user_id;
                        break;                 
                    }

                }  */


            }

        }

        

        if ( empty( $channel_id ) ) {
            goto out;
        }

        $sip_user    = SipUser::find($channel_id );
        if(!$sip_user || $sip_user->status != 1 || $sip_user->organization_id != $organization->id) goto out;

        $err_code = -3;
        $calls = Call::where("sip_user_id",$channel_id)->where('status','<',CallStatusEnum::Disconnected->value)->get();
        if($sip_user->call_limit > 0 && $calls->count() >= $sip_user->call_limit) goto out;

        //$call_data = ['organization_id' => $organization->id, 'destination' => $to, 'caller_id' => $from, 'sip_user_id' => $channel_id, 'status' => 0, 'connect_time' => now(), 'duration' => 0, 'uas' => false];
        $call_data = ['organization_id' => $organization->id, 'destination' => $to, 'caller_id' => $from, 'sip_user_id' => $channel_id, 'status' => 0, 'connect_time' => now(), 'duration' => 0, 'uas' => false];
        
        $call      = Call::create( $call_data );
        $err_code  = 1;
        $actions   = ActionParser::parse( $call->organization_id, $xmlelem );

        if ( sizeof( $actions ) == 0 ) {
            goto out;
        }
        $call_user = ($sip_user->peer && $sip_user->overwrite_cid == false)?$sip_user->username:$from;
       // $call_user = $sip_user->username;
        $post     = ['call_id' => $call->id, 'to' =>strval($destination), 'channel_id' => $channel_id, 'from' => 'sip:' . $call_user . '@' . $organization->domain, 'actions' => $actions];
        if(!empty($timeout) && $timeout > 0)  $post['timeout'] = $timeout;
        $err_code = 4;
        try {
            $response = Http::post( "http://" . config( 'settings.switch.http_listen' ) . config( 'easypbx.call_create_path' ), $post );
        } catch ( Exception $e ) {

            goto out;
        }

        if ( $response->failed() ) {goto out;}

        $status = $response->json( 'status' );
        //info('status callbakc here' . $status);
        if ( $status === 0 ) {


            if (  ! empty( $status_callback ) ) {
                
                $method = strtoupper( $request->input( 'statusCallbackMethod' ) );
                if ( empty( $method ) || ! in_array( $method, ['GET', 'POST'] ) ) {
                    $method = 'POST';
                }

                Cache::put( "CallStatusCallback:" . $call->id, ['url' => $status_callback, 'method' => $method], 36000 );
            }

        } else {
            //$err_code = $status;
            $call->update( ["disconnect_time" => now(), 'status' => CallStatusEnum::Failed] );

        }

        return self::prepare_call_json( $call );

        out:

        if ( $call ) {
            $call->update( ["disconnect_time" => now(), 'status' => CallStatusEnum::Failed] );
        }

        $err =  ['error' => true, 'error_code' => $err_code, 'error_message' => self::call_error_code_to_msg( $err_code )] ;
        //info($err);
        return $err;
    
            
    }

    public static function modify($call_id,$request){
        $err_code = 2;
        //Log::debug("call modify request " . $call_id);

        if ( empty( $call_id ) ) {
            goto out;
        }

        $call = Call::find( $call_id );

        if (  ! $call ) {
            goto out;
        }

        if ( $call->status->value >= 3 ) {
            goto out;
        }
        $xmlelem = null;
        if (  ! empty( $request->input( 'response' ) ) ) {
            $xmlelem = VoiceResponse::getElementFromXml( "", $request->input( 'response' ),['event_from'=>$call->caller_id,'event_to'=>$call->destination] );
        } elseif (  ! empty( $request->input( 'responseXml' ) ) ) {
            $xmlelem = VoiceResponse::getElementFromXml( $request->input( 'responseXml' ) );
        }

        $err_code = 1;

        if (  ! $xmlelem ) {
            goto out;
        }

        $actions = ActionParser::parse( $call->organization_id, $xmlelem );
        //info($actions);
        if ( sizeof( $actions ) == 0 ) {
            goto out;
        }

        $post     = ['call_id' => $call->id, 'actions' => $actions];
        $err_code = 4;
        try {
            $response = Http::post( "http://" . config( 'settings.switch.http_listen' ) . config( 'easypbx.call_modify_path' ), $post );
        } catch ( Exception $e ) {
            //Log::debug("eror exception is " . $e->getMessage());
            goto out;
        }
        //Log::debug($response->status() . " s " . $response->serverError() . " c ". $response->clientError() . " " . $response->body() );


        if ( $response->failed() ) {
            goto out;
        }

        $status = $response->json( 'status' );

        if ( $status === 0 ) {
            return self::prepare_call_json( $call );
        }

        out:
        return ['error' => true, 'error_code' => $err_code];
    }

    public static function prepare_call_json( $call ,$record = false) {
        $ret = ['call_id' => (string) $call->id, 'from' => $call->caller_id, 'to' => $call->destination, 'create_time' => $call->connect_time->format( "Y-m-d H:i:s" )];
        $ret['end_time']      = ( $call->disconnect_time ) ? $call->disconnect_time->format( "Y-m-d H:i:s" ) : "";
        $ret['duration']      = $call->duration;
        $ret['establihed_at'] = ( $call->establish_time ) ? $call->establish_time->format( "Y-m-d H:i:s" ) : $call->establish_time;
        $ret['status']        = $call->status->getText();
        $ret['status-code']   = $call->status->value;
        
        if($record){
            $records = CallRecord::where("call_id",$call->id)->get();
            
            if($records->count() > 0){
                $recordsa = [];
                $ret['records_count'] = $records->count();
                foreach($records as $record){
                    $recordsa[] =  url('/storage')  . "/" . $record->record_path;
                }
                $ret['records'] =  $recordsa;

            }
        }

        return $ret;
    }

    /*

error_codes
1 = Bad xml request
2 = invalid request
3 = No route found
4 = switch internal error
-1 = channel not found
-2 = formating error
-3 = call limit exceeded
-4 = server call limit exceeded
-5 = organization call limit exceeded
others = other internal errors in switch

 */
    
    public static function call_error_code_to_msg( $err_code ) {

        switch ( $err_code ) {
        case 1:
            $err_msg = "Bad xml request";
            break;
        case 2:
            $err_msg = "invalid request";
            break;
        case 3:
            $err_msg = "No route found";
            break;
        case 4:
            $err_msg = "Switch request failed";
            break;
        case -1:
            $err_msg = "channel not found";
            break;
        case -2:
            $err_msg = "switch formating error";
            break;
        case -3:
            $err_msg = "Call limit exceeded";
            break;        
        case -4:
            $err_msg = "server call limit exceeded";
            break;        
        case -5:
            $err_msg = "organization call limit exceeded";
            break;        
            
        default:
            $err_msg = "switch internal error";
            break;
        }

        return $err_msg;

    }

    
}