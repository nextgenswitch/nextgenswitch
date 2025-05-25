<?php

namespace App\Jobs;

use App\Mail\NotifyMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProcessMail implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $configs, $data;
    /**
     * Create a new job instance.
     */
    public function __construct( $configs, $data ) {
        $this->configs = $configs;
        $this->data    = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void {
        Config::set( [
            'mail.mailers.smtp.host'       => $this->configs['host'],
            'mail.mailers.smtp.port'       => $this->configs['port'],
            'mail.mailers.smtp.username'   => $this->configs['username'],
            'mail.mailers.smtp.password'   => $this->configs['password'],
            'mail.mailers.smtp.encryption' => $this->configs['encryption'],
            'mail.from.address' => $this->configs['from_email'],
            'mail.from.name' => $this->configs['from_name']
        ] );
        
        //Log::info($this->configs);
        //Log::info($this->data);

        Mail::to( $this->data['to'] )->send( new NotifyMail( $this->data ) );
    }
}
