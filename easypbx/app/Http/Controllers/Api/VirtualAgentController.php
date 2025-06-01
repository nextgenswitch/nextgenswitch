<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\WitAiTrait;
use App\Http\Controllers\Api\VoiceResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Tts\OpenAi;

class VirtualAgentController extends Controller
{
    use WitAiTrait;

    
public function index(Request $request){

      $voice_response = new VoiceResponse();

 
     $voice_response->say("Hello . Welcome to Carhub. I am  your virtual assistant Sandra. Please tell me how can I help you");
    
     $voice_response->gather(['input'=>'speech','timeout'=>100,'action'=>route('api.voice.agent.talk')]);

    
     return  $voice_response->xml();
 
 }


 public function talk(Request $request){
    $voice_response = new VoiceResponse();
   
      if($request->input('speech_result') != ''){


        $response = $this->getIntent($request->input('speech_result'));



        if(count($response['intents']) == 0){
           $voice_response->say( 'You told ' . $request->input('speech_result'));
         
           $voice_response->say('I don\'t understand your voice properly. Can you repeat please' );
         

        }

        else{

            $intent_name = $response['intents'][0]['name'];
            $value = '';

            if( count($response['entities']) ){
                $value = reset($response['entities'])[0]['body'];
            }


            if( $intent_name == 'rent_car'){
               $voice_response->say( 'What date do you want to book?');
             
            }

            else if($intent_name == 'booking_date'){
               $voice_response->say('Tell me your source and destination?');
            
            }

            else if($intent_name == 'route'){
               $voice_response->say( 'How many seats in a car do you require?');
                
            }

            else if($intent_name == 'no_of_seat'){
               $voice_response->say('Your order has been confirmed. Thank You');
               
            }

            else{
               $voice_response->say( 'I don\'t hear your voice properly, please tell again');
               
            }

        }


      }else{
        $voice_response->say("We don't hear you, Please tell again ");
      }

      $voice_response->gather(['input'=>'speech','timeout'=>100,'action'=>route('api.voice.agent.talk')]);
      return  $voice_response->xml();

 }

    public function index1(Request $request){

        $validator = Validator::make($request->all(), ['question' => 'required']);

        if ($validator->fails()) {
          return response()->json([
            'status' => false,
            'message' => 'validation fails',
            'data' => $validator->messages()

          ]);
        }


        $response = $this->getIntent($request->question);

        // return count($response['intents']);

        if(count($response['intents']) == 0){

            return response()->json([
                'status' => false,
                'message' => 'There are no intent match, please try again',
                'data' => []
            ]);
        }


        

        $intent_name = $response['intents'][0]['name'];
        $value = '';

        if( count($response['entities']) ){
            $value = reset($response['entities'])[0]['body'];
        }

        switch(strtolower($intent_name)){
            case 'rent_car':
                return response()->json([
                    'status' => true,
                    'message' => 'What date do you want to book?',
                    'data' => [
                        'question' => $response['text'],
                        'intent' => $intent_name,
                        'value' => $value
                    ]
                ]);
                break;

            case 'booking_date':
                return response()->json([
                    'status' => true,
                    'message' => 'Tell me your source and destination?',
                    'data' => [
                        'question' => $response['text'],
                        'intent' => $intent_name,
                        'value' => $value
                    ]
                ]);
                break;

            case 'route':
                return response()->json([
                    'status' => true,
                    'message' => 'How many seats in a car do you require?',
                    'data' => [
                        'question' => $response['text'],
                        'intent' => $intent_name,
                        'value' => $value
                    ]
                ]);

                break;

            case 'no_of_seat':
                return response()->json([
                    'status' => true,
                    'message' => 'Your order has been confirmed. Thank You',
                    'data' => [
                        'question' => $response['text'],
                        'intent' => $intent_name,
                        'value' => $value
                    ]
                ]);
                break;


            default:
                return $response;
        }

    }

    public function test(Request $request){


        return OpenAi::speechToText($request->path);
    }


