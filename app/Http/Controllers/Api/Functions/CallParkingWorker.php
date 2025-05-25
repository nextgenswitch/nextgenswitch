<?php

namespace App\Http\Controllers\Api\Functions;

use App\Http\Controllers\Api\FunctionCall;
use App\Models\CallParking;
use App\Models\CallParkingLog;
use App\Models\CallHistory;
use App\Models\Call;
use App\Enums\CallStatusEnum;
use App\Http\Controllers\Api\VoiceResponse;

class CallParkingWorker{
    private $call_parking;
    private $params;
   

    function __construct($id,$params = [])
    {

        $this->call_parking = CallParking::find($id);
        $this->params = $params;
       
    }

    function timeout(){
        $park_calls = CallParkingLog::with(['call'])->where("call_parking_id",$this->call_parking->id)->get();
       
        foreach($park_calls as $park_call){
            info("debug call parking " . $park_call->created_at->diffInSeconds(now()) . " " . $this->call_parking->timeout);
            if($park_call->created_at->diffInSeconds(now()) > $this->call_parking->timeout){
                $response = new VoiceResponse();
                $response->redirect(route('api.func_call',['func_id'=>$this->call_parking->function_id,'dest_id'=>$this->call_parking->destination_id])); 
                FunctionCall::modify_call($park_call->call_id,['responseXml'=>$response->xml()]);
                CallParkingLog::where("call_parking_id",$this->call_parking->id)->where('call_id',$park_call->call_id)->delete(); 
                info("call park deleteing call id " . $park_call->call_id);
            }
        }

        if(CallParkingLog::where("call_parking_id",$this->call_parking->id)->count() > 0){
            $resp = FunctionCall::create_worker(['url'=>route('api.call_park_worker_execute',['id'=>$this->call_parking->id]),'name'=>"call_parking:" . $this->call_parking->id,'delay'=>5]);
          
        }
    }


    function process($response){

       info("on call parking");
        if(!$this->call_parking) return $response;
       
      
        
        $parking_slots = [];
        for($i = $this->call_parking->extension_no + 1;$i<($this->call_parking->extension_no + $this->call_parking->no_of_slot);$i++){
            $parking_slots[$i] = $i; 
        }
        $parked = false;
        $parking_logs = CallParkingLog::where("call_parking_id",$this->call_parking->id)->get();
        foreach($parking_logs as $parking_log){
            $parking_slots[$parking_log->parking_no] = $parking_log->call_id;
        }

        //info($parking_slots);
       
        if(request()->query('enqueue_result') == 1){
            info("enqueue result here");
           // info(request()->input());
            $data = request()->input();
            
            
           
            if(isset($parking_slots[request()->query('parking_no')]) && $parking_slots[request()->query('parking_no')] == $data['call_id']){
                info("reset parking info");
                CallParkingLog::where("call_parking_id",$this->call_parking->id)->where('parking_no',request()->query('parking_no'))->delete();
            }
        
            return;

        }

        if(request()->query('parking_result') == 1){
            $data = request()->input();
            if($data['dial_status'] = 1){
                $call = Call::find($data['call_id']);
                CallHistory::create(
                    ['organization_id'=>$call->organization_id,'call_id'=>$data['call_id'],'bridge_call_id'=>$data['bridge_call_id'],'duration'=>$data['duration'],'status'=>CallStatusEnum::Disconnected,'record_file'=>isset($data['record_file'])?$data['record_file']:""]
                );
            }
            return;
        }

        if(request()->query('parking_join') == 1){
            
            $parking_no = request()->query('parking_no');      
            //info("call park joining " . $parking_slots[$parking_no]);      
            $response->bridge($parking_slots[$parking_no]);
            CallParkingLog::where("call_parking_id",$this->call_parking->id)->where('parking_no',$parking_no)->delete();
           
            return $response;
        }

        

        
        if(request()->query('rejoin') == 1){
            FunctionCall::voice_file_play($response,$this->call_parking->musicOnHold);
            $response->redirect(route('api.func_call',['func_id'=>'call_parking','dest_id'=>$this->call_parking->id,'rejoin'=>1])); 
        }

        if(request()->query('join') == 1){ // need to park this call
       
            $parking_no = request()->query('parking_no');
            FunctionCall::voice_file_play($response,$this->call_parking->musicOnHold);
            CallParkingLog::create(['call_parking_id'=>$this->call_parking->id,'parking_no'=>$parking_no,'call_id'=>request()->input('call_id'),
            'organization_id'=>$this->call_parking->organization_id,'from'=>request()->query('from'),'to'=>'']);
            //info("call is parked");
            $response->redirect(route('api.func_call',['func_id'=>'call_parking','dest_id'=>$this->call_parking->id,'rejoin'=>1])); 
            $this->timeout();
            return $response;    
        }

        $parking_no = $this->call_parking->extension_no;
        
        if(isset($parking_slots[$this->params['event_to']])){
            //info("checking if allready parked" . $parking_slots[$this->params['event_to']]);
            $parking_no = $this->params['event_to'] ;
            //info($parking_slots[$parking_no]);
            if(!is_numeric($parking_slots[$parking_no]))
                $parked = true;
            
        }else{
            foreach($parking_slots as $slot=>$call_id){
                if(is_numeric($call_id)){
                    $parking_no  = $slot;
                    break;
                }
            }
        }

        //info("parking no ". $parking_no . " " . $parked);

        if($parking_no > 0){
           
            if($parked){
               
                $options = ['action'=>route('api.func_call',['func_id'=>'call_parking','dest_id'=>$this->call_parking->id,'parking_result'=>true])];
                if($this->call_parking->record == true)
                    $options['record'] = 'record-from-answer';
    
                $dial = $response->dial('',$options);
                $dial->queue("call_parking:" . $this->call_parking->id,['url'=>route('api.func_call',['func_id'=>'call_parking','dest_id'=>$this->call_parking->id,'parking_join'=>true,'parking_no'=>$parking_no])]);
                return $response;
                
            }else{
               
                $options = [
                    'action'=>route('api.func_call',['func_id'=>'call_parking','dest_id'=>$this->call_parking->id,'enqueue_result'=>true,'parking_no'=>$parking_no]),
                    'answerOnBridge'=>false,
                    'waitUrl'=>route('api.func_call',['func_id'=>'call_parking','dest_id'=>$this->call_parking->id,'parking_no'=>(int)$parking_no,'join'=>true,'from'=>$this->params['event_from']])];
                
                $response->enqueue('call_parking:' . $this->call_parking->id,$options);
                
                $response->redirect(route('api.func_call',['func_id'=>$this->call_parking->function_id,'dest_id'=>$this->call_parking->destination_id])); 
           
          //     info('parking Enqueue  here');
                
            }

        }

        //info("xml is " . $response->xml());

        return $response;    

    }

}