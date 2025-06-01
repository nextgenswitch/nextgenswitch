<?php
namespace App\Tts;

use App\Models\TtsHistory;
use App\Tts\Cloudflare;
use App\Tts\Azure;
use App\Tts\WitAi;
use App\Tts\Google;
use App\Tts\Generic;
use App\Tts\Groq;
use App\Models\TtsProfile;

class Tts {

    public static function llm( $prompt, $instructions, $organization_id, $profile_id = null ) {
        $res = false;

        if($profile_id && is_numeric($profile_id)){
            $profile = TtsProfile::find($profile_id);
        }else{
            $profile = TtsProfile::where('organization_id', $organization_id)->where('type', 2)->where('is_default', 1)->first();
        }

        // $profile = TtsProfile::find($profile_id);
        $profile->config = json_decode( $profile->config );
        $params = [$prompt, $instructions];

        if(isset($profile->model)) $params[] = $profile->model;

        // info($profile);
        $start_time = microtime(true);
        if ( $profile->provider == 'openai' ) {
            $openai   = new OpenAi( $profile->config->api_key );
            // $instructions = "you are a virtual voice assistant. If a user's query indicates that they want to speak to a human or live agent (using keywords or by expressing frustration), please respond with a special flag like [LIVE_AGENT_REQUESTED] instead of a normal answer. Use the following resource to answer questions in short: $instructions";
            $res  = $openai->llm(...$params);
        }

        elseif ( $profile->provider == 'cloudflare' ){
            $cloudflare = new Cloudflare($profile->config->api_key, $profile->config->bearer_token);
            $res =  $cloudflare->llm(...$params);
        }
        elseif ( $profile->provider == 'groq' ){
            $groq = new Groq($profile->config->api_key);
            $res =  $groq->llm(...$params);
        }
        
        elseif ( $profile->provider == 'generic' ){
            $generic = new Generic($profile->config->api_key, $profile->config->api_endpoint);
            $res =  $generic->llm(...$params);
        }
        
        $end_time = microtime(true);
        $execution_time = ($end_time - $start_time);
        info("llm execution time " . $execution_time);

        return $res;
    }

    public static function synthesize( $text, $organization_id,$profile_id = null ,$options = []) {
        $tts_profile = null;
        if (is_numeric($profile_id)) {
            $tts_profile = TtsProfile::where('id', $profile_id);
        } elseif ($profile_id) {
            $tts_profile = TtsProfile::where('type', 0)->where('name', $profile_id);
        } else {
            $tts_profile = TtsProfile::where('type', 0);
        }
        
        if ($tts_profile) {
            $tts_profile = $tts_profile->where(function ($query) use ($organization_id) {
                $query->where('organization_id', $organization_id)
                      ->orWhere('organization_id', 0);
            })->orderBy('is_default', 'DESC')->first();
        }
        
        if (  ! $tts_profile ) {
            info("tts no profile found");
            goto out;
        }
       
        $start_time = microtime(true);
        $model =  $tts_profile->model;
        $lang =  $tts_profile->language;

        if(isset($options['model']))   $model = $options['model']; 
        if(isset($options['lang']))   $lang = $options['lang'];

        $return  = false;
        $tts_profile->config = json_decode( $tts_profile->config );
        if ( $tts_profile->provider == 'witai' ) {
            $witai   = new WitAi( $tts_profile->config->api_key, $tts_profile->config->api_version );
            $return  = $witai->textToSpeech( $text, $model, $lang);
        }elseif ( $tts_profile->provider == 'amazon_polly' ) {           
            $amazon = new AmazonPolly( $tts_profile->config->aws_access_key_id, $tts_profile->config->aws_secret_access_key, $tts_profile->config->aws_default_region );
            $return =  $amazon->textToSpeech( $text, $lang, $model, 'mp3', 'text' );
        }elseif ( $tts_profile->provider == 'openai' ) {
            $openai   = new OpenAi( $tts_profile->config->api_key);
            $return  = $openai->textToSpeech( $text, $model);
        }elseif ( $tts_profile->provider == 'generic' ) {
            $generic = new Generic($tts_profile->config->api_key,$tts_profile->config->api_endpoint);
            $return  = $generic->textToSpeech( $text, $model, $lang);
        }elseif ( $tts_profile->provider == 'microsoft_azure' ) {
            $azure = new Azure($tts_profile->config->api_key,$tts_profile->config->region);
            $return  = $azure->textToSpeech( $text, $model, $lang);
        }elseif ( $tts_profile->provider == 'google_cloud' ) {
            $google = new Google($tts_profile->config->json);
            $return  = $google->text_to_speech( $text, $model, $lang);
        }elseif ( $tts_profile->provider == 'groq' ) {
            $groq = new Groq($tts_profile->config->api_key);
            $return  = $groq->text_to_speech( $text, $model, $lang);
        }

        
        $end_time = microtime(true);
        $execution_time = ($end_time - $start_time);
        info("tts execution time " . $execution_time);

        if($return){
            if($return['cache']== false){
                info('tts');
                info($tts_profile);
                TtsHistory::create([
                    'organization_id' => $organization_id,
                    'tts_profile_id' => $tts_profile->id,
                    'type' => $tts_profile->type,
                    'input' => $text,
                    'output' => $return['path'],
                    'response_time'=>$execution_time
                ]);
            }
            return $return['path'];
        }

        out:
        return false;

    }

