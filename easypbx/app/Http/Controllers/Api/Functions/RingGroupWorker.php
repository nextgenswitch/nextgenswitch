<?php

namespace App\Http\Controllers\Api\Functions;
use App\Jobs\RingGroupJob;
use App\Http\Controllers\Api\FunctionCall;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Api\VoiceResponse;
use App\Models\RingGroup;
use App\Models\CallHistory;
use App\Models\Extension;
use App\Models\Call;
use App\Enums\CallStatusEnum;
use Carbon\Carbon;


class RingGroupWorker{
    private $id;
    private $func_id;

    function __construct($id,$func_id)
    {
        $this->id = $id;
        $this->func_id = $func_id;
    }

    public static function hangup($call_id){
        $response = new VoiceResponse();
        $response->hangup();
        FunctionCall::modify_call($call_id,['responseXml'=>$response->xml()]);
        //Http::asForm()->post(route('api.switch.call_modify'),['call_id'=>$call_id,'responseXml'=>$response->xml()]);
    }

    function redirect_failed_destination($ring_group,$parent_call_id){
        $response = new VoiceResponse();
        $response->redirect(route('api.func_call',['func_id'=>$ring_group->function_id,'dest_id'=>$ring_group->destination_id]));            
        FunctionCall::modify_call($parent_call_id,['responseXml'=>$response->xml()]);
        $this->cache_cleanup($parent_call_id);
        Log::info("hangup ring call here");
    }



    function send_to_extension($ring_group,$call_to_send,$parent_call_id){
        $group_calls = Cache::pull("RingCall:" . $parent_call_id,[]);
        
        while(1){
            if($ring_group->ring_strategy == 1 && sizeof($group_calls) > 0) break;
            $extension = array_shift($call_to_send);
            if(!$extension) break;
            $rjson = FunctionCall::send_call($extension);
            Log::debug("sent call response");
            Log::info($rjson);
            if(isset($rjson['error'])) continue;                    
            $call_id = $rjson["call_id"];
            if(!empty($call_id)) Call::where('id',$call_id)->update(['parent_call_id'=>$parent_call_id]);
            if(!empty($call_id) && $rjson['status-code'] < CallStatusEnum::Disconnected->value){                
                $group_calls[] = $call_id; //['call_id'=>$call_id,'connect_tm'=>time()];                
            }             
        }
        Log::debug("sent ring calls");
        Log::info($group_calls);

        

        if(sizeof($group_calls) > 0) Cache::put("RingCall:" . $parent_call_id,$group_calls,3600);
        else{
            $this->redirect_failed_destination($ring_group,$parent_call_id);
            return false;
        }

        if(sizeof($call_to_send) > 0){ 
            Cache::put("RingExtension:" . $parent_call_id,$call_to_send,3600);            
        }

      /*   if(Cache::has("RingCallStatus:" .$parent_call_id) == false ){
            Log::info("Ring call allready finished");
            foreach($group_calls as $queue_call)
                self::hangup($queue_call);
            $this->cache_cleanup($parent_call_id);
        }
 */

        return true;       

    }

 

    function send_calls($parent_call_id){
        Log::info("Ring calls to send");
        $ring_group = RingGroup::find($this->id);   
        if(!$ring_group) return;
        $call = Call::Find($parent_call_id);
        if(!$call) return;
        if($call->status->value > CallStatusEnum::Established->value) return;
        //$organization = Organization::find($ring_group->organization_id);
        $extensions = Extension::whereIn("id",explode(",",$ring_group->extensions))->get();
        $call_to_send = [];
        if(sizeof($extensions) == 0) return false;
        foreach( $extensions as $extension){
            
            $call_to_send[] = ['statusCallback'=>route('api.func_call',['func_id'=>$this->func_id,'dest_id'=>$ring_group->id,'queue_status'=>true,'extension'=>$extension->id,'parent_call_id'=>$parent_call_id]),
            'to'=>$extension->code,"organization_id"=>$extension->organization->id,"from"=>$call->caller_id,'timeout'=>$ring_group->ring_time,
            'response'=>route('api.func_call',['func_id'=>$this->func_id,'dest_id'=>$ring_group->id,'queue'=>true,'parent_call_id'=>$parent_call_id,'record'=>$extension->sipUser->record])];            
            if ($ring_group->allow_diversions && !empty($extension->forwarding_number))
                $call_to_send[] = ['statusCallback'=>route('api.func_call',['func_id'=>$this->func_id,'dest_id'=>$ring_group->id,'queue_status'=>true,'extension'=>$extension->id,'parent_call_id'=>$parent_call_id]),
                'to'=>$extension->forwarding_number,"organization_id"=>$extension->organization->id,"from"=>$call->caller_id,'timeout'=>$ring_group->ring_time,
                'response'=>route('api.func_call',['func_id'=>$this->func_id,'dest_id'=>$ring_group->id,'queue'=>true,'parent_call_id'=>$parent_call_id,'record'=>$extension->sipUser->record])];            

        }
        //Log::info($call_to_send);

     
        $this->send_to_extension($ring_group,$call_to_send,$parent_call_id);
        return true;

    }

    function cache_cleanup($call_id){

        $group_calls = Cache::pull("RingCall:" . $call_id,[]);
            Log::debug($group_calls);

            foreach($group_calls as $queue_call)
                self::hangup($queue_call);
        Cache::forget("RingCallStatus:" .$call_id);
        Cache::forget("RingExtension:" . $call_id);
        Cache::forget("RingCall:" . $call_id);
    }

 

