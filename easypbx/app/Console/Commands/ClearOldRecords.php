<?php

namespace App\Console\Commands;

use App\Models\Call;
use App\Models\CallHistory;
use App\Models\Queue;
use App\Models\QueueCall;
use App\Models\CallRecord;
use Illuminate\Console\Command;

class ClearOldRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'records:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete records older than one month';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $one_month_ago = now()->subMonth()->format('Y-m-d');

        Call::whereDate('created_at', '<', $one_month_ago)->delete();
        CallHistory::whereDate('created_at', '<', $one_month_ago)->delete();
        Queue::whereDate('created_at', '<', $one_month_ago)->delete();
        CallRecord::whereDate('created_at', '<', $one_month_ago)->delete();
        QueueCall::whereDate('created_at', '<', $one_month_ago)->delete();
        
        $rec_path = storage_path( 'app/public/records/' );
        // $cmd = 'find ' . $rec_path . ' -type f ! -newermt ' . '"' . $one_month_ago .'"' . ' -exec /bin/rm {} \;';
        $cmd = 'find ' . $rec_path . ' -type d ! -newermt ' . '"' . $one_month_ago .'"' . ' -exec rm -rf {} +';
        // $this->info($cmd);
        exec($cmd);


        $this->info('Old records successfully deleted.');
    }
}
