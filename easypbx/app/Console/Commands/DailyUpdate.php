<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DailyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-update';

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
        $rec_path = storage_path( 'app/public/records/' );
        exec('find ' . $rec_path . ' -name "*.wav" -mtime +1 -exec /bin/rm {} \;');
    }
}