    function process($response){   
        //$response = new VoiceResponse();
        $ring_group = RingGroup::find($this->id);
        $data = request()->all();        
        if(!$ring_group) return $response;
               
        if(request()->query('queue_join') == 1){
            Log::debug("call establishing here in ring group");
            $group_calls = Cache::pull("RingCall:" . $data['bridge_call_id'],[]);
            //unset($group_calls[$data["call_id"]]);
            foreach($group_calls as $queue_call)
                if($data["call_id"] != $queue_call) self::hangup($queue_call);

            Cache::put("RingCall:" . $data['bridge_call_id'],$group_calls,3600);
            Cache::put("RingCallStatus:" .$data['bridge_call_id'],CallStatusEnum::Established->value,3600);
            //$this->hangup_ring_group($data['bridge_call_id'],$data["call_id"]);
            if(isset($data['bridge_call_id'])){
                $response->bridge($data['bridge_call_id']);
            }else{
                $response->say("bridge call not found");
            }

        }elseif(request()->query('queue_status') == 1){
            $status_data = request()->input();
            $parent_call_id = request()->query('parent_call_id');
            Log::info("Ring status callback " . Cache::get("RingCallStatus:" .$parent_call_id,-1));
            //Log::info($status_data);
            
            if((int) $status_data['status-code'] < CallStatusEnum::Disconnected->value) return; 
            if(Cache::get("RingCallStatus:" .$parent_call_id,-1) == -1){
                $this->cache_cleanup($parent_call_id);
                return;
            }
            
            if(Cache::get("RingCallStatus:" .$parent_call_id,false) == CallStatusEnum::Established->value) 
                return;
            
            
            $group_calls = Cache::pull("RingCall:" . $parent_call_id,[]);
            foreach($group_calls as $k=>$gcall)
                if($gcall == $status_data['call_id']) unset($group_calls[$k]);
            if(sizeof($group_calls) > 0)
                Cache::put("RingCall:" . $parent_call_id,$group_calls,3600);
            Log::info("group calls after unset");
            Log::info($group_calls);
            if($ring_group->ring_strategy == 1){
                $call_to_send = Cache::get("RingExtension:" . $parent_call_id,[]);            
                $this->send_to_extension($ring_group,$call_to_send,$parent_call_id);
            }else{
                if(sizeof($group_calls) ==0)
                    $this->redirect_failed_destination($ring_group,$parent_call_id);
            }
           
        }elseif(request()->query('queue_result') == 1){
            Log::debug("ring queue result post here");

            $data = request()->input();
            CallHistory::create(
                ['organization_id'=>$ring_group->organization_id,'call_id'=>$data['bridge_call_id'],'bridge_call_id'=>$data['call_id'],'duration'=>$data['duration'],'status'=>CallStatusEnum::Disconnected,'record_file'=>isset($data['record_file'])?$data['record_file']:""]
            );

        }elseif(request()->query('queue') == 1){
            $options = ['action'=>route('api.func_call',['func_id'=>$this->func_id,'dest_id'=>$ring_group->id,'queue_result'=>true,'bridge_call_id'=>$data['parent_call_id']])];
            if(request()->query('record') == 1)
                $options['record'] = 'record-from-answer';
            $dial = $response->dial('',$options);

            $dial->queue("queue_" . $this->id,['url'=>route('api.func_call',['func_id'=>$this->func_id,'dest_id'=>$ring_group->id,'queue_join'=>true,'bridge_call_id'=>$data['parent_call_id']])]);
            //$response->say("Thanks for providing the support");
        }elseif(request()->query('enqueue_result') == 1){
            Log::debug("Ring group finished here". $data['call_id']);

            if(intval(request()->input('dial_status')) == 0){
                $data = request()->input();
               
               /*  CallHistory::create(
                    ['organization_id'=>$ring_group->organization_id,'call_id'=>$data['call_id'],'bridge_call_id'=>"",'status'=>CallStatusEnum::Failed]
                ); */
            }                        
            //$response->say("this is a after enqueue end");
            $this->cache_cleanup($data['call_id']);

            $response->redirect(route('api.func_call',['func_id'=>$ring_group->function_id,'dest_id'=>$ring_group->destination_id]));
         
        }elseif(request()->query('join') == 1){
           
            if($this->send_calls($data["call_id"])){
                if($ring_group->answer_channel && $ring_group->ringback_tone)
                $response->play(storage_path( 'app/public/sounds/ring_tone.wav' ),['loop'=>10,'localfile'=>true]);   
                Cache::put("RingCallStatus:" .$data["call_id"],CallStatusEnum::Dialing->value,3600);
            }else
                $response->leave();
            
        }else{
         
            $response->enqueue("queue_" . $this->id,['answerOnBridge'=>($ring_group->answer_channel)?false:true,
            'action'=>route('api.func_call',['func_id'=>$this->func_id,'dest_id'=>$ring_group->id,'enqueue_result'=>true]),
            'waitUrl'=>route('api.func_call',['func_id'=>$this->func_id,'dest_id'=>$ring_group->id,'join'=>true])
            ]);
            $response->redirect(route('api.func_call',['func_id'=>$ring_group->function_id,'dest_id'=>$ring_group->destination_id]));
         
                   
        }
        
       
        return $response;
    }


}
