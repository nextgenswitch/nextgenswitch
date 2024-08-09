<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SwitchController;
use App\Http\Controllers\Api\VirtualAgentController;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\DialerController;
use App\Http\Controllers\CustomFuncsController;
use App\Http\Controllers\DialerCampaignsController;
use App\Models\Dialplan;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::group(['prefix' => 'v1', 'middleware' => 'api.auth'], function () {
    Route::get('/calls', [SwitchController::class, 'api_call_list'])->name('api.calls');
    Route::post('/call', [SwitchController::class, 'api_call_create'])->name('api.call_create');
    Route::put('/call/{call_id}', [SwitchController::class, 'api_call_modify'])->name('api.call_modify');
    Route::get('/call/{call_id}', [SwitchController::class, 'api_call_get'])->name('api.call_get');
    
});



Route::get('/', [SwitchController::class, 'index'])->name('index');
Route::post('/', [SwitchController::class, 'index'])->name('index.post');
Route::get('/func/{function_id}', [CustomFuncsController::class, 'function_execute'])->name('api.function_execute');
Route::post('/func/{function_id}', [CustomFuncsController::class, 'function_execute'])->name('api.function_execute.post');
Route::post('campain/history/{campaign_id}', [SwitchController::class, 'update_campaign_history'])->name('campaign.history.update');
Route::post('/call_queue/worker/{id}/{func_id}', [SwitchController::class, 'queue_worker'])->name('api.call_queue_worker_execute');
Route::post('/function/{func_id}/{dest_id}/', [SwitchController::class, 'func_call'])->name('api.func_call');
Route::get('/set_sip_log', [SwitchController::class, 'set_sip_log'])->name('api.set_sip_log');
Route::post('/gtts', [TestController::class, 'gtts'])->name('api.gtts');
Route::post('/whisper', [TestController::class, 'whisper'])->name('api.whisper');
Route::post( '/webdialer/dialer_response', [DialerController::class, 'dialer_connect_response'] )->name( 'webdialer.response');
Route::post( '/webdialer/dialer_response_callback/{client_id}', [DialerController::class, 'dialer_response_callback'] )->name( 'webdialer.responseCallback');
Route::post( '/webdialer/dialer_status_callback/{client_id}', [DialerController::class, 'dial_status_callback'] )->name( 'webdialer.statusCallback');

// dialer campaign callback route
Route::post( '/dialer_campaign/dialer_response', [DialerCampaignsController::class, 'dialer_connect_response'] )->name( 'dialer_campaign.response');
Route::post( '/dialer_campaign/dialer_response_callback/{client_id}', [DialerCampaignsController::class, 'dialer_response_callback'] )->name( 'dialer_campaign.responseCallback');
Route::post( '/dialer_campaign/dialer_status_callback/{client_id}', [DialerCampaignsController::class, 'dial_status_callback'] )->name( 'dialer_campaign.statusCallback');


Route::group([
    'prefix' => 'switch','middleware' => ['switch.auth']
], function () {
    Route::get('licence', [SwitchController::class, 'licence'])->name('api.licence');
    //Route::post('/call', [SwitchController::class, 'call_create'])->name('api.switch.call_create');
    //Route::put('/call/{call_id}', [SwitchController::class, 'call_modify'])->name('api.switch.call_modify');
    Route::get('/call/{call_id}', [SwitchController::class, 'api_call_get'])->name('api.call_get1');
    Route::post('/call/url_request', [SwitchController::class, 'url_request'])->name('api.url_request');
    Route::post('/call/record_update', [SwitchController::class, 'call_record_update'])->name('api.record_update');
    Route::post('/call/incoming', [SwitchController::class, 'call_in'])->name('api.call_in');
    Route::post('/call/dial', [SwitchController::class, 'dial'])->name('api.call_dial');
    Route::post('/call/transfer', [SwitchController::class, 'call_transfer'])->name('api.call_transfer');
    Route::post('/call/update', [SwitchController::class, 'call_update'])->name('api.call_update');
    Route::post('/sip_user/validate', [SwitchController::class, 'sip_user_validate'])->name('sip_user_validate');
    Route::post('/sip_user/outbound', [SwitchController::class, 'sip_user_outbound'])->name('sip_user_outbound');
    
    Route::get('/function/{func_id}/{dest_id}/', [SwitchController::class, 'func_call'])->name('api.func_call.get');
    Route::post('/config', [SwitchController::class, 'getConfig'])->name('api.config');
    Route::post('/sip_user/channel_notify', [SwitchController::class, 'sip_channel_update'])->name('api.sip_channel_update');
    // wit ai
    
    Route::post('/test', [VirtualAgentController::class, 'test'])->name('api.test');
    Route::post('/speech_to_text', [SwitchController::class, 'speechToText'])->name('speechToText.post');
    Route::post('/text_to_speech', [SwitchController::class, 'textToSpeech'])->name('textToSpeech.post');
    Route::post('/is_ip_blocked', [SwitchController::class, 'isIpBlocked'])->name('api.switch.is_ip_blocked');
    Route::post('/sms', [SwitchController::class, 'sendSms'])->name('api.switch.sms');
    Route::post('/worker/run', [SwitchController::class, 'worker_run'])->name('api.worker.run');

});

Route::group([
    'prefix' => 'test'
], function () {
    Route::post('/intent', [TestController::class, 'getIntent']);
    Route::get('/', [TestController::class, 'index'])->name('test.index');
    Route::post('/', [TestController::class, 'index'])->name('test.index.post');
    Route::get('/enqueue', [TestController::class, 'enqueue'])->name('test.enqueue');
    Route::post('/enqueue', [TestController::class, 'enqueue'])->name('test.enqueue.post');
    Route::get('/queue', [TestController::class, 'queue'])->name('test.queue');
    Route::post('/queue', [TestController::class, 'queue'])->name('test.queue.post');
    Route::get('/virtual-agent', [VirtualAgentController::class, 'index'])->name('api.voice.agent_get');
    Route::post('/virtual-agent', [VirtualAgentController::class, 'index'])->name('api.voice.agent');
    Route::post('/virtual-agent/talk', [VirtualAgentController::class, 'talk'])->name('api.voice.agent.talk');
    Route::post('/virtual-agent/talk', [VirtualAgentController::class, 'LoanPayment'])->name('api.voice.agent.loan');
    Route::get('/virtual-agent/talk', [VirtualAgentController::class, 'LoanPayment'])->name('api.voice.agent.loan.get');
    Route::post('send/sms', [TestController::class, 'sendSMS'])->name('test.send.sms');
    Route::post('/vtiger/notify', [TestController::class, 'notify'])->name('test.vtiger.notify');
});




