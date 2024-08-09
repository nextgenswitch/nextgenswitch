<?php
namespace App\Tts;

use App\Models\TtsHistory;
use App\Tts\Cloudflare;
use App\Tts\Azure;
use App\Tts\WitAi;
use App\Tts\Google;
use App\Models\TtsProfile;

class Tts {

    public static function synthesize( $text, $organization_id,$profile_id = null ,$options = []) {
        $tts_profile = null;
        if(!empty($profile_id)){
            if(is_numeric($profile_id)){
                $tts_profile = TtsProfile::find( $profile_id );            
            }else{
                $org_id = $organization_id;
                $tts_profile = TtsProfile::where("name",$profile_id)->where(function ($query) use ($org_id) {
                    $query->where("organization_id", $org_id)
                          ->orWhere("organization_id", 0);
                })->first();
            }
        }
       
        if (!$tts_profile ) {
            $org_id = $organization_id;
            $tts_profile = TtsProfile::where(function ($query) use ($org_id) {
                $query->where("organization_id", $org_id)
                      ->orWhere("organization_id", 0);
            })->orderBy( "is_default", 'DESC' )->first();            
        }

        if (!$tts_profile ) goto out;

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
            $generic = new GenericTts($tts_profile->config->api_key,$tts_profile->config->api_endpoint);
            $return  = $generic->textToSpeech( $text, $model, $lang);
        }elseif ( $tts_profile->provider == 'microsoft_azure' ) {
            $azure = new Azure($tts_profile->config->api_key,$tts_profile->config->region);
            $return  = $azure->textToSpeech( $text, $model, $lang);
        }elseif ( $tts_profile->provider == 'google_cloud' ) {
            $google = new Google($tts_profile->config->json);
            $return  = $google->text_to_speech( $text, $model, $lang);
        }

        


        if($return){
            if($return['cache']== false){
                TtsHistory::create([
                    'organization_id' => $organization_id,
                    'tts_profile_id' => $tts_profile->id,
                    'type' => $tts_profile->type,
                    'input' => $text,
                    'output' => $return['path']
                ]);
            }
            return $return['path'];
        }

        out:
        return false;

    }

    public static function speechToText( $audioPath,$organization_id) {
        
        $org_id = $organization_id;
        $tts_profile = TtsProfile::where('type',1)->where(function ($query) use ($org_id) {
            $query->where("organization_id", $org_id)
                  ->orWhere("organization_id", 0);
        })->orderBy( "is_default", 'DESC' )->first();


        if (  ! $tts_profile ) {
            goto out;
        }
        //info($tts_profile);
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
            $generic = new GenericTts($tts_profile->config->api_key,$tts_profile->config->api_endpoint);
            $ret = $generic->speechToText($audioPath,$tts_profile->model,$tts_profile->language);
        }elseif ( $tts_profile->provider == 'microsoft_azure' ) {
            $azure = new Azure($tts_profile->config->api_key,$tts_profile->config->region);
            $ret  = $azure->speechToText($audioPath,$tts_profile->model,$tts_profile->language);
        }elseif ( $tts_profile->provider == 'google_cloud' ) {
            $google = new Google($tts_profile->config->json);
            $ret  = $google->speechToText($audioPath,$tts_profile->model,$tts_profile->language);
        }

        if($ret && isset($ret['text'])){
            if(!isset($ret['confidence']))
            $ret['confidence'] = null;
            // add history here
            TtsHistory::create([
                'organization_id' => $organization_id,
                'tts_profile_id' => $tts_profile->id,
                'type' => $tts_profile->type,
                'input' => $audioPath,
                'output' => $ret['text']
            ]);

            return $ret;
        }
        
        
        
        out:
        info("no profile found");
        return false;


    }

}

?>