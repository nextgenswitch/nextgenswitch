<?php

namespace App\Mail;

use App\Jobs\ProcessMail;
use App\Mail\MailService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class Smtp implements MailService {

    protected $options = [];

    function __construct( $options ) {
        $this->options = $options;
    }

    public function send( $to, $subject, $body, $template ) {

        $data = [
            'to'       => $to,
            'subject'  => $subject,
            'body'     => $body,
            'template' => $template,
        ];

        dispatch( new ProcessMail($this->options, $data) );
        return 'Mail waiting to be sent';
    }
}