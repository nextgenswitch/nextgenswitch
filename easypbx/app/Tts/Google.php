<?php
namespace App\Tts;
use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;

use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\StreamingRecognitionConfig;


class Google{
  
   function __construct($credential,$storage_path = null)
   {
    
        $storage_path = ($storage_path)?$storage_path:storage_path(config('enums.tts_storage_path') . 'google');
        @mkdir($storage_path,0777,true);
        $credential_path = $storage_path . "/" . md5($credential) . ".json";
        file_put_contents($credential_path,$credential);
        putenv('GOOGLE_APPLICATION_CREDENTIALS='. $credential_path);        

   }


   public function text_to_speech($text,$model,$lang){
    // Providing the Google Cloud project ID.
        $full_path = $this->outputPath( $text );

        if ( file_exists( $full_path ) ) {
            return ['path'=>$full_path,'cache'=>true];
        }
       
        $textToSpeechClient = new TextToSpeechClient();

        $input = new SynthesisInput();
        $input->setText($text);
        $voice = new VoiceSelectionParams();
        $voice->setLanguageCode($lang);
        $voice->setName($model);
        $audioConfig = new AudioConfig();
        $audioConfig->setAudioEncoding(AudioEncoding::MP3);
        $resp = $textToSpeechClient->synthesizeSpeech($input, $voice, $audioConfig);
        file_put_contents($full_path, $resp->getAudioContent());
        if ( is_file( $full_path ) ) {
            return ['path'=>$full_path,'cache'=>false];
        }
        return false;
   }

   public  function speechToText($audioPath,$model,$lang){                
        if(is_dir($audioPath) || !file_exists($audioPath))
        return false;
        $recognitionConfig = new RecognitionConfig();
        $recognitionConfig->setEncoding(AudioEncoding::LINEAR16);
        $recognitionConfig->setSampleRateHertz(8000);
        $recognitionConfig->setLanguageCode($lang);
        $config = new StreamingRecognitionConfig();
        $config->setConfig($recognitionConfig);
        
        $audioResource = fopen($audioPath, 'r');
        
        $responses = $speechClient->recognizeAudioStream($config, $audioResource);
        
        fclose($audioResource);
        foreach ($responses as $$result) {
            $alternatives = $result->getAlternatives();
            $mostLikely = $alternatives[0];
            $transcript = $mostLikely->getTranscript();
            $confidence = $mostLikely->getConfidence();
            return ['text'=>$transcript,'confidence'=>$confidence];
        }

        return false;


   
    }

   
   private function outputPath( $text, $type = 'mp3' ) {

    $storage_path = storage_path( config( 'enums.tts_storage_path' ) . 'google'  );
    @mkdir( $storage_path, 0777, true );

    $filename = md5( $text ) . "." . $type;

    $full_path = $storage_path . '/' . $filename;

    return $full_path;
}
}