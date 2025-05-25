<?php

namespace App\Jobs;

use App\Http\Controllers\Api\Functions\BroadcastWorker;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;
class ProcessBroadcast implements ShouldQueue , ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $campaignID;
    /**
     * Create a new job instance.
     */
    public function __construct(int $campaignID)
    {
        $this->campaignID = $campaignID;
      
    }

    public $uniqueFor = 3600;
 
    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return $this->campaignID;
    }

   

   


    /**
     * Execute the job.
     */
    public function handle(): void
    {
       // info("Job id is " . $this->job->getJobId());
        $worker = new BroadcastWorker($this->campaignID);
        $worker->process();
    }
}
