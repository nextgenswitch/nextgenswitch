<?php 

namespace App\Tts;
use Illuminate\Support\Facades\Http;
use CurlFile;

class OpenAi{
	private $api_key;
   
    function __construct($api_key)
    {
        $this->api_key = $api_key;
       
    }

	public function llm($prompt, $instructions, $model = null, $lang = null)
    {
		// info('Log from openAI class');
		// info($prompt);

        $url = "https://api.openai.com/v1/chat/completions";
        $model = isset($model) ? $model : "gpt-4o-mini";

        $headers = [
            "Authorization" => "Bearer " . $this->api_key
        ];

        $payload = [
            "model" => $model,
            "store" => true,
            "messages" => [
                ["role" => "system", "content" => $instructions],
                ["role" => "user", "content" => $prompt]
            ]
        ];

        try {
            $response = Http::withHeaders($headers)->post($url, $payload);
			// info($response);

            if ($response->successful()) {
                $data = $response->json();
				// info($data);

                return $data['choices'][0]['message']['content'] ?? false;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

	public  function speechToText($audioPath,$model,$lang){
		if(is_dir($audioPath) || !file_exists($audioPath))
            return false;

		$lang = explode('-',$lang)[0]; 
		
        $url = 'https://api.openai.com/v1/audio/transcriptions';

        $ch = curl_init($url);

         $headers = [
	        'Authorization: Bearer ' . $this->api_key,
	        'Content-Type: multipart/form-data'
		];

		 $postFields = [
	        'file' => new CurlFile($audioPath),
	        'model' => $model,
			'language'=>$lang,
	    ];
        

        curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	    $response = curl_exec($ch);

        $ret =  json_decode($response, true);
        

        if(isset($ret['text']))
            return ['text'=>$ret['text'],'confidence'=>null];

        return false;		
	}

	public  function textToSpeech($text,$model){
		$url = 'https://api.openai.com//v1/audio/speech';
		$full_path = $this->outputPath( $text );

        if ( file_exists( $full_path ) ) {
            return ['path'=>$full_path,'cache'=>true];
        }

		$ch = curl_init($url);
        $fp      = fopen( $full_path, 'w+' );
		$headers = [
		   'Authorization: Bearer ' . $this->api_key,
		   'Content-Type: multipart/form-data'
	   ];

		$postFields = [
		   "model"=> "tts-1",
		   'input' => $text,
		   'voice'=>$model,
	   ];
	   

	   curl_setopt($ch, CURLOPT_POST, true);
	   curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
	   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	   curl_setopt($ch, CURLOPT_FILE, $fp );
	   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );

	   $response = curl_exec($ch);
       //info($response);
	   fclose( $fp );
	   if ( is_file( $full_path ) ) {
		   return ['path'=>$full_path,'cache'=>false];
	   }

	   return false;

		
	}

	private function outputPath( $text, $type = 'mp3' ) {

        $storage_path = storage_path( config( 'enums.tts_storage_path' ) . 'openai' );
        @mkdir( $storage_path, 0777, true );

        $filename = md5( $text ) . "." . $type;

        $full_path = $storage_path . '/' . $filename;

        return $full_path;
    }



}