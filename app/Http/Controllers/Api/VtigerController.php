<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\VoiceResponse;
use App\Enums\CallStatusEnum;
use App\Models\CallHistory;
use App\Http\Controllers\Api\FunctionCall;

class VtigerController extends Controller
{
    public function call(Request $request){
    	Log::info('log from call method');

		Log::info($request->all());
		
		if($request->has('to') == false){
			return response()->json(['status' => false, 'message' => 'Contact(to) is required']);
		}
		
		$from = $request->input('from');
		if(empty($from)) $from = "1010";
		
		$response = new VoiceResponse();
		$response->dial($from, ['action' =>route('vtiger.status.callback', ['organization_id' => $request->input('organization_id')])] );
		info($response->xml());
		$payload = ['to' => $request->input('to'), 'organization_id' => $request->input('organization_id'), 'responseXml'=>$response->xml()];
		
		$call = FunctionCall::send_call($payload);
		Log::info('call response log from call method');
		Log:info($call);

		return response()->json(['status' => 'success', 'call_id' => 'asdfasdf']);
		
	}

	public function statusCallback(Request $request){
		$data = $request->all();
		Log::info('log from statusCallback method');
		Log::info($data);

		if($data['status-code'] >= CallStatusEnum::Disconnected->value){
			CallHistory::create([
                'organization_id'=> $data['organization_id'],
                'call_id'=>$data['call_id'],
                'bridge_call_id' => $data['dialer_call_id'],
                'duration' => $data['duration'],
                'record_file' => isset($data['record_file']) ? $data['record_file'] : '',
                'status' => CallStatusEnum::fromKey($data['status-code'])
            ]);
		}
	}
}
