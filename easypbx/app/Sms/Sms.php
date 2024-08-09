<?php

namespace App\Sms;





class Sms{
    public static function send($to, $body, $from = null, $sms_profile = null){

        $nspace = 'App\Sms\\' . $sms_profile->provider;

        
        if($sms_profile && class_exists($nspace)){
            //$sms = app()->makeWith($nspace, json_decode($sms_profile->options, true));
            $sms = new $nspace(json_decode($sms_profile->options, true));
            return $sms->send($to, $body, $from);
        }
    }
}