<?php 
namespace App\Tts;
use CurlFile;

class Azure{
    private $api_key;
    private $region;

    function __construct($api_key,$region)
    {
        $this->api_key = $api_key;
        $this->region = $region;
    }

    public  function speechToText($audioPath,$model,$lang){
        
        
        if(is_dir($audioPath) || !file_exists($audioPath))
        return false;
     
        $ch = curl_init('https://' . $this->region . '.stt.speech.microsoft.com/speech/recognition/conversation/cognitiveservices/v1?language='. $lang .'&format=simple');

        $headers = array();
        $headers[] = 'Ocp-Apim-Subscription-Key: ' . $this->api_key;
        $headers[] = 'Content-Type:  audio/wav;codec="audio/pcm";';
        $headers[] = 'Host: ' . $this->region . '.stt.speech.microsoft.com';
        //$headers[] = 'Authorization: Bearer '.$token;//Token okay
        $headers[] = 'User-Agent: EasternServer';
        $headers[] = 'Transfer-Encoding: chunked';

        

        curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($audioPath));
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        $ret =  json_decode($response, true);
        //info($ret);
        if(isset($ret['RecognitionStatus']) && $ret['RecognitionStatus'] == "Success")
            return ['text'=>$ret['DisplayText'],'confidence'=>null];

        return false;
        
    }

    public function textToSpeech( $text, $model , $language ) {

        $full_path = $this->outputPath( $text );
       // info($full_path);
        if ( file_exists( $full_path ) ) {
            return ['path'=>$full_path,'cache'=>true];
        }


        $cont="<speak version='1.0' xml:lang='en-US'><voice xml:lang='en-US'  name='". $language ."-" . $model . "'>"
        . $text . 
        "</voice></speak>";

        
        $ch = curl_init();
        $fp      = fopen($full_path, 'w+' );
        curl_setopt($ch, CURLOPT_URL, 'https://' . $this->region . '.tts.speech.microsoft.com/cognitiveservices/v1');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($cont));
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FILE, $fp );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );

        $headers = array();
        $headers[] = 'Ocp-Apim-Subscription-Key: ' . $this->api_key;
        $headers[] = 'Content-Type: application/ssml+xml';
        $headers[] = 'Host: ' . $this->region . '.tts.speech.microsoft.com';
        $headers[] = 'Content-Length: '.strlen($cont);
        //$headers[] = 'Authorization: Bearer '.$token;//Token okay
        $headers[] = 'User-Agent: EasternServer';
        $headers[] = 'X-Microsoft-OutputFormat: audio-16khz-32kbitrate-mono-mp3';

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_exec($ch);
        fclose($fp);

        if ( is_file( $full_path ) ) {
            return ['path'=>$full_path,'cache'=>false];
        }
        return false;
    }

    private function outputPath( $text, $type = 'mp3' ) {

        $storage_path = storage_path( config( 'enums.tts_storage_path' ) . 'azure'   );
        @mkdir( $storage_path, 0777, true );

        $filename = md5( $text ) . "." . $type;

        $full_path = $storage_path . '/' . $filename;

        return $full_path;
    }

}