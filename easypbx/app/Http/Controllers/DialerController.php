<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SipUser;
use App\Models\Call;
use App\Models\CallHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\FunctionCall;
use App\Enums\CallStatusEnum;
use App\Http\Controllers\Api\VoiceResponse;
use App\Http\Controllers\Api\Functions\CallHandler;

class DialerController extends Controller {
    public function login( Request $request ) {

        if ( $request->ajax() ) {

            $rules = [
                'username' => ['required', 'string'],
                'password' => ['required', 'string'],
            ];

            $validator = Validator::make( $request->all(), $rules );

            if ( $validator->fails() ) {
                $validationErrors = $validator->errors()->toArray();

                return response()->json( ['status' => 'error', 'errors' => $this->getErrors( $validationErrors )] );
            }

            $data = $validator->validated();

            $sip = SipUser::where('username', $data['username'])->where('password', $data['password'])
            ->where('organization_id',auth()->user()->organization_id)->where('peer',0)
            ->where('status',1)->whereRaw('NOT(user_type <=> 2)');
            $error = 'User or password incorrent';
            if($sip->exists()){
                $sipuser = $sip->first();
                
                //$client_id = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10/strlen($x)) )),1,10);
                $call = FunctionCall::send_call(['to'=>$sipuser->username,'channel_id'=>$sipuser->id,'from'=>'easypbx','response'=>route('webdialer.response'),'statusCallback'=>route('webdialer.responseCallback','webdialer')]);
                // return $call;
                // dd($call);

                if(isset($call['error']) && $call['error'] == true){
                    $error = $call['error_message'];
                }

                else if(isset($call['status-code']) && $call['status-code'] < CallStatusEnum::Disconnected->value){
                    $request->session()->put('dialer.login.' . auth()->user()->organization_id,$sipuser->username);
                    $request->session()->put('dialer.call_id.' . auth()->user()->organization_id,$call['call_id']);
                    
                    return response()->json(['status' => 'success','call_id'=>$call['call_id']]);
                }else
                    $error = 'User not active . Please login to your dialer.';

                
            }            
            return response()->json(['status' => 'error', 'errors' => ['username' => $error]]);
            

        }

    }

    public function logout(){
        $call_id = request()->session()->get('dialer.call_id.' . auth()->user()->organization_id);
        $response = new VoiceResponse();
        $response->hangup();
        FunctionCall::modify_call($call_id,['responseXml'=>$response->xml()]);
        request()->session()->forget('dialer.call_id.' . auth()->user()->organization_id);
        request()->session()->put('dialer.call_id.' . auth()->user()->organization_id);

    }

    function isLoggedIn(){
        if(request()->session()->has('dialer.login.' . auth()->user()->organization_id)){
            $call_id = request()->session()->get('dialer.call_id.' . auth()->user()->organization_id);
            $call = Call::find($call_id);
            if($call && $call->status == CallStatusEnum::Established)
                return true;
        }
        return false; 
    }

    public function loginForm(){
        return view('dialer.login',['call_id'=>'dsfsd']);
    }

    public function index(){
        if(request()->session()->has('dialer.login.' . auth()->user()->organization_id)){
            $call_id = request()->session()->get('dialer.call_id.' . auth()->user()->organization_id);
            

            $outgoingCall = [];
            if(session()->has('dialer.outgoing.call_id')){
                $outgoingCall = Call::find(session('dialer.outgoing.call_id'));
            
                $outgoingCall =CallHandler::prepare_call_json($outgoingCall);

                if( count($outgoingCall) > 0 && $outgoingCall['status-code'] >= CallStatusEnum::Disconnected->value ){
                    $outgoingCall = [];
                    session()->forget('dialer.outgoing.call_id');
                }
            }
            
            $call = Call::find($call_id);
            if($call && $call->status->value < CallStatusEnum::Disconnected->value)
                return view('dialer.index',['outgoingCall' => $outgoingCall , 'call_id'=>$call_id,'login'=>session()->get('dialer.login.' . auth()->user()->organization_id)]);
            else    
                $this->logout();

        }

        return view('dialer.login',['call_id'=>'dsfsd']);

    }

