<?php 

namespace App\Tts;
use CurlFile;
use Illuminate\Support\Facades\Http;

class Generic{

    private $api_key;
    private $api_url;
    function __construct($api_key,$api_url)
    {
        $this->api_key = $api_key;
        $this->api_url = $api_url;
    }


    public function llm($prompt, $instructions, $model = null, $lang = null)
    {
		// info('Log from Generic class');
		// info($prompt);

        $headers = [
            "Authorization" => "Bearer " . $this->api_key
        ];

        $payload = [ 
            "prompt" => $prompt,
            // "instructions" => $instructions,
            // "model" => $model,
            // "lang" => $lang,
        ];

        info($payload);

        try {
            $response = Http::asForm()->withHeaders($headers)->post($this->api_url, $payload);
			// info($response);

            if ($response->successful()) {
                $data = $response->json();
				// info($data);

                return $data['content'] ?? false;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }


	public  function speechToText($audioPath,$model,$lang){
        
        
        if(is_dir($audioPath) || !file_exists($audioPath))
        return false;
     
        $ch = curl_init($this->api_url);

        $headers = [
	        'Authorization: Bearer ' . $this->api_key,
            'Content-Type: multipart/form-data'
		];

        if(!empty($lang)) $lang_code = explode("-",$lang);

		$postFields = [
	        'file' => new CurlFile($audioPath),
	        'model' => $model,
			'lang'=>$lang_code[0],
	    ];
        

        curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        $ret =  json_decode($response, true);
        //info($ret);
        if(isset($ret['error']))
            return false;		

        return $ret;

        
    }

    
    public function textToSpeech( $text, $model , $language ) {

        $full_path = $this->outputPath( $text );

        if ( file_exists( $full_path ) ) {
            return ['path'=>$full_path,'cache'=>true];
        }

        $headers = [
	        'Authorization: Bearer ' . $this->api_key,
            'Content-Type: multipart/form-data'
	     //   'Content-Type: application/json'
		];


        $playload = [
            'text'     => $text,
            'voice' => $model,
            'lang'  => $language,
        ];
        //info($playload);
        //info( $this->api_url);
        $request = curl_init( $this->api_url );
        $fp      = fopen( $full_path, 'w+' );
        curl_setopt( $request, CURLOPT_CUSTOMREQUEST, "POST" );
        curl_setopt( $request, CURLOPT_POSTFIELDS, $playload  );
        curl_setopt( $request, CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $request, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $request, CURLOPT_FILE, $fp );
        curl_setopt( $request, CURLOPT_FOLLOWLOCATION, true );

        $response = curl_exec( $request );
        fclose( $fp );
        if ( is_file( $full_path ) ) {
            return ['path'=>$full_path,'cache'=>false];
        }

        return false;
        
    }

   

    private function outputPath( $text, $type = 'mp3' ) {

        $storage_path = storage_path( config( 'enums.tts_storage_path' ) . 'generic'  );
        @mkdir( $storage_path, 0777, true );

        $filename = md5( $text ) . "." . $type;

        $full_path = $storage_path . '/' . $filename;

        return $full_path;
    }

}    