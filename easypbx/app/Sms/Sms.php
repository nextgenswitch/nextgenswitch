<?php

namespace App\Sms;

use Exception;

class Sms{
    public static function send($to, $body, $from = null, $sms_profile = null){

        $nspace = 'App\Sms\\' . $sms_profile->provider;
        $response = [];
        $response['success'] = false;
        $response['status'] = 0;
        $response['trxid'] = '';
        $response['res_data'] = '';
        
        if($sms_profile && class_exists($nspace)){
            try{
                $sms = new $nspace(json_decode($sms_profile->options, true));
            
                $response =  $sms->send($to, $body, $from);
            }catch(Exception $e){

            }
            
        }
        return $response;
    }
}