    public function LoanPayment(Request $request){
        $voice_response = new VoiceResponse();
        
        info($request->query());
        info($request->input());
        
        if($request->query('hello') == '1'){
            //$voice_response->say("Hello , I am Ria Talking from City Centrtal Bank.  ");
            //$voice_response->say("Am I talking to Mr. Khairul  Alam ?");
            $voice_response->play("http://sgdev.infosoftbd.com/voice/hello_intro.mp3");
            $voice_response->gather(['input'=>'speech','timeout'=>100,'action'=>route('api.voice.agent.loan',['yes_no'=>true])]);
        }elseif($request->query('get_avaialable') == '1'){
           // $voice_response->say("In which day that money will be avaiable in your account ? ");
           // $voice_response->say("Today, Tomorrow or some other days ? ");
           $voice_response->play("http://sgdev.infosoftbd.com/voice/get_date.mp3");
            $voice_response->gather(['input'=>'speech','timeout'=>100,'action'=>route('api.voice.agent.loan',['get_date'=>true])]);
        }
        elseif($request->query('yes_no') == '1'){
            if($request->input('speech_result') != ''){
                $response = $this->getWitIntent($request->input('speech_result'));
                info($response);
                if(isset($response["entities"]["yes-no:yes-no"])){
                    $voice_response->play("http://sgdev.infosoftbd.com/voice/hello_intro.mp3");
                }else{                   
                        $voice_response->play("http://sgdev.infosoftbd.com/voice/available.mp3");
                        $voice_response->gather(['input'=>'speech','timeout'=>100,'action'=>route('api.voice.agent.loan',['get_avaialable'=>true])]);
                        return  $voice_response->xml();
                }
            }else{
            
              $voice_response->play("http://sgdev.infosoftbd.com/voice/available.mp3");
              $voice_response->gather(['input'=>'speech','timeout'=>100,'action'=>route('api.voice.agent.loan',['get_avaialable'=>true])]);
              return  $voice_response->xml();

            }
            $voice_response->gather(['input'=>'speech','timeout'=>100,'action'=>route('api.voice.agent.loan',$request->query())]);
                       
        } elseif($request->query('get_date') == '1'){
            if($request->input('speech_result') != ''){
                $response = $this->getWitIntent($request->input('speech_result'));
                info($response);
                if(isset($response["entities"]["get_date:get_date"])){
                    //$val = $response["entities"]["Yes_No:Yes_No"]["value"];
                    //if($val == 'জি'){
                       // $voice_response->say("Thank you Sir . Please make sure the money is available in your account on time. ");
                       $voice_response->play("http://sgdev.infosoftbd.com/voice/finish.mp3");
                        return  $voice_response->xml();
                   // }else{
                       
                   // }
                }else{
                  //  $voice_response->say("Ok Sir,  Please make sure the money is available in your account on time. ");
                  $voice_response->play("http://sgdev.infosoftbd.com/voice/finish.mp3"); 
                        $voice_response->say("Thank you .");
                }
            }else{
               // $voice_response->say("I don't understand your voice Sir , Can you repeat again ?");
               $voice_response->play("http://sgdev.infosoftbd.com/voice/available_repeat.mp3");
                $voice_response->gather(['input'=>'speech','timeout'=>100,'action'=>route('api.voice.agent.loan',$request->query())]);
            }
            
                       
        }else{
            $voice_response->play("http://sgdev.infosoftbd.com/voice/hello_new.mp3");
            $voice_response->gather(['input'=>'speech','timeout'=>100,'action'=>route('api.voice.agent.loan',['hello'=>true])]);
       
        }
        
        
       
 
       // $voice_response->say("You have and EMI monthly payment fourty Five thousand Taka,  which will be due today . ");
       
        
       
        return  $voice_response->xml();
    
    }


    public function getWitIntent($text){

        $url = 'https://api.wit.ai/message';
       // $url = 'https://api.wit.ai/speech';
        $params = [
            'v' =>'20240311',
            'q' => $text,
        ];

        $queryString = http_build_query($params);


        $ch = curl_init();


        curl_setopt($ch, CURLOPT_URL, $url . '?' . $queryString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


        $headers = [
            'Authorization: Bearer B6RQGBQ4L47Q2IZIWW4SICCRJ3PQQ64W',
            'Content-Type: application/json',
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


        $response = curl_exec($ch);


        if (curl_errno($ch))
            return false;


        curl_close($ch);

        return json_decode($response, true);

    }

}
