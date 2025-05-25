<?php 

namespace App\Tts;
use CurlFile;
use Illuminate\Support\Facades\Http;
//https://console.groq.com/docs/api-reference#chat
class Groq{

    private $api_key;

    function __construct($api_key)
    {
        $this->api_key = $api_key;
    }


    public function llm($prompt, $instructions, $model = 'llama-3.3-70b-versatile', $lang = 'en'){
        if(!empty($lang))
        $lang = explode('-',$lang)[0]; 
        if(empty($model)) $model = 'llama-3.3-70b-versatile';
        $content = [
            'messages' => [
                ['role' => 'system', 'content' => $instructions],
                ['role' => 'user', 'content' => $prompt]
            ],
            'model'=>$model
        ];
        $url = 'https://api.groq.com/openai/v1/chat/completions';
        $response = Http::withToken($this->api_key)
        ->post($url, $content);
        info("llm response of groq");
        info($response);
        if ($response->successful()) {
            $data = $response->json();
            // info($data);

            return $data['choices'][0]['message']['content'] ?? false;
        }
        return false;
    }

    public  function speechToText($audioPath,$model = 'whisper-large-v3-turbo',$lang = 'en'){
        if(!empty($lang))
            $lang = explode('-',$lang)[0]; 
        else $lang = 'en';
        if(empty($model)) $model = 'whisper-large-v3';
        $content = ['model'=>$model,'temperature'=>0,'response_format'=>'json','language'=>$lang];
        $url = 'https://api.groq.com/openai/v1/audio/transcriptions';
        //$url = 'https://api.groq.com/openai/v1/audio/translations';
        //unset($content['language']);
        //$content['prompt'] = "please translate from Bengali"; 
        $response = Http::withToken($this->api_key)->attach('file', file_get_contents($audioPath), basename($audioPath))->
        post($url, $content);
        info("groq response");
        info($response);
        $data = $response->json();
        if(isset($data['text']))
          return $data;
        else return false;

    }

    public function text_to_speech($text,$model,$lang){
        $full_path = $this->outputPath( $text );
        if ( file_exists( $full_path ) ) {
            return ['path'=>$full_path,'cache'=>true];
        }

      
        $url = 'https://api.groq.com/openai/v1/audio/speech';
        if($model == '') $model = 'Angelo-PlayAI';
        $content = ['model'=>'playai-tts','input'=>$text,'voice'=>$model,'response_format'=>'wav'];
        info($content);
        $response = Http::withToken($this->api_key)->post($url, $content);
        //info($response);
        if($response->getStatusCode() == 200){
            file_put_contents($full_path,$response->getBody());
            
            if ( file_exists( $full_path ) ) {
                return ['path'=>$full_path,'cache'=>false];
            }
        }
        
        else return false;
    }

    private function outputPath( $text, $type = 'wav' ) {

        $storage_path = storage_path( config( 'enums.tts_storage_path' ) . 'groq' );
        @mkdir( $storage_path, 0777, true );

        $filename = md5( $text ) . "." . $type;

        $full_path = $storage_path . '/' . $filename;

        return $full_path;
    }




}    