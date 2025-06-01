<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Controllers\Api\Functions\SwitchHandler;
use Illuminate\Support\Facades\Log;

use Amp\ByteStream;
use function Amp\trapSignal;
use Amp\Http\HttpStatus;
use Amp\Http\Server\DefaultErrorHandler;
use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\Response;
use Amp\Http\Server\SocketHttpServer;
use Amp\Log\ConsoleFormatter;
use Amp\Log\StreamHandler;
use Psr\Log\NullLogger;

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

    public function handle(){
        $requestHandler = new class() implements RequestHandler {
            public function handleRequest(Request $request) : Response
            {
                Log::setDefaultDriver('daily-cli');
                $url = parse_url($request->getUri());
                //var_dump($url);
                $path = substr($url['path'],1);
                $body = $request->getBody();
                $data = json_decode($body,true);
                //var_dump($data);
                $func = str_replace("/","_",$path);
                //var_dump($func);
                //var_dump($data);
                if(method_exists('App\Http\Controllers\Api\Functions\SwitchHandler',$func))
                    $response = json_encode(SwitchHandler::{$func}($data));
                else
                    $response = '{"error":1}';
                //var_dump($response);
                return new Response(
                    status: HttpStatus::OK,
                    headers: ['Content-Type' => 'application/json'],
                    body: $response,
                );
            }
        };

        $errorHandler = new DefaultErrorHandler();

        $server = SocketHttpServer::createForDirectAccess(new NullLogger);
        $server->expose(config('easypbx.AGI_HOST'));
        $server->start($requestHandler, $errorHandler);
        $this->info("AGI Process started at " . config('easypbx.AGI_HOST'));
        // Serve requests until SIGINT or SIGTERM is received by the process.
        trapSignal([SIGINT, SIGTERM]);

        $server->stop();
    }


}
