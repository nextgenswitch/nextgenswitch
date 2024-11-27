<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\Api\Functions\QueueWorker;


class QueueJob implements ShouldQueue //, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $call_queue_id;
    private $func_id;
    //public $uniqueFor = 60;
    /**
     * Create a new job instance.
     */
    public function __construct($call_queue_id,$func_id)
    {
        //
        $this->call_queue_id = $call_queue_id;
        $this->func_id = $func_id;
    }

    

   /*  public function uniqueId(): string
    {
        return "queue_" . $this->call_queue_id;
    } */

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
        $queue_worker = new QueueWorker($this->call_queue_id,$this->func_id);
        $queue_worker->handle();
       
       // $this->release();
    }
}
