<?php

namespace App\Mail;

interface MailService{

    public function send($to, $subject, $body, $template);
}