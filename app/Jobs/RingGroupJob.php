<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\Api\Functions\RingGroupWorker;

class RingGroupJob implements ShouldQueue //, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $id;
    private $call_id;
    public function __construct($id,$call_id)
    {
        //
        $this->id = $id;
        $this->call_id = $call_id;
    }

    public function handle(): void
    {
        RingGroupWorker::handle($this->call_id,$this->id);

    }
}   

