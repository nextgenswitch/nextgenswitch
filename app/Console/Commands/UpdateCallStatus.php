<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Call;
use App\Enums\CallStatusEnum;
use App\Http\Controllers\Api\Functions\SwitchHandler;

class UpdateCallStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-call-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "If the call status is not updated sometimes, it updates the calls.";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info('Call handle method of updateCallStatus');

        $switch_call_ids = [];
        try {
            $switch_calls = Http::get( "http://" . config( 'settings.switch.http_listen' ) . "/call/list")->json();

            foreach($switch_calls as $call){
                $switch_call_ids[] = $call['call_id'];                         
            }

            // info($switch_call_ids);

            $calls = Call::where('status', '<', CallStatusEnum::Disconnected)->get();
            // info($calls);

            foreach($calls  as $call){
                if(!in_array($call->id, $switch_call_ids)){
                    $cdr = Http::get( "http://" . config( 'settings.switch.http_listen' ) . "/call/get",['call_id'=>$call->id])->json();                
                    if(isset($cdr['call_id'])) SwitchHandler::call_update($cdr);
                    else{ // call not found in cdr , so update manually
                        $status = $call->status == CallStatusEnum::Established ? CallStatusEnum::Disconnected : CallStatusEnum::Failed;
                        $call->update(['status' => $status]);
                    } 
                
                }
            }
        }catch (\Exception $exception) {
           //info($exception->getMessage());
        }  

    }
}