    public function dial(){
        $tel_no = request()->query('tel_no');
        $call_id = request()->session()->get('dialer.call_id.' . auth()->user()->organization_id);
        $call = FunctionCall::send_call(['to'=>$tel_no,'response'=>route('webdialer.response'),'statusCallback'=>route('webdialer.responseCallback', ['client_id' => $call_id, 'organization_id' => auth()->user()->organization_id])]);
        if(isset($call['error'])){ 
            FunctionCall::send_to_websocket($call_id,['type'=>1,'data'=>['status'=>'Failed','call_id'=>'','status-code'=>3]]);
            return $call;
        }
        
        session(['dialer.outgoing.call_id' => $call['call_id']]);

        $voice_response = new VoiceResponse;
        $voice_response->bridge($call['call_id']);
        $voice_response->redirect(route('webdialer.response'));
        FunctionCall::modify_call($call_id,['responseXml'=>$voice_response->xml()]);
        $call['error'] = false;
        return $call;
    }

    public function hangup(){
        $response = new VoiceResponse();
        $response->hangup();
        return FunctionCall::modify_call(request()->get('call_id'),['responseXml'=>$response->xml()]);
    }

    // public function endCall($outgoing_call_id, $dialer_call_id){

    //     $outgoingCall = Call::find($outgoing_call_id);
    //     Log::info('outgoing------------');
    //     Log::info($outgoingCall);

    //     if(!CallHistory::where('call_id', $outgoingCall->id)->exists()){
            
    //         $callHistory = CallHistory::create([
    //             'organization_id'=> $outgoingCall->organization_id,
    //             'call_id'=>$outgoingCall->id,
    //             'bridge_call_id' => isset($outgoingCall->bridge_call_id) ? $outgoingCall->bridge_call_id : "",
    //             'duration' => $outgoingCall->duration,
    //             'record_file' => $outgoingCall->record_file,
    //             'status' => $outgoingCall->status
    //         ]);
    //     }

        
    //     $call = Call::find($dialer_call_id);
    //     Log::info('dialer call ------------');
    //     Log::info($call);
        
    //     if(!CallHistory::where('call_id', $call->id)->exists()){
    //         CallHistory::create([
    //             'organization_id'=>$call->organization_id,
    //             'call_id'=>$call->id,
    //             'bridge_call_id' => isset($call->bridge_call_id) ? $call->bridge_call_id : "",
    //             'duration' => $call->duration,
    //             'record_file' => $call->record_file,
    //             'status' => $call->status
    //         ]);

    //     }

    //     return response()->json(['status' => 'success']);

    // }

    public function forward(){
        $response = new VoiceResponse();
        $response->dial(request()->query('forward'));
        $response->hangup();
        return FunctionCall::modify_call(request()->get('call_id'),['responseXml'=>$response->xml()]);
    }

    public function getErrors( $validationErrors ) {

        $errors = [];

        foreach ( $validationErrors as $field => $error ) {

            if ( isset( $error[0] ) ) {
                $errors[$field] = $error[0];
            }

        }

        return $errors;

    }

    public function dialer_connect_response(){
        $voice_response = new VoiceResponse;
        $voice_response->pause(10);
        $voice_response->redirect(route('webdialer.response'));
        return $voice_response->xml();
    }

    public function dial_status_callback($client_id){
        $calldata = request()->input();
        //info($calldata);
        if($calldata['bridge_call_id'] != ''){
            $call = Call::find( $calldata['bridge_call_id'] );
            FunctionCall::send_to_websocket($client_id,['type'=>1,'data'=>CallHandler::prepare_call_json( $call,false )]);        
        }else
            FunctionCall::send_to_websocket($client_id,['type'=>1,'data'=>['status'=>'Failed','call_id'=>'','status-code'=>3]]);
        
        return $this->dialer_connect_response();
    }


    public function dialer_response_callback($client_id){
        $calldata = request()->input();
        // Log::info($client_id);
        Log::info($calldata);
    

        $type = 1;
        if($client_id == 'webdialer') {
            $client_id = $calldata['call_id']; 
            $type = 0; 
            if($calldata['status-code'] >= CallStatusEnum::Disconnected->value){
               // info("dialer disconnecting");
                //$this->logout();
            }

        }


        if($type == 1 && $calldata['status-code'] >= CallStatusEnum::Disconnected->value){

            if(!CallHistory::where('bridge_call_id', $calldata['call_id'])->exists()){
            
                CallHistory::create([
                    'organization_id'=> $calldata['organization_id'],
                    'call_id'=>$calldata['call_id'],
                    'bridge_call_id' => $client_id,
                    'duration' => $calldata['duration'],
                    'record_file' => isset($calldata['record_file']) ? $calldata['record_file'] : '',
                    'status' => CallStatusEnum::fromKey($calldata['status-code'])
                ]);
                
            }
        }

        $data = ['type'=>$type,'data'=>$calldata];
        FunctionCall::send_to_websocket($client_id,$data);
    }
}