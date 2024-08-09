<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Amp\Socket;
use function Amp\async;
use App\Http\Controllers\Api\Functions\SwitchHandler;
use Illuminate\Support\Facades\Log;

class Amphp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:amphp';

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
        $server = Socket\listen('127.0.0.1:6785');

        echo 'Listening for new connections on ' . $server->getAddress() . ' ...' . PHP_EOL;
       // echo 'Open your browser and visit http://' . $server->getAddress() . '/' . PHP_EOL;

        while ($socket = $server->accept()) {
            async(function () use ($socket) {
                $read = $socket->read();
                //echo $read;
                //$address = $socket->getRemoteAddress();
                //$ip = $address->getHost();
                //$port = $address->getPort();

                //echo "Accepted connection from {$address}." . PHP_EOL;

                //$body = "Hey, your IP is {$ip} and your local port used is {$port}.";
                //$bodyLength = \strlen($body);

                //$socket->write("HTTP/1.1 200 OK\r\nConnection: close\r\nContent-Length: {$bodyLength}\r\n\r\n{$body}");
                $socket->write($this->switchProcess($read));
                $socket->end();
            });
        }
    }

    function switchProcess($input){
        Log::setDefaultDriver('daily-cli');
       // info($input);       
        //echo "test";
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
           // info("trying path ".  $response_headers['Path']);
            $func = str_replace("/","_",substr($response_headers['Path'],1));
            $response = json_encode(SwitchHandler::{$func}($data));
        }catch (\Exception $exception) {
            //info($exception->getMessage());
        }  

        return $response;
    }
}
