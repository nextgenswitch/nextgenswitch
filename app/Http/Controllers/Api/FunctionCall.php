<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Mail\Mail;
use Carbon\Carbon;
use App\Models\Ivr;
use App\Models\Call;
use App\Models\Func;
use App\Models\Trunk;
use App\Models\Survey;
use App\Models\SipUser;
use App\Models\Extension;
use App\Models\IvrAction;
use App\Models\VoiceFile;
use App\Models\VoiceMail;
use App\Models\VoiceRecord;
use App\Models\CustomFunc;
use App\Models\SmsHistory;
use App\Models\CallHistory;
use App\Models\MailProfile;
use App\Models\SmsProfile;
use App\Models\AiConversation;
use App\Models\Announcement;
use App\Models\InboundRoute;
use App\Models\SurveyResult;
use Illuminate\Http\Request;
use App\Enums\CallStatusEnum;
use App\Models\OutboundRoute;
use App\Models\Ticket;
use App\Models\AiBot;
use App\Models\SipChannel;
use App\Models\AiAssistantCall;
use App\Models\TimeCondition;
use App\Models\CallParking;
use App\Models\Stream;
use App\Models\StreamHistory;
use App\Tts\OpenAi;
use App\Tts\Tts;
use App\Sms\Sms;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Api\Functions\CallHandler;
use App\Http\Controllers\Api\Functions\QueueWorker;
use App\Http\Controllers\Api\Functions\OutboundWorker;
use App\Http\Controllers\Api\Functions\RingGroupWorker;
use App\Http\Controllers\Api\Functions\CallParkingWorker;
use App\Http\Controllers\Api\Functions\ShortCodeHandler;
use App\Models\CallQueueExtension;
use App\Http\Controllers\Api\Functions\AiAssistantHandler;
use App\Models\CallQueue;

class FunctionCall
{
    private $func_id;
    private $params;
    private $response;
    public static function execute($func_id, $dest_id, $response = null, $params = [])
    {
        if (is_numeric($func_id))
            $func = Func::find($func_id);
        else
            $func = Func::where("func", $func_id)->first();

        if ($func) {
            $funcCall = new FunctionCall;
            $funcCall->func_id = $func->id;
            $funcCall->params = $params;
            if ($response)
                $funcCall->response = $response;
            else
                $funcCall->response = new VoiceResponse();

            $xmlelem =   $funcCall->{$func->func}($dest_id);
            return $xmlelem;
        } else
            return new VoiceResponse();
    }

    public static function send_call($data)
    {
        if (auth()->user())
            $data['organization_id'] = auth()->user()->organization_id;

        $request = Request::create('/', 'POST', $data);
        return CallHandler::create($request);
    }



    public static function send_sms($data)
    {
        if (auth()->user())
            $data['organization_id'] = auth()->user()->organization_id;

        if (isset($data['sms_profile'])) $smsProfile = $data['sms_profile'];

        else {
            $smsProfile = SmsProfile::where('organization_id', $data['organization_id'])->where('default', 1)->first();

            if ($smsProfile) {
                $data['sms_profile'] = $smsProfile;
            } else {
                Log::info("SMS profile not found");
                return;
            }
        }

        if (isset($data['from']) == false) {
            $options = json_decode($smsProfile->options, true);

            $data['from'] = isset($options['from']) ? $options['from'] : 'EasyPbx';
        }

        $smsHistory = SmsHistory::create($data);

        $response = Sms::send($data['to'], $data['body'], $data['from'], $data['sms_profile']);
        //Log::info( $response );

        $smsHistory->update([
            'trxid' => $response['trxid'],
            'status' => $response['status']
        ]);

        $response['sms_history_id'] = $smsHistory->id;
        return $response;
    }


    public static function send_mail($data)
    {
        if (auth()->user())
            $data['organization_id'] = auth()->user()->organization_id;

        if (! isset($data['mail_profile_id'])) {
            $mprofile = MailProfile::where('organization_id', $data['organization_id'])->where('default', 1)->first();

            if ($mprofile) {
                $data['mail_profile_id'] = $mprofile->id;
            } else {
                Log::info("Mail profile not found");
                return;
            }
        }

        $template = isset($data['template']) ? $data['template'] : 'default';
        return Mail::send($data['to'], $data['subject'], $data['body'], $data['mail_profile_id'], $template);
    }


