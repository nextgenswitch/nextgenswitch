<?php

namespace App\Http\Controllers\Api\Functions;

use App\Http\Controllers\Api\FunctionCall;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Api\VoiceResponse;
use App\Models\OutboundRoute;
use App\Models\CallHistory;
use App\Models\Call;
use App\Enums\CallStatusEnum;
use Carbon\Carbon;


class OutboundWorker{
    private $id;
    private $params;
    private $dest;
    

    function __construct($id,$params)
    {
        $this->id = $id;
        $this->params = $params;
    }



    function send_call($outbound_route,$response,$index = 0){
        $routes = [];
        foreach($outbound_route->trunks as $key=>$trunk){
            $routes[] = ['event_to'=>$this->dest,'channel_id'=>$trunk->sip_user_id,'answerOnBridge'=>'true','record'=>'record-from-answer',
            'action'=>route('api.func_call',['func_id'=>'outbound_route','dest_id'=>$outbound_route->id,'verify'=>true,'queue'=>true,'index'=>$key,'dest'=>$this->dest])];
        }
        //info($routes);
        //info("trying index " . $index);
        
        if(isset($routes[$index])){
            $route = $routes[$index];
            $response->dial($route['event_to'],$route); 
        }else
            $response->redirect(route('api.func_call',['func_id'=>$outbound_route->function_id,'dest_id'=>$outbound_route->destination_id]));
        
    }

    function process_calls($response){
        //info($this->params);
        $this->dest = request()->query('dest');
        $index = request()->query('index',0);
        $index++;
        $data = request()->input();
        //info($data);
        $outbound_route = OutboundRoute::find($this->id);
        if(request()->input('dial_status') == "0"){
            $this->send_call($outbound_route,$response,$index);
            CallHistory::create(
                ['organization_id'=>$outbound_route->organization_id,'call_id'=>$data['call_id'],'bridge_call_id'=>empty($data['bridge_call_id'])?"":$data['bridge_call_id'],'status'=>CallStatusEnum::Failed]
            );
        }else{
            $response->hangup();
            CallHistory::create(
                ['organization_id'=>$outbound_route->organization_id,'bridge_call_id'=>$data['bridge_call_id'],'call_id'=>$data['call_id'],'duration'=>$data['duration'],'status'=>CallStatusEnum::Disconnected,'record_file'=>isset($data['record_file'])?$data['record_file']:""]
            );
        }
        
    }

    function process($response){
        info("on outbound call");
        $this->dest = $this->params['event_to'];        
       // if(empty($dest))  return;
        //info("in outbound worker");
        //info($this->params);
        $outbound_route = OutboundRoute::find($this->id);
              

        $this->send_call($outbound_route,$response);

    }

}    