<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Campaign;
use App\Http\Controllers\Api\FunctionCall;

class minuteUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:minute-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $this->dispatchBroadcasts();

    }

    function dispatchBroadcasts(){
        $braodcasts = Campaign::where('status',1)->get();
        foreach($braodcasts as $campaign){
            if(Campaign::inTime(($campaign))){
                $resp = FunctionCall::create_worker(['url'=>route('api.broadcast_worker',['campaign_id'=>$campaign->id]),'name'=>"broadcast:" . $campaign->id,'delay'=>1,'timeout'=>120]); 
                info("execute broadcast job " . $campaign->id);
                //info($resp);
            }
        }
    }
}
