<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Tts\Tts;
use Carbon\Carbon;
use App\Models\Call;
use App\Models\CampaignCall;
use Illuminate\Http\Request;
use App\Enums\CallStatusEnum;
use App\Enums\QueueStatusEnum;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Api\ActionParser;
use App\Http\Controllers\Api\FunctionCall;
use App\Http\Controllers\Api\Functions\CallHandler;
use App\Http\Controllers\Api\Functions\QueueWorker;
use App\Http\Controllers\Api\Functions\SwitchHandler;
use App\Http\Controllers\Api\Functions\BroadcastWorker;
use App\Http\Controllers\Api\Functions\CallParkingWorker;
use App\Http\Controllers\Api\VoiceResponse;



/* organization specific

default language
sms gateway url
record format
default tts
default stt */

class SwitchController extends Controller {

    public function licence(){
        if(config()->has('licence')) return config('licence');
        return [];
    }

    public function calls(){
        $response = Http::get( "http://" . config( 'settings.switch.http_listen' ) . "/call/list")->json();
        foreach($response as $call){            
            $cdr = Http::get( "http://" . config( 'settings.switch.http_listen' ) . "/call/get",['call_id'=>$call['call_id']])->json();
            //info($cdr);
            //return $cdr;
        }
        return $response;
    }

    public function index( Request $request ) {
        if ( $request->isMethod( 'post' ) ) {
        return ['retry'=>10];
        }

        //FunctionCall::send_to_websocket('675765',['hello'=>'test']);

       Tts::speechToText("/usr/share/nginx/html/laravel/easypbx/storage/app/public/records/3726ee40-90f0-4b53-b5ca-77bf74f44e8e/gather/1075519993.wav",1);
        

       /*  $value = Redis::script('LOAD',<<<'LUA'
        return #redis.pcall('keys', ARGV[1])
        LUA);
        $data = FunctionCall::create_worker(['url'=>'http://127.0.0.1:81/api','name'=>'testworker','delay'=>1]);
        dd($data);

        $this->switch_set_settings(['sip_log_delay'=>60,'sip_log_ip'=>'103.86.196.156:0']);
        dd($value); */
        /* $val = Redis::set('ipblock:1.1.1.2:34232','1dd');
        dump($val); 
        //Redis::expire('ipblock:1.1.1.1:34232',60);
        $val = Redis::get('ipblock:1.1.1.2:34232');
        dump($val);*/
        
        //dd($val);
       
    }

    public function set_sip_log(Request $request){
        $data = ['sip_log_delay'=>$request->query('delay',60)];
        if($request->query('ip') != '') $data['sip_log_ip'] = $request->query('ip');
        $this->switch_set_settings($data);
        return [$data];
    }

    public function switch_set_settings($post){
        try {
            $response = Http::post( "http://" . config( 'settings.switch.http_listen' ) . config( 'enums.set_settings' ), $post );
            if ( $response->failed() ) {

            }

           return  $response->json();
        } catch ( Exception $e ) {

            
        }
    }

  

    public function textToSpeech() {
        $data = request()->input();
        $file = Tts::synthesize( $data['text'], isset( $options['lang'] ) ? $options['lang'] : null );

        return $file;
    }




 

   

 


    public function api_call_list(Request $request, $perpage = 10, $page = 1) {
        
        $perPage = $request->get( 'per_page' ) ?: 50;
        $fromDate = $request->get('from_date') ?: '';
        $toDate = $request->get('to_date') ?: '';

        $calls = Call::where('organization_id', $request->organization_id);    

        if(!empty($fromDate)){
            $calls = $calls->where('created_at', '>=', Carbon::parse($fromDate)->format('Y-m-d H:i:s'));
        }

        if(!empty($toDate)){
            $calls = $calls->where('created_at', '<=', Carbon::parse($toDate)->format('Y-m-d H:i:s'));
        }

        $calls = $calls->latest()->paginate($perPage);
        $items = [];

        foreach($calls->items() as $item){
            $items[] =CallHandler::prepare_call_json($item);
        }

        $response = [
            'perPage'=>$calls->perPage(),
            'page' => $calls->currentPage(),
            'count'=>$calls->count(),
            'total'=>$calls->total(),
            'data'=>$items
        ];

        return $response;
    }

    public function api_call_get( $call_id ) {
        $err_code = 2;

        if ( empty( $call_id ) ) {
            goto out;
        }

        $call = Call::find( $call_id );

        if (  ! $call ) {
            goto out;
        }

        return CallHandler::prepare_call_json( $call,true );
        $err_msg = CallHandler::call_error_code_to_msg( $err_code );
        out:
        return response( [
            'errors' => [[
                'message' => $err_msg,
                "code"    => $err_code,
            ]],
        ], 404 );
    }