    public static function create_worker($data)
    {
        if (auth()->user())
            $data['organization_id'] = auth()->user()->organization_id;
        if (!isset($data['name'])) $data['name'] = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(6 / strlen($x)))), 1, 6);
        try {
            $response = Http::post("http://" . config('settings.switch.http_listen') . config('easypbx.worker_create_path'), $data);
            if ($response->failed()) {
                goto out;
            }
            $return =  $response->json();
            if ($return['status'] != 0) goto out;
            else return $return;
        } catch (Exception $e) {
        }
        out:
        return ['error' => true];
    }

    public static function send_to_websocket($client_id, $data)
    {


        try {
            $response = Http::post("http://" . config('settings.switch.http_listen') . config('easypbx.websocket_send_path'), ['client_id' => $client_id, 'data' => $data]);
            if ($response->failed()) {
                goto out;
            }
            $return =  $response->json();
            if ($return['status'] != 0) goto out;
            else return $return;
        } catch (Exception $e) {
        }
        out:
        return ['error' => true];
    }



    public static function modify_call($call_id, $data)
    {
        $request = Request::create('/', 'POST', $data);
        return CallHandler::modify($call_id, $request);
    }





    public static function unreg_channel($id)
    {
        $sip_channel  = SipUser::find($id);
        //dd($sip_channel->organization);
        if (!$sip_channel) return;
        $data['channel_id'] = $sip_channel['id'];
        $response = Http::post(config('settings.switch.http_listen') . config('easypbx.channel_unregister_path'), $data);
    }

    public static function reg_channel($id)
    {
        $sip_channel  = SipUser::find($id);
        //dd($sip_channel->organization);
        if (!$sip_channel) return;
        if ($sip_channel->peer == false) return;
        //print_r($sip_channel->organization);
        $data['channel_id'] = $sip_channel['id'];
        $data['registrar'] = "sip:" . $sip_channel->username . "@" . $sip_channel->host . ":" . $sip_channel->port;
        $data['from'] =  "sip:" . $sip_channel->username . "@" . $sip_channel->organization->domain;
        $data['password'] = $sip_channel->password;
        $response = Http::post(config('settings.switch.http_listen') . config('easypbx.channel_register_path'), $data);
        //dd($response->json());

    }

    public static function processDestination($dest, $sip_channel, $caller_id)
    {  // this is called when incoming calls come
        if (!$sip_channel) return;
        $is_trunk = false;
        $trunks = Trunk::where("organization_id", $sip_channel->organization_id)->where("sip_user_id", $sip_channel->id)->get();
        if ($trunks->count() > 0) $is_trunk = true;
        $response = new VoiceResponse();
        //Log::debug("is trunk " . $is_trunk);
        if ($is_trunk == false) {  // call comes from an local extension
            info("call come from local extension ?");
            $response = self::getOutboundRoutes($dest, $sip_channel->organization_id, $caller_id);
            if ($response && $response->count() == 0) {
                $response->say("User extension ");
                $response->say(implode(" ", str_split($dest)));
                $response->say("is not exists , please check back later. Thank you");
            }
        } else { // call comes from remote channel
            $iroutes = InboundRoute::where("organization_id", $sip_channel->organization_id)->get();

            if (sizeof($iroutes) > 0) {
                foreach ($iroutes as $iroute) {
                    if (FunctionCall::matchPattern($iroute->did_pattern, $dest)) {
                        if (!empty($iroute->cid_pattern) && !empty($caller_id)) {
                            if (FunctionCall::matchPattern($iroute->cid_pattern, $caller_id) == false) continue;
                        }
                        Log::debug("incoming route found" . $iroute->did_pattern);
                        // need fix here not to use redirect
                        //$response->redirect(route('api.func_call',['func_id'=>$iroute->function_id,'dest_id'=>$iroute->destination_id]));
                        $response = self::execute($iroute->function_id, $iroute->destination_id, $response, ['event_to' => $dest, 'event_from' => $caller_id]);
                    }
                }
            }
        }


        return $response;
    }

    public static function getOutboundChannels($organization_id, $to)
    {

        $dest = $to;
        $oroutes = OutboundRoute::where("organization_id", $organization_id)->where("is_active", true)->get();
        $outbound_channels = [];
        foreach ($oroutes as $oroute) {

            $dest = $to;

            if (FunctionCall::matchPattern($oroute->pattern, $dest)) {
                foreach ($oroute->trunks as $key => $trunk)
                    $outbound_channels[$trunk->sip_user_id] = $dest;
            }
        }
        return $outbound_channels;
    }

    public static function getExtensionRoute($dest, $org_id, $caller_id = null, $response = null)
    {
        if (!$response)
            $response = new VoiceResponse();
        // info("here on getExtensionRoute");
        $extension = Extension::where("organization_id", $org_id)->where("code", $dest)->first();
        // info($extension);
        if ($extension) return self::execute('extension', $extension->id, $response, ['event_to' => $dest, 'event_from' => $caller_id]);
        return false;
    }


    public static function getOutboundRoutes($dest, $org_id, $caller_id = null, $response = null)
    {   // this is used when call from api


        if (!$response)
            $response = new VoiceResponse();
        $newresponse = self::getExtensionRoute($dest, $org_id, $caller_id, $response);
        if ($newresponse == false) {
            if (substr($dest, 0, 1) == '*') {
                return self::execute('short_code', $dest, $response);
            }
            // find outbound routes here

            $routes = [];
            $oroutes = OutboundRoute::where("organization_id", $org_id)->where("is_active", true)->orderBy('priority', 'desc')->get();

            foreach ($oroutes as $oroute) {
                if (self::matchPattern($oroute->pattern, $dest, $caller_id)) {
                    //Log::debug("matched pattern " . $dest);
                    //Log::debug($oroute->pattern);

                    $routes[] = $oroute;
                    //$response->redirect(route('api.func_call',['func_id'=>'outbound_route','dest_id'=>$oroute->id,'dest'=>$dest]));
                    //$response->dial($dest,['channel_id'=>$oroute->trunk->sip_user_id,'answerOnBridge'=>'true','record'=>'record-from-answer']);
                    $response = self::execute('outbound_route', $oroute->id, $response, ['event_to' => $dest, 'event_from' => $caller_id]);
                    break;
                }
            }

            // $response->dial($dest,['answerOnBridge'=>'true','record'=>'record-from-answer']);


        } else
            $response = $newresponse;
        return $response;
    }

    // public static function matchPattern($pattern, &$dest, $caller_id = null)
    // {
    //     //Log::debug($pattern);

    //     if (is_array($pattern)) {
    //         foreach ($pattern as $patt) {
    //             if ($caller_id && !empty($patt->cid_pattern)) {
    //                 if (self::matchPattern($patt->cid_pattern, $caller_id) == false) return false;
    //             }
    //             if (self::matchPattern($patt->pattern, $dest)) {
    //                 if (!empty($patt->prefix_remove)) {
    //                     if (strcmp($patt->prefix_remove, substr($dest, 0, strlen($patt->prefix_remove))) == 0) $dest = substr($dest, strlen($patt->prefix_remove));
    //                 }
    //                 if (!empty($patt->prefix_append)) {
    //                     $dest = $patt->prefix_append . $dest;
    //                 }
    //                 return true;
    //             }
    //         }
    //     } else {
    //         if (substr($pattern, 0, 1) == '/') {
    //             if (@preg_match($pattern, $dest, $matches)) return true;
    //         } elseif ($pattern == '*') return true;
    //         elseif (strcmp($pattern, substr($dest, 0, strlen($pattern))) == 0) return true;
    //     }
    //     //Log::debug("matching pattern ". $pattern . " " .$dest . " " . strcmp($pattern,substr($dest,0,strlen($pattern))));
    //     return false;
    // }

    public static function matchPattern($pattern, &$dest, $caller_id = null)
    {
        // info("pattern " );
        // Log::debug($pattern);

        // info("dest " . $dest);
        // info("caller_id " . $caller_id);


        if (is_array($pattern)) {
            foreach ($pattern as $patt) {

                if ($caller_id && !empty($patt->cid_pattern)) {
                    if (self::matchPattern($patt->cid_pattern, $caller_id) == false) return false;
                }
                if (self::matchPattern($patt->pattern, $dest)) {
                    if (!empty($patt->prefix_remove)) {
                        if (strcmp($patt->prefix_remove, substr($dest, 0, strlen($patt->prefix_remove))) == 0) $dest = substr($dest, strlen($patt->prefix_remove));
                    }
                    if (!empty($patt->prefix_append)) {
                        $dest = $patt->prefix_append . $dest;
                    }
                    return true;
                }
            }
        } else {
            // info("Else pattern " );
            // Log::debug($pattern);
            // Log::debug($dest);

            if (substr($pattern, 0, 1) == '/') {
                if (@preg_match($pattern, $dest, $matches)) return true;
            } elseif ($pattern == '*') return true;
            elseif (strcmp($pattern, substr($dest, 0, strlen($pattern))) == 0) return true;
        }
        //Log::debug("matching pattern ". $pattern . " " .$dest . " " . strcmp($pattern,substr($dest,0,strlen($pattern))));
        return false;
    }

    public static function voice_file_play($response, $voice_file)
    {
        if ($voice_file && $voice_file->voice_type == 1) {
            $response->say($voice_file->tts_text, ['tts_profile_id' => $voice_file->tts_profile_id]);
        } elseif ($voice_file) {
            $response->play(storage_path('app/public/uploads/' . $voice_file->organization_id . "/" . $voice_file->file_name), ['localfile' => "true"]);
        }

        return $response;
    }

    function ivr($id)
    {
        $ivr = Ivr::find($id);
        if (! $ivr) return $this->response;

        $retry = (int) request()->query('retry', 0);
        //Log::info("ivr retry is " . $retry);
        $data = request()->input();
        if (request()->query('gather') == true) {

            if (isset($data['digits']) && $data['digits'] != NULL) {
                $ivr_action = IvrAction::where("ivr_id", $id)->where("digit", $data['digits'])->first();
                // Log::info("ivr action here");
                // Log::info($ivr_action);
                if (!$ivr_action) {
                    self::voice_file_play($this->response, $ivr->invalidVoice);
                    $this->response->redirect(route('api.func_call', ['func_id' => $this->func_id, 'dest_id' => $ivr->id, 'retry' => ($retry + 1)]));
                    //$this->response = self::execute($this->func_id,$ivr->id,$this->response,['retry'=>1]);
                } else {
                    $this->response->redirect(route('api.func_call', ['func_id' => $ivr_action->function_id, 'dest_id' => $ivr_action->destination_id]));
                }
            } elseif (isset($data['speech_result']) && !empty($data['speech_result'])) {

                if ($ivr->intent_analyzer) {
                    $prompt = "Please classify the following text: " . $data['speech_result'];
                    $instruction = $ivr->intent_analyzer;

                    $intent = Tts::llm($prompt, $instruction, $ivr->organization_id);

                    $ivr_action = IvrAction::where("ivr_id", $id)->where("voice", $intent)->first();
                } else {
                    $ivr_action = IvrAction::where("ivr_id", $id)->where("voice", 'LIKE', "%" . $data['speech_result'] . "%")->first();
                }

                if (!$ivr_action) {
                    self::voice_file_play($this->response, $ivr->invalidVoice);
                    $this->response->redirect(route('api.func_call', ['func_id' => $this->func_id, 'dest_id' => $ivr->id, 'retry' => ($retry + 1)]));
                    //$this->response = self::execute($this->func_id,$ivr->id,$this->response,['retry'=>1]);
                } else {
                    $this->response->redirect(route('api.func_call', ['func_id' => $ivr_action->function_id, 'dest_id' => $ivr_action->destination_id]));
                }
            } else {
                self::voice_file_play($this->response, $ivr->timeoutVoice);
                $this->response->redirect(route('api.func_call', ['func_id' => $this->func_id, 'dest_id' => $ivr->id, 'retry' => ($retry + 1)]));
            }
        } else {

            if ($retry > $ivr->max_retry) {
                // Log::info("redirecting ivr after max retry " . $retry . "  " . $ivr->max_retry);
                $this->response->pause(1);
                $this->response->redirect(route('api.func_call', ['func_id' => $ivr->function_id, 'dest_id' => $ivr->destination_id]));
                return $this->response;
            }

            if ($retry == 0) self::voice_file_play($this->response, $ivr->welcomeVoice);
            $this->response->pause(1);
            $max_digit = ($ivr->max_digit <= 0) ? 1 : $ivr->max_digit;
            $input = 'dtmf';
            if ($ivr->mode == 1)  $input = 'speech';
            elseif ($ivr->mode == 2)  $input = 'dtmf speech';
            Log::info("input is " . $input);
            $timeout = $ivr->timeout;
            if ($timeout < 5) $timeout = 5;
            $gather = $this->response->gather(['input' => $input, 'numDigits' => $max_digit, 'timeout' => $timeout, 'action' => route('api.func_call', ['func_id' => $this->func_id, 'dest_id' => $ivr->id, 'gather' => true, 'retry' => $retry])]);
            self::voice_file_play($gather, $ivr->instructionVoice);
            self::voice_file_play($this->response, $ivr->timeoutVoice);
            $this->response->redirect(route('api.func_call', ['func_id' => $this->func_id, 'dest_id' => $ivr->id, 'retry' => ($retry + 1)]));
        }

        return $this->response;
    }

    function call_survey($id)
    {
        $survey = Survey::find($id);
        $try = request()->query('retry', -1);
        $data = request()->input();

        info("on survey " . $id);

        if (request()->query('gather') == true) {

            $call = Call::find($data['call_id']);
            if (!empty($data['digits']) && !empty($survey->keys)) {
                $keys = json_decode($survey->keys, true);
                info($keys);
                foreach ($keys as $key) {
                    if ($key['key'] == $data['digits']) {
                        $this->response->redirect(route('api.func_call', ['func_id' => $key['function_id'], 'dest_id' => $key['destination_id']]));
                        $create = [
                            'organization_id' => $survey->organization_id,
                            'survey_id' => $survey->id,
                            'call_id' => $call->id,
                            'caller_id' => $call->destination,
                            'pressed_key' => $data['digits']
                        ];
                        SurveyResult::create($create);

                        $body = 'Received feedback ' . $data['digits'] . '(' . $key['text'] . ')' . ' from caller id #' . $call->destination . ' and survey - ' . $survey->name;
                        info($body);

                        if (empty($survey->email) == false) {
                            info('Sending email from survey');
                            self::send_mail([
                                'organization_id' => $survey->organization_id,
                                'to' => $survey->email,
                                'subject' => 'Survey Feedback',
                                'body' => $body,
                                'template' => 'plain'
                            ]);
                        }

                        if (empty($survey->phone) == false) {
                            info('sending sms from survey');
                            self::send_sms([
                                'organization_id' => $survey->organization_id,
                                'to' => $survey->phone,
                                'body' => $body,
                            ]);
                        }
                        return $this->response;
                    }
                }
            } elseif (isset($data['speech_result']) && !empty($data['speech_result'])) {
                info($data['speech_result']);
                // if ($survey->intent_analyzer) {
                //     $prompt = "Please classify the following text: " . $data['speech_result'];
                //     $instruction = $survey->intent_analyzer;

                //     $intent = Tts::llm($prompt, $instruction, $survey->organization_id);

                //     $ivr_action = IvrAction::where("ivr_id", $id)->where("voice", $intent)->first();
                // } else {
                //     $ivr_action = IvrAction::where("ivr_id", $id)->where("voice", 'LIKE', "%" . $data['speech_result'] . "%")->first();
                // }

                // if (!$ivr_action) {
                //     self::voice_file_play($this->response, $survey->invalidVoice);
                //     $this->response->redirect(route('api.func_call', ['func_id' => $this->func_id, 'dest_id' => $ivr->id, 'retry' => ($retry + 1)]));
                //     //$this->response = self::execute($this->func_id,$ivr->id,$this->response,['retry'=>1]);
                // } else {
                //     $this->response->redirect(route('api.func_call', ['func_id' => $ivr_action->function_id, 'dest_id' => $ivr_action->destination_id]));
                // }
            } elseif (isset($data['record_file'])) {
                $create = [
                    'organization_id' => $survey->organization_id,
                    'survey_id' => $survey->id,
                    'call_id' => $call->id,
                    'caller_id' => $call->destination,
                    'record_file' => $data['record_file']
                ];
                SurveyResult::create($create);
                $this->response->redirect(route('api.func_call', ['func_id' => $survey->function_id, 'dest_id' => $survey->destination_id]));
                $this->response->hangup();
                return $this->response;
            }
        }

        $try = $try + 1;
        $max_try = $survey->max_retry;
        if ($max_try == 0) $max_try = 1;
        info($try . "  " . $max_try);
        if ($try > $max_try) {
            info("survey on end");
            $this->response->redirect(route('api.func_call', ['func_id' => $survey->function_id, 'dest_id' => $survey->destination_id]));
            return $this->response;
        }



        if ($survey->type == 1) {
            self::voice_file_play($this->response, $survey->voice);
            $this->response->record(['beep' => true, 'transcribe' => false, 'action' => route('api.func_call', ['func_id' => $this->func_id, 'dest_id' => $id, 'record' => true, 'gather' => $try])]);
        } else {
            $max_digit = 1;



            $input = 'dtmf';
            if ($survey->type == 2)  $input = 'speech';
            elseif ($survey->type == 3)  $input = 'dtmf speech';
            Log::info("input is " . $input);
            $timeout = $survey->timeout;
            if (!$timeout) $timeout = 10;

            $options = [
                'input' => $input,
                'numDigits' => $max_digit,
                'speechTimeout' => 5,
                'timeout' => $timeout,
                'transcript' => false,
                'action' => route('api.func_call', ['func_id' => $this->func_id, 'dest_id' => $survey->id, 'gather' => true, 'retry' => $try]),
            ];

            $gather = $this->response->gather($options);
            self::voice_file_play($gather, $survey->voice);
            $this->response->redirect(route('api.func_call', ['func_id' => $this->func_id, 'dest_id' => $survey->id, 'retry' => $try]));
            $this->response->hangup();
        }



        // info($this->response->xml());
        return $this->response;
    }


    function terminate_call($id)
    {
        $this->response->hangup();
        return $this->response;
    }

    function call_queue($id)
    {
        info("on call queue");
        $queue_worker = new QueueWorker($id, $this->func_id);
        return $queue_worker->process($this->response);
    }

    function queue_join($id)
    {
        //$data = request()->input();
        info("on call queue_join");
        if (request()->query('gather') == '1') {
            $call = Call::find(request()->input('call_id'));
            $extension = Extension::where("organization_id", $call->organization_id)->where('destination_id', $call->sip_user_id)->where('extension_type', 1)->first();
            if (request()->input('digits') == '1') {
                if ($extension) CallQueueExtension::where('call_queue_id', $id)->where("extension_id", $extension->id)->update(['dynamic_queue' => 1]);
                return $this->response;
            } else if (request()->input('digits') == '2') {
                if ($extension) CallQueueExtension::where('call_queue_id', $id)->where("extension_id", $extension->id)->update(['dynamic_queue' => 0]);
                return $this->response;
            } else if (request()->input('digits') == '0') {
                $this->response->redirect(route('api.func_call', ['func_id' => 'call_queue', 'dest_id' => $id, 'queue' => true]));
            } else
                return $this->response;
        }
        $options = [
            'action' => route('api.func_call', ['func_id' => $this->func_id, 'dest_id' => $id, 'gather' => true]),
            'numDigits' => 1,
            'timeout' => 300,
        ];

        $gather = $this->response->gather($options);
        $gather->play(storage_path('app/public/sounds/queue_menu_en.mp3'), ['localfile' => "true"]);
        info($this->response->xml());
        return $this->response;
    }



    function ring_group($id)
    {
        $ring_worker = new RingGroupWorker($id, $this->func_id);
        return $ring_worker->process($this->response);
    }

    // function getCode($input)
    // {
    //     if (preg_match('/^\*[^*]+/', $input, $matches)) {
    //         return $matches[0];
    //     }
    //     return $input;
    // }

    // function extractNumber($input)
    // {
    //     if (preg_match('/^\*\d+\*([^\#%]+)(?:#|%23)?$/', $input, $matches)) {
    //         return $matches[1];
    //     }
    //     return null;
    // }

    public function getResponse()
    {
        return $this->response;
    }


    public function getFuncId()
    {
        return $this->func_id;
    }


    function getRequestData()
    {

        if (request()->isJson()) {
            return request()->json()->all();
        }

        $raw = request()->getContent();
        $decoded = json_decode($raw, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        return request()->all();
    }



    function short_code($code)
    {
        return (new ShortCodeHandler($this))->handle($code);
    }


    function call_parking($id)
    {
        //  info("on call parking");
        $worker = new CallParkingWorker($id, $this->params);
        return $worker->process($this->response);
    }


    function handleForward($extension)
    {

        if (empty($extension->forwarding_number)) return;
        else if ($extension->forwarding == 1) return; // always disable
        else if ($extension->forwarding == 0) $this->params['forward'] = $extension->forwarding_number; // always enable

        else if ($extension->forwarding == 2) { // busy
            info("Call forwarding when busy");
            if (Call::where('sip_user_id', $extension->destination_id)->where('status', '<=', CallStatusEnum::Established)->exists()) {
                $this->params['forward'] = $extension->forwarding_number;
                info("Extension is busy now");
            }
        } else if ($extension->forwarding == 4) {
            info("Call forwarding when unavailable");
            if (!SipChannel::where('sip_user_id', $extension->destination_id)->exists()) {
                $this->params['forward'] = $extension->forwarding_number;
                info("Extension does not exists in sip channel");
            }
        }
    }


    function extension($id)
    {
        $extension = Extension::find($id);

        if (!$extension) return $this->response;
        if ($extension->status != 1) return $this->response;
        $this->func_id = $extension->function_id;
        // if( $extension->forwarding != 1  && !empty($extension->forwarding_number) )
        //     $this->params['forward'] = $extension->forwarding_number;
        $this->handleForward($extension);

        //Log::debug($extension);
        $response =  self::execute($extension->function_id, $extension->destination_id, $this->response, $this->params);
        return $response;
    }

    function sip_call($id)
    {
        // info("on sip call");
        $sip_user = SipUser::find($id);
        if (!$sip_user) return $this->response;
        if (request()->input('dial_status') == "0") {
            Log::debug("failed dial record log here");
            Log::debug(request()->input());
            $data = request()->input();
            CallHistory::create(
                ['organization_id' => $sip_user->organization_id, 'call_id' => $data['call_id'], 'bridge_call_id' => isset($data['bridge_call_id']) ? $data['bridge_call_id'] : "", 'status' => CallStatusEnum::Failed]
            );

            $this->response->say("Extension is not available right now");
            // send  to forward number

            if (request()->input('forward') != '')
                $this->response = self::getOutboundRoutes(request()->input('forward'), $sip_user->organization_id, (isset($this->params['event_from'])) ? $this->params['event_from'] : null, $this->response);
            //$this->response->dial(request()->input('forward'));
        } elseif (request()->input('dial_status') == "1") {
            // Log::debug("success dial record log here");
            //Log::debug(request()->input());
            $data = request()->input();
            CallHistory::create(
                ['organization_id' => $sip_user->organization_id, 'call_id' => $data['call_id'], 'bridge_call_id' => $data['bridge_call_id'], 'duration' => $data['duration'], 'status' => CallStatusEnum::Disconnected, 'record_file' => isset($data['record_file']) ? $data['record_file'] : ""]
            );
            $this->response->hangup();
        } else {
            // info("Call options to dial sip user");
            $opt = [
                'channel_id' => $id,
                'answerOnBridge' => 'true',
                'action' => route('api.func_call', array_merge(['func_id' => $this->func_id, 'dest_id' => $id], $this->params))
            ];
            if ($sip_user->record)
                $opt['record'] = 'record-from-answer';
            //Log::debug(" call options");
            //Log::debug($opt);
            //Log::debug($this->params);
            $this->response->dial($sip_user->username, $opt);
        }
        //info($this->response->xml());
        return $this->response;
    }



    function time_condition($id)
    {
        $time_condition = TimeCondition::find($id);
        //Log::debug("time condition here");
        //Log::debug($time_condition);
        $matched = false;
        $response = $this->response;
        $time_group = $time_condition->timeGroup;
        if ($time_group) {
            $convertedDateTime = now()->tz($time_group->timezone);
            $currentWeekDay = $convertedDateTime->format('D');
            $currentMonth = $convertedDateTime->format('M');
            $currentday = $convertedDateTime->format('d');
            //Log::debug("current date times $currentWeekDay $currentMonth $currentday $convertedDateTime");
            $schedules = json_decode($time_group->schedules);

            foreach ($schedules as $schedule) {
                //Log::debug(json_encode($schedule));
                if (empty($schedule->start_time))
                    $schedule->start_time = "00:00:00";
                if (empty($schedule->end_time))
                    $schedule->end_time = "23:59:59";
                $start = Carbon::createFromTimeString($schedule->start_time);
                $end = Carbon::createFromTimeString($schedule->end_time);
                if ($start > $end)  $end = Carbon::createFromTimeString($schedule->end_time)->addDay();
                //Log::debug("current start end $start $end ");
                $in_time =  $convertedDateTime->between($start, $end);
                $in_wdays = true;
                $in_months = true;
                $in_days = true;
                if (!empty($schedule->week_days)) $in_wdays = in_array(strtolower($currentWeekDay), explode(',', $schedule->week_days)) ? true : false;
                if (!empty($schedule->months)) $in_months = in_array(strtolower($currentMonth), explode(',', $schedule->months)) ? true : false;
                if (!empty($schedule->days)) $in_days = in_array(strtolower($currentday), explode(',', $schedule->days)) ? true : false;

                if ($in_time && $in_wdays && $in_months && $in_days) {
                    $matched = true;
                    break;
                }
            }
        }
        if ($matched) {
            $response =  self::execute($time_condition->matched_function_id, $time_condition->matched_destination_id, $this->response);
        } else {
            $response =  self::execute($time_condition->function_id, $time_condition->destination_id, $this->response);
        }
        return $response;
    }

    function sendNotification($organization_id,$role = NULL ,$msg)
    {
        $admins = User::where('organization_id', $organization_id)->get();
        foreach ($admins as $admin) {
            $admin->notify(new PushNotification([
                'type' => 0,
                'code' => 1,
                'msg' => $msg
            ]));
        }
    }

    function voice_record($id)
    {
        //$response = new VoiceResponse();
        $recordVoiceProfile = VoiceRecord::find($id);

        if (!$recordVoiceProfile) return $this->response;
        $data = request()->all();

        if (isset($data['record_file'])) {
            // save voicemail here
            info($data);

            $data = [
                'organization_id' => $recordVoiceProfile->organization_id,
                'voice_record_id' => $id,
                'voice_path' => $data['record_file'],
                'caller_id' => $data['event_from'],
                'call_id' => $data['call_id'],
                'transcript' => isset($data['speech_result']) ? $data['speech_result'] : __('Please check record file for more information.')
            ];

            //if (isset($data['speech_result'])) $data['transcript'] = $data['speech_result'];
            VoiceMail::create($data);

            $body = 'Received voice record from ' . $data['caller_id'] . ' and transcript - ' . $data['transcript'];


            // send email
            if ($recordVoiceProfile->email && $recordVoiceProfile->is_transcript) {
                self::send_mail([
                    'organization_id' => $recordVoiceProfile->organization_id,
                    'to' => $recordVoiceProfile->email,
                    'subject' => 'Received voice record from ' . $recordVoiceProfile->name,
                    'body' => $body,
                    'template' => 'plain'
                ]);
            }

            // send sms
            if ($recordVoiceProfile->email && $recordVoiceProfile->is_transcript) {
                self::send_sms([
                    'organization_id' => $recordVoiceProfile->organization_id,
                    'from' => $recordVoiceProfile->name,
                    'to' => $recordVoiceProfile->phone,
                    'body' => $body
                ]);
            }

            // create ticket

            if ($recordVoiceProfile->is_create_ticket && $data['voice_path']) {
                Ticket::create([
                    'organization_id' => $recordVoiceProfile->organization_id,
                    'name' => $data['caller_id'],
                    'phone' => $data['caller_id'],
                    'subject' => __('Support ticket from ') . $data['caller_id'],
                    'description' => $data['transcript'],
                    'record' => $data['voice_path']
                ]);
            }


            $this->response->say("Your voice has been recorded");
        } else {

            // $this->response->say("Please leave a message after the beep.");
            self::voice_file_play($this->response, $recordVoiceProfile->voice);

            $playload = [
                'beep' => $recordVoiceProfile->play_beep ? true : false,
                'transcribe' => $recordVoiceProfile->is_transcript ? true : false,
                'action' => route('api.func_call', ['func_id' => $this->func_id, 'dest_id' => $id])
            ];

            $this->response->record($playload);
        }
        // info($this->response->xml());
        return $this->response;
    }

    function custom_function($id)
    {
        $custom_func = CustomFunc::find($id);
        //info("in custom function");
        //info($this->params);

        $xmlelem = false;
        if ($custom_func) {
            //  dd($custom_func);
            if ($custom_func->func_lang == 0)
                $xmlelem = VoiceResponse::getElementFromXml(null, $custom_func->func_body);
            else {
                $xmlelem = VoiceResponse::getElementFromXml(null, route('api.function_execute', array_merge($this->params, ['function_id' => $id])), $this->params);
            }
        }

        if ($xmlelem == false) return;
        //Log::debug($xmlelem->asXML());
        if (!isset($xmlelem['url'])) $xmlelem->addAttribute("url", route('api.func_call', ['func_id' => $this->func_id, 'dest_id' => $id]));
        foreach ($xmlelem as $verb)
            $this->response->appendXML($verb);
        //Log::debug($this->response->asXML());
        return   $this->response;
    }

    function play_voice($id)
    {
        $voice_file = VoiceFile::find($id);
        $response = new VoiceResponse();
        $this->voice_file_play($response, $voice_file);
        return $response;
    }

    function announcement($id)
    {
        $announcement =  Announcement::find($id);
        //$response = new VoiceResponse();
        $this->voice_file_play($this->response, $announcement->voice);
        //$this->response->redirect(route('api.func_call',['func_id'=>$announcement->function_id,'dest_id'=>$announcement->destination_id]));
        $this->response = self::execute($announcement->function_id, $announcement->destination_id, $this->response, $this->params);
        return $this->response;
    }

    function ai_assistant($id)
    {
        $aiAsisHandler = new AiAssistantHandler($this->func_id, $this->response);
        return $aiAsisHandler->handle($id);
    }

    function stream($id)
    {
        info('Calling Stream function');
        $data = request()->all();
        info("Stream request data :", $data);

        $stream = Stream::find($id);
        info("Stream data :", [$stream]);

        if (isset($data['event'])) {
            if ($data['event'] === 'stopped') {
                StreamHistory::create([
                    'organization_id' => $stream->organization_id,
                    'stream_id'       => $id,
                    'caller_id'       => $data['event_from'],
                    'call_id'         => $data['call_id'],
                    'duration'        => $data['duration'],
                    'record_file'     => $data['rec_path'] ?? null,
                ]);
            }
            return;
        }

        $prompt             = $stream->prompt ?? null;
        $greetings          = $stream->greetings ?? null;
        $forwarding_number  = $stream->forwarding_number ?? null;
        $extra_parameters   = $stream->extra_parameters ? $this->parseKeyValueString($stream->extra_parameters) : [];

        info("Prompt:", [$prompt]);
        info("Greetings:", [$greetings]);
        info("Forwarding number:", [$forwarding_number]);
        info("Extra parameters:", $extra_parameters);

        $connect  = $this->response->connect();
        $aiStream = $connect->stream([
            'name'             => $stream->name,
            'record'           => $stream->record ? true : false,
            'max_call_duration' => $stream->max_call_duration,
            'url'              => $stream->ws_url,
            'statusCallback'   => route('api.func_call', [
                'func_id' => $this->func_id,
                'dest_id' => $id,
            ]),
        ]);

        $this->response->redirect(route('api.func_call', [
            'func_id' => $stream->function_id,
            'dest_id' => $stream->destination_id,
        ]));

        if ($prompt)            $aiStream->parameter(["name" => "prompt", "value" => trim($prompt)]);
        if ($greetings)         $aiStream->parameter(["name" => "greetings", "value" => trim($greetings)]);
        if ($forwarding_number) $aiStream->parameter(["name" => "forwarding_number", "value" => trim($forwarding_number)]);

        foreach ($extra_parameters as $key => $value) {
            $aiStream->parameter(["name" => $key, "value" => trim($value)]);
        }

        return $this->response;
    }


    private function parseKeyValueString(string $input): array
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($input));
        $result = [];

        foreach ($lines as $line) {
            if (strpos($line, '=') !== false) {
                [$key, $value] = explode('=', $line, 2);
                $result[trim($key)] = trim($value);
            }
        }

        return $result;
    }



    function dial_outbound($outbound_route)
    {
        //if(!isset($this->params['dest'])) return;
        $outbound_worker = new OutboundWorker($outbound_route->id, $this->params);
        // Log::debug($this->params);
        if (isset($this->params['queue']))
            $outbound_worker->process_calls($this->response);
        else
            $outbound_worker->process($this->response);




        /* $dest = $this->params['dest'];
        foreach($outbound_route->trunks as $trunk)
            $this->response->dial($dest,['channel_id'=>$trunk->sip_user_id,'answerOnBridge'=>'true','record'=>'record-from-answer',
        ]); */
    }


    function outbound_route($id)
    {

        $outbound_route = OutboundRoute::find($id);
        $verify = (request()->input('verify') == 1) ? true : false;

        if (request()->input('digits') != "") {
            if (request()->input('digits') == "1234") {
                $verify = true;
            } else {
                $this->response->say("You have entered invalid pin");
            }
        }

        if ($outbound_route->pin_list_id > 0 && $verify == false) {
            $this->response->say("Please enter the pin followed by the hash key");
            $this->response->gather(['action' => route('api.func_call', ['func_id' => $this->func_id, 'dest_id' => $id])]);
            $this->response->say("You didn't enter  pin.");
            $this->response->redirect();
        } else
            $this->dial_outbound($outbound_route);
        return $this->response;
    }

    /*   public static function genXmlElelment($tag,$val,$attrs = []){
        $xml = new \SimpleXMLElement("<$tag>$val</$tag>");
        foreach($attrs as $k => $v)
            $xml->addAttribute($k,$v);

        return $xml;
    } */

    public static function sip_code_to_msg($resp_code)
    {

        switch ($resp_code) {
            case 100:
                return "Trying";
                break;
            case 180:
                return "Ringing";
                break;
            case 183:
                return "Session in Progress";
                break;
            case 200:
                return "Ok";
                break;
            case 400:
                return "Bad Request";
                break;

            case 401:
                return "Unauthorized";
                break;
            case 402:
                return "Payment Required";
                break;
            case 403:
                return "Forbidden";
                break;
            case 404:
                return "Not Found";
                break;
            case 405:
                return "Method Not Allowed";
                break;
            case 406:
                return "Not Acceptable";
                break;
            case 407:
                return "Proxy Authentication Required";
                break;
            case 408:
                return "Request Timeout";
                break;
            case 409:
                return "Conflict";
                break;
            case 410:
                return     "Gone";
                break;
            case 411:
                return "Length Required";
                break;
            case 412:
                return "Conditional Request Failed";
                break;

            case 413:
                return "Request Entity too Large";
                break;

            case 414:
                return "Request-URI Too Long";
                break;
            case 415:
                return "Unsupported Media Type";
                break;
            case 416:
                return "Unsupported URI Scheme";
                break;
            case 417:
                return "Unknown Resource-Priority";
                break;
            case 420:
                return "Bad Extension";
                break;
            case 421:
                return "Extension Required";
                break;
            case 422:
                return "Session Interval Too Small";
                break;
            case 423:
                return "Interval Too Brief";
                break;
            case 424:
                return "Bad Location Information";
                break;
            case 428:
                return "428 - Use Identity Headed";
                break;
            case 429:
                return "Provide Referrer Identity";
                break;
            case 430:
                return "430 - Flow Failed";
                break;
            case 433:
                return "Anonymity Disallowed";
                break;
            case 436:
                return "Bad Identity-Info";
                break;
            case 437:
                return    "Unsupported Certificate";
                break;
            case 438:
                return "Invalid Identity Headed";
                break;
            case 439:
                return "First Hop Lacks Outbound Support";
                break;
            case 470:
                return "Consent Needed";
                break;
            case 480:
                return "Temporary Unavailable";
                break;
            case 481:
                return "Call/Transaction Does Not Exist";
                break;
            case 482:
                return    "Loop Detected";
                break;
            case 483:
                return    "Too Many Hops";
                break;
            case 484:
                return "Address Incomplete";
                break;
            case 485:
                return "Ambiguous";
                break;
            case 486:
                return "Busy Here";
                break;
            case 487:
                return "Request Terminated";
                break;
            case 488:
                return "Not Acceptable Here";
                break;
            case 489:
                return "Bad Event";
                break;
            case 491:
                return "Request Pending";
                break;
            case 493:
                return "Undecipherable";
                break;
            case 494:
                return "Security Agreement Required";
                break;
            case 500:
                return "Server Internal Error";
                break;
            case 501:
                return "Not Implemented";
                break;
            case 502:
                return "Bad Gateway";
                break;
            case 503:
                return "Service Unavailable";
                break;
            case 504:
                return "Server Time-out";
                break;
            case 505:
                return "Version Not Supported";
                break;
            case 513:
                return "Message Too Large";
                break;
            case 580:
                return "Precondition Failure";
                break;
            case 600:
                return "Busy Everywhere";
                break;
            case 603:
                return "Decline";
                break;
            case 604:
                return "Does Not Exist Anywhere";
                break;
            case 606:
                return "Not Acceptable";
                break;
            case 607:
                return "Unwanted";
                break;
            default:
                return "Unknown";
                break;
        }
    }
}
