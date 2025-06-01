<?php

namespace App\Mail;

use App\Models\MailProfile;
use Illuminate\Support\Facades\Log;

class Mail{
    
    public static function send($to, $subject, $body, $mail_profile_id, $template = 'default') {

        

        $mail_profile = MailProfile::find($mail_profile_id);
        
        if(! $mail_profile ){
            return [
                'status' => false,
                'errors' => [
                    'mail_profile' => 'There are currently no profiles available for sending emails.'
                ]
            ];
        }
        

        $nspace = 'App\Mail\\' . $mail_profile->provider;

        
        if($mail_profile && class_exists($nspace)){
            
            $mail = new $nspace(json_decode($mail_profile->options, true));
            
            return $mail->send($to, $subject, $body, $template);
        }

    }
}