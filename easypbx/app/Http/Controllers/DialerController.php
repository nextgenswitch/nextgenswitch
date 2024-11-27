<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SipUser;
use App\Models\Call;
use App\Models\Func;
use App\Models\CallHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\FunctionCall;
use App\Http\Traits\FuncTrait;
use App\Enums\CallStatusEnum;
use App\Http\Controllers\Api\VoiceResponse;
use App\Http\Controllers\Api\Functions\CallHandler;

class DialerController extends Controller {
    use FuncTrait;

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
            //info("dialer outgoing call id on index " . session('dialer.outgoing.call_id'));

            $outgoingCall = [];
            $oCall = Call::where('parent_call_id',$call_id)->where('status','<',CallStatusEnum::Disconnected->value)->first();
            //info("on index");
            //info($oCall);
            if($oCall){
                
                $outgoingCall = CallHandler::prepare_call_json($oCall);
                //info($outgoingCall);
                if( count($outgoingCall) > 0 && $outgoingCall['status-code'] >= CallStatusEnum::Disconnected->value ){
                    $outgoingCall = [];
                    //session()->forget('dialer.outgoing.call_id');
                }
            }
            
            $call = Call::find($call_id);
            if($call && $call->status->value < CallStatusEnum::Disconnected->value){
                $dialer_functions = Func::getFuncList();
                return view('dialer.index',['dialer_functions' => $dialer_functions, 'outgoingCall' => $outgoingCall , 'call_id'=>$call_id,'login'=>session()->get('dialer.login.' . auth()->user()->organization_id)]);
            }
            else    
                $this->logout();

        }

        return view('dialer.login',['call_id'=>'dsfsd']);

    }

    public function destinations($function){

        if ( request()->ajax() ) {
            return $this->dist_by_function( $function );
        }           

        die();

    }


    /*  public function dial(){
        
        $tel_no = request()->query('tel_no');
        $call_id = request()->session()->get('dialer.call_id.' . auth()->user()->organization_id);
        $dialercall = Call::find($call_id);
        info($dialercall);
        $call = FunctionCall::send_call(['to'=>$tel_no,'from'=>$dialercall->destination,'response'=>route('webdialer.response'),'statusCallback'=>route('webdialer.responseCallback', ['client_id' => $call_id, 'organization_id' => auth()->user()->organization_id])]);
        if(isset($call['error'])){ 
            FunctionCall::send_to_websocket($call_id,['type'=>1,'data'=>['status'=>'Failed','call_id'=>'','status-code'=> CallStatusEnum::Failed]]);
            return $call;
        }
        
        session(['dialer.outgoing.call_id' => $call['call_id']]);

        $voice_response = new VoiceResponse;
        $voice_response->bridge($call['call_id']);
        $voice_response->redirect(route('webdialer.response'));
        FunctionCall::modify_call($call_id,['responseXml'=>$voice_response->xml()]);
        $call['error'] = false;
        return $call;
    }  */
   
    public function dial(){
        $tel_no = request()->query('tel_no');
        $record = request()->query('record',true);
        $call_id = request()->session()->get('dialer.call_id.' . auth()->user()->organization_id);
        $call = Call::find($call_id);
        $voice_response = new VoiceResponse;
       // session(['dialer.outgoing.tel_no' => $tel_no]);
        $voice_response->dial($tel_no,['record'=>$record,'callerId'=>$call->destination,'action'=>route('webdialer.statusCallback',['client_id' => $call_id]),'statusCallback'=>route('webdialer.responseCallback', ['client_id' => $call_id, 'tel_no' => $tel_no])]);
        $voice_response->redirect(route('webdialer.response'));
        FunctionCall::modify_call($call_id,['responseXml'=>$voice_response->xml()]);
        return ['success'=>true];
    } 

    public function hangup(){
        $response = new VoiceResponse();
        //$response->hangup();
        $call_id = request()->session()->get('dialer.call_id.' . auth()->user()->organization_id);
        $response->redirect(route('webdialer.response'));
        return FunctionCall::modify_call($call_id,['responseXml'=>$response->xml()]);
    }

    

    public function forward(Request $request){
        $response = new VoiceResponse();

        if($request->has('forward')){
            $response->dial($request->input('forward'));
        }

        else if( $request->has('function_id') && $request->has('destination_id')){
            $func = Func::select('id')->where('func', $request->input('function_id'))->first();

            $response->redirect(route('api.func_call',[
                'func_id'=> $func->id,
                'dest_id'=>$request->input('destination_id')
            ]));    
        }
        
        $response->hangup();
        FunctionCall::modify_call(request()->get('call_id'),['responseXml'=>$response->xml()]);

        
        
        //FunctionCall::send_to_websocket(request()->session()->get('dialer.call_id.' . auth()->user()->organization_id),['type'=>1,'data'=>['status'=>'Disconnected','call_id'=>'','status-code'=>3]]);
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
        //info("dialer connect response");
        //info(request()->input());
        $voice_response = new VoiceResponse;
        $voice_response->pause(10);
        $voice_response->redirect(route('webdialer.response'));
        return $voice_response->xml();
    }

    public function dial_status_callback($client_id){
        $calldata = request()->input();
        info("dialer action  callback " . $client_id);
        $call = Call::find($calldata['call_id']);
       

        if($calldata['bridge_call_id'] != ''){
            $call_history = [
                'organization_id'=> $call->organization_id,
                'call_id'=>$calldata['call_id'],
                'bridge_call_id' => $calldata['bridge_call_id'],
                'duration' => $calldata['duration'],
                'record_file' => isset($calldata['record_file']) ? $calldata['record_file'] : '',
                'status' => ($calldata['dial_status'] == 1)?CallStatusEnum::Disconnected->value:CallStatusEnum::Failed->value
            ];
            CallHistory::create(
               $call_history
            );
 
            $call = Call::find( $calldata['bridge_call_id'] );
            $call_resp = CallHandler::prepare_call_json( $call,false );
            $call_resp['duration'] = $calldata['duration'];
            $call_resp['status-code'] = $call_history['status'];
            $call_resp['status'] = ($calldata['dial_status'] == 1)?CallStatusEnum::Disconnected->getText():CallStatusEnum::Failed->getText();
            $call_resp['record_file'] = isset($calldata['record_file']) ? $calldata['record_file'] : '';
            FunctionCall::send_to_websocket($client_id,['type'=>1,'data'=>$call_resp]);        
        }else
            FunctionCall::send_to_websocket($client_id,['type'=>1,'data'=>['status'=>'Failed','call_id'=>'','status-code'=>3]]);
        
        
        return $this->dialer_connect_response();
    }


    public function dialer_response_callback($client_id){
        $calldata = request()->input();
      
        info("dialer response callback");
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

 

        $data = ['type'=>$type,'data'=>$calldata];
        FunctionCall::send_to_websocket($client_id,$data);
    }
}