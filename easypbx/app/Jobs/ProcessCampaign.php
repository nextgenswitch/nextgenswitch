<?php

namespace App\Jobs;

use App\Http\Controllers\Api\Functions\CampaignWorker;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCampaign implements ShouldQueue
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


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $worker = new CampaignWorker($this->campaignID);
        $worker->process();
    }
}