    public function api_call_modify( $call_id, Request $request ) {
        //dd($request->all());
        $response = CallHandler::modify( $call_id, $request );

        if ( isset( $response['error'] ) ) {
            $err_msg = $this->call_error_code_to_msg( $response['error_code'] );

            return response( [
                'errors' => [[
                    'message' => $err_msg,
                    "code"    => $response['error_code'],
                ]],
            ], 404 );

        } else {
            return $response;
        }

    }

    public function api_call_create( Request $request ) {

        $response = CallHandler::create( $request );

        if ( isset( $response['error'] ) ) {
            $err_msg = CallHandler::call_error_code_to_msg( $response['error_code'] );

            return response( [
                'errors' => [[
                    'message' => $err_msg,
                    "code"    => $response['error_code'],
                ]],
            ], 400 );

        } else {
            return $response;
        }

    }
    
    public function func_call( $func_id, $dest_id ) {
        $bresponse = new VoiceResponse();
       // info("on funcion call");
        //info( request()->all());
        $response = FunctionCall::execute( $func_id, $dest_id, $bresponse, request()->all() );

        return ($response)?$response->asXML():$bresponse->asXML();
    }


    public function getConfig() {
        return SwitchHandler::config([]);
    }

    public function call_record_update() {
        $data = request()->json()->all();
        SwitchHandler::call_record_update($data);
    }

    public function call_in() {
        $data        = request()->json()->all();
        return SwitchHandler::call_incoming($data);
    }
    
    public function call_update() {
        $data = request()->json()->all();
        return SwitchHandler::call_update($data);
    }

    public function call_transfer() {
        $data = request()->json()->all();
        return SwitchHandler::call_transfer($data);
    }

    public function dial() {
        $data = request()->json()->all();
        return SwitchHandler::call_dial($data); 
    }

    public function url_request() {
        $data = request()->json()->all();
        return SwitchHandler::call_url_request($data);
    }

    public function sip_user_outbound() {
       return SwitchHandler::sip_user_outbound([]);
    }
    public function sip_user_sms() {
       // info(request()->all());
        $data  = request()->json()->all();
        return SwitchHandler::sip_user_sms($data);
    }

    public function sip_user_validate() {
        $data  = request()->json()->all();
        return SwitchHandler::sip_user_validate($data);

    }

    public function sip_channel_update() {
        $data = request()->json()->all();
        return SwitchHandler::sip_user_channel_notify($data);
    }

    
    public function isIpBlocked(){
        $data = request()->json()->all();
        return SwitchHandler::is_ip_blocked($data);
    }

    public function speechToText() {
        $data = request()->json()->all();
        return SwitchHandler::speech_to_text($data);

    }

    public function sendSms(){
        $data = request()->json()->all();
        return FunctionCall::send_sms($data);
    }

    public function worker_run(){
        $data = request()->json()->all();
        return SwitchHandler::worker_run($data);
    }

    public function queue_worker($id,$func_id){
        $queue_worker = new QueueWorker($id,$func_id);
        $queue_worker->handle();
        Log::debug("Queue worker executing");
        return [];
    }

    public function internal_status_callback(Request $request){
        //$url = 
    }

    public function call_parking_worker($id){
        $parkingWorker = new CallParkingWorker($id);
        $parkingWorker->timeout();
        Log::debug("CallPark worker executing");
        return [];
    }

    public function update_broadcast_history(Request $request, $campaign_id){
        
        //$campaign = Campaign::find($campaign_id);          
        $data = $request->all();
        // Log::info("---------- Update Campaing call  ---------");
		// Log::info($data);
        CampaignCall::where('campaign_id',$campaign_id)->where( 'call_id', $data['call_id'] )->update(['duration'=>$data['duration'],'status'=>$data['status-code']] );

        $wsRes = [
            'date' => date('d-m-Y H:i:s'),
            'contact' => $data['to'],
            'status' => ''
        ];      

        if(isset($data['error']) && $data['error'] ){
            $wsRes['status'] = $data['error_message'];
        }

        if( isset($data['call_id']) ){
            $wsRes['status'] = $data['status'];
        }   
        
        FunctionCall::send_to_websocket('campaign_' . $campaign_id, $wsRes);

        

    }


    public function broadcast_worker(Request $request, $campaign_id){
        $broadcastWorker = new BroadcastWorker($campaign_id);
        $broadcastWorker->process();
    }

}
