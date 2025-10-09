<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Call;
use App\Enums\CallStatusEnum;

class HourlyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:hourly-update';

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
        //info("From cron job");
        //Call::where('status','<',CallStatusEnum::Established )->whereRaw('TIMESTAMPDIFF(MINUTE, connect_time, now())  > 1')->update(['status'=>CallStatusEnum::Failed->value]);
         Call::where('status', '<', CallStatusEnum::Established)
            ->whereNotNull('connect_time')
            ->where('connect_time', '<', now()->subMinute())
            ->update(['status' => CallStatusEnum::Failed->value]);
 
    }
}