    public static function speechToText( $audioPath,$organization_id,$profile_id = null) {
        
        $tts_profile = null;
        if (is_numeric($profile_id)) {
            $tts_profile = TtsProfile::where('id', $profile_id);
        } elseif ($profile_id) {
            $tts_profile = TtsProfile::where('type', 1)->where('name', $profile_id);
        } else {
            $tts_profile = TtsProfile::where('type', 1);
            info("stt come here");
        }
        if($tts_profile)
        $tts_profile = $tts_profile->where(function ($query) use ($organization_id) {
            $query->where("organization_id", $organization_id);
        })->orderBy( "is_default", 'DESC' )->first();


        if (  ! $tts_profile ) {
            info("stt no profile found");
            goto out;
        }
        info("stt profile info" . $profile_id);
        info($tts_profile);
        $start_time = microtime(true);
        $tts_profile->config = json_decode( $tts_profile->config );
        $ret = false;
        if ( $tts_profile->provider == 'witai' ){          
            $witai  = new WitAi( $tts_profile->config->api_key, $tts_profile->config->api_version );
            $ret =  $witai->speechToText( $audioPath);           
        }elseif ( $tts_profile->provider == 'cloudflare' ){
            $cloudflare = new Cloudflare($tts_profile->config->api_key,$tts_profile->config->bearer_token);
            $ret =  $cloudflare->speechToText($audioPath);
        }elseif ( $tts_profile->provider == 'openai' ){
            $openai = new OpenAi($tts_profile->config->api_key);
            $ret = $openai->speechToText($audioPath,$tts_profile->model,$tts_profile->language);
        }elseif ( $tts_profile->provider == 'generic' ){
            $generic = new Generic($tts_profile->config->api_key,$tts_profile->config->api_endpoint);
            $ret = $generic->speechToText($audioPath,$tts_profile->model,$tts_profile->language);
        }elseif ( $tts_profile->provider == 'microsoft_azure' ) {
            $azure = new Azure($tts_profile->config->api_key,$tts_profile->config->region);
            $ret  = $azure->speechToText($audioPath,$tts_profile->model,$tts_profile->language);
        }elseif ( $tts_profile->provider == 'google_cloud' ) {
            $google = new Google($tts_profile->config->json);
            $ret  = $google->speechToText($audioPath,$tts_profile->model,$tts_profile->language);
        }elseif ( $tts_profile->provider == 'groq' ) {
            $google = new Groq($tts_profile->config->api_key);
            $ret  = $google->speechToText( $audioPath,$tts_profile->model,$tts_profile->language);
        }

        $end_time = microtime(true);

        // Calculate the Script Execution Time
        $execution_time = ($end_time - $start_time);
        info("stt execution time " . $execution_time);

        if($ret && isset($ret['text'])){
            if(!isset($ret['confidence']))
            $ret['confidence'] = null;
            // add history here
            info('stt');
            info($tts_profile->type);
            TtsHistory::create([
                'organization_id' => $organization_id,
                'tts_profile_id' => $tts_profile->id,
                'type' => $tts_profile->type,
                'input' => $audioPath,
                'output' => $ret['text'],
                'response_time'=>$execution_time
            ]);

            return $ret;
        }
        
        
        
        out:
        info("error in transcribe " . $audioPath);
        return false;


    }

}

?>