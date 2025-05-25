<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Api\VoiceResponse;
use App\Models\SmsProfile;
use App\Sms\Sms;


class TestController extends Controller
{
    public function index(Request $request){
     

  //$output = shell_exec("gtts-cli \"c'est la vie\" --lang fr --output " . storage_path('app/public/testvoice.mp3'));
  //echo "<pre>$output</pre>"; 

  //dd(config('settings.switch.http_listen'));
  //Log::info("Speech to text processing");
 /*  Cache::store('redis')->set('bar', 'baz', 600); // 10 Minutes
  //Cache::store('redis')->set('bar2', 'baz', 600); // 10 Minutes
  $value = Cache::get('bar');
  dd($value);  */
  //QueueCall::create(["call_id"=>'d1f4fc54-4b5e-4786-ad53-bc0918acd51b','queue_name'=>'queue_2' ,'organization_id'=>3]);
   //FunctionCall::reg_channel(8);
   //$queueJobs = new QueueSendCall();
   //$this->dispatch( $queueJobs);
   $response = new VoiceResponse();
  /* $response->say("Please send a voice message after the beep");
  $response->record(['action'=>url()->full()]);
  $response->say("Voice record finished , thanks"); */
  /* if($request->input('digits') != ''){
    $response->say("You have pressed  " );
    $response->say($request->input('digits'));
  }
  $response->say("Please press a value after the beep to test ");
  $gather = $response->gather(['input'=>'dtmf','beep'=>true,'timeout'=>10,'action'=>'http://easypbx.laravel.infosoftbd.com/api']);
  $gather->say("Press now");
  $response->say("You did not press anything"); */
  if($request->input('speech_result') != ''){
    $response->say("You have told  ");
    $response->say($request->input('speech_result'));
  }
  $response->say("Hello !! I am  a virtual assistant . Please tell me how can I help you");
  $gather = $response->gather(['input'=>'speech','speechTimeout'=>0,'action'=>url()->full()]);
  $response->redirect(); 
  /* $response->say("please wait while we are dialing",['loop'=>1]); 
  $dial = $response->dial("1100",["answerOnBridge"=>true,'record'=>'record-from-answer']);
  $response->say("Agent not available , please try later , thanks "); */
  //$dial = $response->dial("",['record'=>'record-from-answer']);
  //$response->say("Your call has been modified , thanks .",['loop'=>3]); 
  //$dial->queue('support',['url'=>'']);
  /*$dial->say("Please wait while someone picking your call.");
  $dial->say("All of our support agents are busy at this momemnt . Please wait ");
  $dial->play("http://easypbx.laravel.infosoftbd.com/storage/sounds/music_on_hold.mp3");
   */
  return $response->xml();


        
        //$response = new VoiceResponse();
       // $response->say("Hello . Welcome to Carhub. I am  your virtual assistant Sandra. Please tell me how can I help you");
       // $gather = $response->gather(['input'=>'speech','timeout'=>100,'action'=>'http://easypbx.laravel.infosoftbd.com/api/switch/virtual-agent']);
        //$response->say("please wait while we are dialing",['loop'=>1]); 
        //$dial = $response->dial("1002",["answerOnBridge"=>true,'record'=>'record-from-answer']);
        // $dial = $response->dial("",['record'=>'record-from-answer']);
        //$dial->queue('support',['url'=>'']);
        /*$dial->say("Please wait while someone picking your call.");
        $dial->say("All of our support agents are busy at this momemnt . Please wait ");
        $dial->play("http://easypbx.laravel.infosoftbd.com/storage/sounds/music_on_hold.mp3");
         */
       // return $response->xml();
       
    }

    public function cloudflare(Request $request){
        if ($request->isMethod('post')){

        }
        return view('cloudflare');
    }

    public function enqueue(Request $request){
        $response = new VoiceResponse();

        if ($request->isMethod('post')) {
            $response->say("Please wait while someone picking your call.");
            $response->say("All of our support agents are busy at this momemnt . Please wait ");
            $response->pause(['length'=>1]);            
            $response->play("http://easypbx.laravel.infosoftbd.com/storage/sounds/music_on_hold.mp3");
            $response->redirect();
        }else{
            $response->say("please wait while we are dialing",['loop'=>1]); 
            
            $response->enqueue("support",['waitUrl'=>url()->full()]);
            $response->say("Thanks for call us , goodbye ");
            //$dial->queue('support',['url'=>url()->full()]);
            //$response->redirect();

        }

        return $response->xml();
    }

