<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Api\Functions\SwitchHandler;
use Illuminate\Support\Facades\Log;
class SwitchConsole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:switch-console';

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
        define("SwitchConsole",true);
        $handle = fopen('php://stdin','r');
        $input = fgets($handle);
        //config('logging.default', 'daily-cli');
        Log::setDefaultDriver('daily-cli');
        //info($input);       
      
        $headers = strstr($input,"\r\t\r\t",true);
        //info($headers); 
        //echo $headers; 
        $body = trim(strstr($input,"\r\t\r\t"));
        
        //info($body);
        $data = json_decode($body,true);
        //info($data);
            
        $line = strtok($headers, "\r\t");
        $status_code = trim($line);
        $response_headers = [];
        // Parse the string, saving it into an array instead
        while (($line = strtok("\r\t")) !== false) {
            if(false !== ($matches = explode(':', $line, 2))) {
            $response_headers["{$matches[0]}"] = trim($matches[1]);
            }  
        }
        //info( $response_headers);
        $response = "{}";
        try {
          //  info("trying path ".  $response_headers['Path']);
            $func = str_replace("/","_",substr($response_headers['Path'],1));
            $response = json_encode(SwitchHandler::{$func}($data));
        }catch (\Exception $exception) {
            //info($exception->getMessage());
        }  

        $this->info($response); 

    }
}
