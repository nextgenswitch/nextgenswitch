<?php

namespace App\Http\Controllers\Api\Functions;
use App\Enums\CallStatusEnum;
use App\Enums\QueueStatusEnum;
use App\Http\Controllers\Api\FunctionCall;
use App\Http\Controllers\Api\VoiceResponse;
use App\Models\CallQueue;
use App\Models\Queue;
use App\Models\QueueCall;
use App\Models\Call;
use App\Models\CallHistory;
use App\Models\CallQueueExtension;
use App\Models\SipChannel;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class QueueWorker{
    private $id;
    private $func_id;
    private $call_queue;
    function __construct($id,$func_id)
    {
        $this->id = $id;
        $this->func_id = $func_id;
        $this->call_queue = CallQueue::find($this->id);  
    }

    public function hangup($call_id){
        $response = new VoiceResponse();
        $response->hangup();
        $call = FunctionCall::modify_call($call_id,['responseXml'=>$response->xml()]);
       
        
    }

    public function hangup_queue_calls($call_id = NULL){
        info("hangup all call");
        if($this->queue_has_calls() == 0){
            $calls = QueueCall::where("call_queue_id",$this->id)->where('status','<',CallStatusEnum::Established->value)->get();
            //info($calls);
            foreach($calls as $call){
                if(!$call_id || $call->call_id != $call_id) $this->hangup($call->call_id);
            }
        }
    }

    function dial_forward($extension){
        if(!$extension->allow_diversion || empty($extension->forwarding_number) ) return false;
        $caller_id = $this->call_queue->extension->code;
        if(!empty($this->call_queue->cid_name_prefix)) $caller_id =$this->call_queue->cid_name_prefix . $caller_id;
        $dial =  ['id'=>$extension->id,'to'=>$extension->forwarding_number,"organization_id"=>$this->call_queue->organization_id,"from"=> $caller_id,'timeout'=>($this->call_queue->member_timeout > 0)?$this->call_queue->member_timeout:30,
        'response'=>route('api.func_call',['func_id'=>$this->func_id,'dest_id'=>$this->call_queue->id,'queue'=>true,'ext_id'=>$extension->id]),
        'statusCallback'=>route('api.func_call',['func_id'=>$this->func_id,'dest_id'=>$this->call_queue->id,'queue_status'=>true])
        ];
        $call = FunctionCall::send_call($dial); 
        return $call;
    }

    function dial_extension($extension){
        info("dialing extension " . $extension->id . " " . $extension->destination_id);
        if(SipChannel::where("sip_user_id",$extension->destination_id)->count() == 0) return false;
        if(QueueCall::where("call_queue_id",$this->id)->where('status','<=',CallStatusEnum::Established->value)->where('extension_id',$extension->id)->count() > 0) return false;
        if($extension->last_ans < $this->call_queue->wrap_up_time) return false;
        if($extension->last_dial < $this->call_queue->retry) return false;
        info("calling extension " . $extension->id . " " . $extension->destination_id);
        $caller_id = $this->call_queue->extension->code;
        if(!empty($this->call_queue->cid_name_prefix)) $caller_id =$this->call_queue->cid_name_prefix . $caller_id;
        $dial =  ['id'=>$extension->id,'to'=>$extension->code,"organization_id"=>$this->call_queue->organization_id,"from"=> $caller_id,'timeout'=>($this->call_queue->member_timeout > 0)?$this->call_queue->member_timeout:30,
        'response'=>route('api.func_call',['func_id'=>$this->func_id,'dest_id'=>$this->call_queue->id,'queue'=>true,'ext_id'=>$extension->id]),
        'statusCallback'=>route('api.func_call',['func_id'=>$this->func_id,'dest_id'=>$this->call_queue->id,'queue_status'=>true])
        ];
        $call = FunctionCall::send_call($dial); 
        //info($call);
        if(isset($call['error']) || $call['status-code'] > CallStatusEnum::Disconnected->value){
            $call = $this->dial_forward($extension);
            if(!$call || (isset($call['error']) || $call['status-code'] > CallStatusEnum::Disconnected->value))
                return false; 
        } 
        //if() return false;
        QueueCall::create(['call_id'=>$call['call_id'],'status'=>$call['status-code'],'extension_id'=>$extension->id,'call_queue_id'=>$this->id,'organization_id'=>$this->call_queue->organization_id]);      
        CallQueueExtension::where("call_queue_id",$this->id)->where('extension_id',$extension->id)->update(['last_dial'=>now()]);
        return $call;
    }

    private  function order_least_assigned($a, $b){
        
    }

    private  function order_fewest_assigned($a, $b){
        
    }


    private  function order_weight_random($a, $b){
        $vals = [-1, 1];
        return $vals[array_rand($vals, 1)];
    }

    private  function order_priority($a, $b){
        return ($a->priority > $b->priority) ? 1 : -1;
    }

    private  function order_last_dial($a, $b){
        return ($a->last_dial < $b->last_dial) ? 1 :- 1;
        
    }

    private function order_least_talk($a, $b){
        $aTalkTime = QueueCall::where('created_at', '>=', Carbon::now()->subDay())->where('extension_id', $a->id)->sum('duration');
        $bTalkTime = QueueCall::where('created_at', '>=', Carbon::now()->subDay())->where('extension_id', $b->id)->sum('duration');
        // info('Talk Time - ' . $aTalkTime. ' ' . $bTalkTime);

        return $aTalkTime > $bTalkTime ? 1 : -1;
    }

    private function order_fewest_answered($a, $b){
        $aAns = QueueCall::where('created_at', '>=', Carbon::now()->subDay())->where('extension_id', $a->id)->where('status', CallStatusEnum::Disconnected->value)->count();
        $bAns = QueueCall::where('created_at', '>=', Carbon::now()->subDay())->where('extension_id', $b->id)->where('status', CallStatusEnum::Disconnected->value)->count();

        return $aAns > $bAns ? 1 : -1;
    }   
   

    function queue_has_calls(){
      
        $queue_calls = Queue::where('call_queue_id',$this->call_queue->id)->where('status','<',QueueStatusEnum::Bridged->value);
        return $queue_calls->count();
    }

    function update_queue_calls(){
        //Cache::put("RingExtension:" . $parent_call_id,$call_to_send,3600);  
        QueueCall::where("call_queue_id",$this->id)->where('status','<',CallStatusEnum::Established->value)->where('created_at', '<' , now()->subMinutes(1))->update(['status'=>CallStatusEnum::Failed->value]);
         $queue_calls = Cache::pull("QueueCalls:" . $this->call_queue->id,[]);
        info("updating queue calls");
        
        foreach($queue_calls as $call_id=>$status_code){
            $call = Call::find($call_id);
            $row = QueueCall::where("call_queue_id",$this->id)->where('call_id',$call_id)->update(['status'=>$call->status]);
            info($call_id . "  " . $row);
            if($call->status->value > CallStatusEnum::Established->value) 
                unset($queue_calls[$call_id]);
        }
        Cache::put("QueueCalls:" . $this->call_queue->id,$queue_calls,3600);
        info($queue_calls); 
            
    } 



    function handle(){
        $this->update_queue_calls();
        $call_count = $this->queue_has_calls();
        if($call_count > 0){

            $extensionlist = $this->call_queue->extensions;

            info(count($extensionlist));
            
            usort($extensionlist,array($this,'order_last_dial'));
           // info((array) $extensionlist);
            foreach($extensionlist as $ext)
                info($ext);
            if($this->call_queue->strategy == 0){
                foreach($extensionlist as $extension)
                    $this->dial_extension($extension);
            }
            else{
                info('before sorting');
                info($extensionlist);
                
                
                if($this->call_queue->strategy == 2){
                    usort($extensionlist,array($this,'order_priority'));
                }   
                
                if($this->call_queue->strategy == 3){
                    usort($extensionlist,array($this,'order_weight_random'));

                }   
                
                if($this->call_queue->strategy == 5){
                    usort($extensionlist,array($this,'order_least_talk'));
                }   
                
                if($this->call_queue->strategy == 6){
                    usort($extensionlist,array($this,'order_fewest_answered'));
                }                  

                info('after sorting');
                info($extensionlist);

                $sent = QueueCall::where("call_queue_id",$this->id)->where('status','<=',CallStatusEnum::Established->value)->count();
                foreach($extensionlist as $extension){
                    if($sent >= $call_count) break;
                    if($this->dial_extension($extension))
                        $sent++;
                    
                }
            }
            
            $queue_calls = Queue::where('call_queue_id',$this->call_queue->id)->where('status','<',QueueStatusEnum::Bridged->value)->get();
            //info($queue_calls);
            foreach($queue_calls as $qcall){
                if(($qcall->created_at->diffInSeconds(now()) > $this->call_queue->queue_timeout) || (sizeof($extensionlist) == 0 && $this->call_queue->leave_when_empty)  ){
                    info("queue call has timeout now");
                    $response = new VoiceResponse();
                    $response->redirect(route('api.func_call',['func_id'=>$this->call_queue->function_id,'dest_id'=>$this->call_queue->destination_id]));
                    FunctionCall::modify_call($qcall->call_id,['responseXml'=>$response->xml()]);
                    Queue::where("call_id",$qcall->call_id)->update(['status'=>QueueStatusEnum::Timeout]);
                }
            }
            $resp = FunctionCall::create_worker(['url'=>route('api.call_queue_worker_execute',['id'=>$this->call_queue->id,'func_id'=>$this->func_id]),'name'=>"queue:" . $this->call_queue->id,'delay'=>$this->call_queue->retry]);
          
        }

    }

    function queue_name(){
        return preg_replace("/[^a-zA-Z0-9]+/", "", $this->call_queue->name);
    }

    function process($response){        
        $data = request()->all();
       // info($data);
        if(!$this->call_queue) return $response;
       
        if(request()->query('queue_result') == 1){  // this is called when agent call finally ended
            info("Queue dial result here");
            info($data);
            if(isset($data['record_file']))
                Queue::where("call_id",$data['bridge_call_id'])->update(['record_file'=>$data['record_file']]);
            
            CallQueueExtension::where("call_queue_id",$this->id)->where('extension_id',request()->query('ext_id'))->update(['last_ans'=>now()]);
            if(isset($data['bridge_call_id']))
            CallHistory::create(
                ['organization_id'=>$this->call_queue->organization_id,'call_id'=>$data['bridge_call_id'],'bridge_call_id'=>$data['call_id'],'duration'=>$data['duration'],'status'=>CallStatusEnum::Disconnected,'record_file'=>isset($data['record_file'])?$data['record_file']:""]
            );

            if(isset($data['duration']) && $data['duration'] > 0){
                QueueCall::where("call_queue_id",$this->id)->where('call_id', $data['call_id'])->update(['duration' => $data['duration']]);
            }            
            
            
        }elseif(request()->query('queue_status') == 1){  // this is called when agent  call status comes 
            $calldata = request()->input();
            
            $row = QueueCall::where("call_queue_id",$this->id)->where('call_id',$calldata['call_id'])->update(['status'=>$calldata['status-code']]);
             if($row == 0){

                $queue_calls = Cache::pull("QueueCalls:" . $this->call_queue->id,[]);
                $queue_calls[$calldata['call_id']] = $calldata['status-code'];
                Cache::put("QueueCalls:" . $this->call_queue->id,$queue_calls,3600);
                info("queue calls to update ");
                info($queue_calls);
            
                
            } 
            if((int) $calldata['status-code'] < CallStatusEnum::Established->value) return $response;
            elseif((int) $calldata['status-code'] > CallStatusEnum::Established->value) $this->handle();
          
          
        }else if(request()->query('queue_join') == 1){       // this is called when agent  joining a call from queue
       
            //Log::debug("call bridging here");
            info("agent joining call");
            info($data);
            $queue = Queue::where("queue_name",$data["name"])->where("organization_id",$this->call_queue->organization_id)->where("status",0)->orderBy('created_at','asc')->first();

            if($queue){
                Queue::where("call_id",$queue["call_id"])->update(['status'=>QueueStatusEnum::Bridged,'bridge_call_id'=>$data['call_id']]);
                
                $response->bridge($queue['call_id']);
                $this->hangup_queue_calls($data['call_id']);
                
            }else{
                $response->leave();
            }
        
        }else if(request()->query('queue') == 1){   // this is called when agent entering in the queue
            if(!empty($this->call_queue->agentAnnouncement)) FunctionCall::voice_file_play($response,$this->call_queue->agentAnnouncement); 
            $options = ['action'=>route('api.func_call',['func_id'=>$this->func_id,'dest_id'=>$this->call_queue->id,'queue_result'=>true,'ext_id'=>request()->query('ext_id')])];
            if($this->call_queue->record == true)
                $options['record'] = 'record-from-answer';

            $dial = $response->dial('',$options);
            $dial->queue($this->queue_name(),['url'=>route('api.func_call',['func_id'=>$this->func_id,'dest_id'=>$this->call_queue->id,'queue_join'=>true])]);
            
        
        }else if(request()->query('join') == 1){   // this is called when call joing in the queue
                info("on join");
                $extensions = $this->call_queue->extensions;
                
                if(sizeof($extensions) > 0 || $this->call_queue->join_empty){
                    Queue::create(['call_id'=>$data['call_id'],'call_queue_id'=>$this->call_queue->id,'organization_id'=>$this->call_queue->organization_id,'queue_name'=>$data['name'],'status'=>QueueStatusEnum::Queued]);        
                                            
                    $this->handle();                
                    if(!empty($this->call_queue->joinAnnouncement)) FunctionCall::voice_file_play($response,$this->call_queue->joinAnnouncement);           
                    if(!empty($this->call_queue->musicOnHold)) FunctionCall::voice_file_play($response,$this->call_queue->musicOnHold); 
                    else $response->play(storage_path( 'app/public/sounds/music_on_hold.mp3' ), ['localfile' => "true"]);
                            
                    $response->redirect(route('api.func_call',['func_id'=>$this->func_id,'dest_id'=>$this->call_queue->id,'rejoin'=>true]));     
                    
                }else                 
                    $response->leave();
           
                
        }else if(request()->query('rejoin') == 1){ // this is called when call rejoing in the queue after playing the music on hold
            if(!empty($this->call_queue->joinAnnouncement)) FunctionCall::voice_file_play($response,$this->call_queue->joinAnnouncement);           
            if(!empty($this->call_queue->musicOnHold)) FunctionCall::voice_file_play($response,$this->call_queue->musicOnHold); 
            else $response->play(storage_path( 'app/public/sounds/music_on_hold.mp3' ), ['localfile' => "true"]);
            $response->redirect(route('api.func_call',['func_id'=>$this->func_id,'dest_id'=>$this->call_queue->id,'rejoin'=>true]));     

        }else if(request()->query('enqueue_result') == 1){  // this is called when queue call finally ended
            $qcall = Queue::where("call_id",$data['call_id'])->first();
            //info("queue call updating");
            //info($qcall);
            info("on queue call end");
            info($data);
            if(!$qcall) return;
            $status = $qcall->status;
            if($data['dial_status'] == "0"){
                $status = ($qcall->status == QueueStatusEnum::Timeout)?QueueStatusEnum::Timeout:QueueStatusEnum::Abandoned;         
            }else   
                $status =   QueueStatusEnum::Disconnected;

            Queue::where("call_id",$data['call_id'])->update(['bridge_call_id'=>isset($data['bridge_call_id'])?$data['bridge_call_id']:"",'duration'=>$data['duration'],'waiting_duration'=>$data['waiting_duration'],'status'=>$status]);
       
            $this->hangup_queue_calls();
        }else{    // this is called when a queue first time enter in a queue
            
            $options = [
                'action'=>route('api.func_call',['func_id'=>$this->func_id,'dest_id'=>$this->call_queue->id,'enqueue_result'=>true]),
                'answerOnBridge'=>false,
                'waitUrl'=>route('api.func_call',['func_id'=>$this->func_id,'dest_id'=>$this->call_queue->id,'join'=>true])];
            
            $response->enqueue($this->queue_name(),$options);
            $response->redirect(route('api.func_call',['func_id'=>$this->call_queue->function_id,'dest_id'=>$this->call_queue->destination_id]));
        }

       // Log::debug($response->xml());

        return $response;
    }
}