    public function queue(Request $request){
        $response = new VoiceResponse();

        if ($request->isMethod('post')) {
            $response->say("Please wait while we are connecting you to the caller");
            $dial = $response->dial('');
            $dial->queue('support',['url'=>url()->full(),'method'=>'get']);
            $response->say("Thanks for providing the support");
        }else{
            $response->say("You are now connecting",['loop'=>1]); 
            
         //   $response->enqueue("support",['waitUrl'=>url()->full()]);
            //$dial->queue('support',['url'=>url()->full()]);
            //$response->redirect();

        }

        return $response->xml();
    }

    // public function getIntent($context, $intent){
    public function getIntent(Request $request){
        $context = $request->input('context');
        $intent = $request->input('intent');
        $data = array();
        
        $url = 'https://api.wit.ai/message?v=20240313&q=' . $context;
        $response = Http::withToken('RYUIQ2ALEVRCCYBDLGEAU3G3KM4KYHCE')->acceptJson()->get($url);

        

        if( count($response['intents']) == 0){
            return false;
        }

        if( count($response['intents']) > 0){
            $data['intent'] = $response['intents'][0]['name'];            
        }

        if(count($response['entities']) > 0){
            $expected_entities = $response['entities'][array_key_first($response['entities'])];

            $entity = $expected_entities[array_key_first($expected_entities)];

            $data['entity'] = [
                'name' => $entity['name'],
                'value' => $entity['value']
            ];
        }


        return $data;

    }

    public function sendSMS(Request $request){
        $data = $request->input();

        $response = [
            'status' => 1,
            'message' => 'Message sent successfully',
            'trxid' => '',
        ];

        // $url = "http://103.86.196.156:5000/send-message";

        // $res = Http::post($url, $data);

        // Log::info( $data );

        // Log::info( $res );

        // $res = json_decode( $res );
        // $response['status'] = $res->status ? 2 : 3;

        /*
        $url  = "https://fastsmsportal.com/smsapi?apiKey=$2y$10$/aBUNCnCwdo2qbem2qP3Weh4WfUarB.lWl7rET3t6o1U23zvA4wde131&senderId=8809612441571&mobileNo=" . $data['to'] . "&message=" . $data['body'];

        $res = Http::get( $url );
        Log::info( $res );

        $res = json_decode( $res );

        if( isset($res->messageid) ){
            $response['trxid'] = $res->messageid;
        }

        if(isset($res->status) && strtolower($res->status) == 'success'){
            $response['status'] = 2;
        }

        elseif(isset($res->status) && strtolower($res->status) == 'failed'){
            $response['status'] = 3;
        }

        */

        $profile = SmsProfile::where('default', 1)->first();
        $res = Sms::send($data['to'], $data['body'], '', $profile);
        if( isset($res->messageid) ){
            $response['trxid'] = $res->messageid;
        }

        if(isset($res->status) && strtolower($res->status) == 'success'){
            $response['status'] = 2;
        }

        elseif(isset($res->status) && strtolower($res->status) == 'failed'){
            $response['status'] = 3;
        }

        return $response;

    }

    public function notify(Request $request){
        Log::info( $request->all() );
        return response()->json(['status' => 'success', 'call_id' => 'tasdkj3u0asdlf']);
    }

    public function gtts(Request $request){
        $text = $request->input('text');
        $lang = $request->input('lang');
        info($request->input());
        info("text is " . $text);
        $lang = strtolower(explode('-',$lang)[0]); 
        $response = Http::get("http://127.0.0.1:5001/gtts", [
            'text' => $text,
            'lang' => $lang,
        ]);
        //return $response->json();
       
        $file =  storage_path('app/public/' . $response->json('file'));
        //die(storage_path('app/public/' . $file));
        if(file_exists($file))
            return response()->download($file, basename($file))->deleteFileAfterSend(true);
        else
            return ["error"=>true];
    }

    public function whisper(Request $request){
        if($request->hasFile('file')){
            $file = $request->file('file');
            $lang = $lang = $request->input('lang');
            $lang = strtolower(explode('-',$lang)[0]); 
            $ret = Http::attach('file', file_get_contents($file), 'file.wav')
            ->post('http://51.79.230.231:5000/whisper?lang=' . $lang, $request->all());
            if(isset($ret['results'][0]['transcript'])){
                info($ret['results'][0]['transcript']);
                return ['text'=>$ret['results'][0]['transcript']];
            }           
        }
        return ['error'=>true];        
       
    }

}