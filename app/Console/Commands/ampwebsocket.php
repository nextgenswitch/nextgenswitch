<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Amp\Http\Server\DefaultErrorHandler;
use Amp\Http\Server\Request;
use Amp\Http\Server\Response;
use Amp\Http\Server\Router;
use Amp\Http\Server\SocketHttpServer;
use Amp\Http\Server\StaticContent\DocumentRoot;
use Amp\Log\ConsoleFormatter;
use Amp\Log\StreamHandler;
use Amp\Socket;
use Amp\Websocket\Compression\Rfc7692CompressionFactory;
use Amp\Websocket\Server\AllowOriginAcceptor;
use Amp\Websocket\Server\Websocket;
use Amp\Websocket\Server\WebsocketClientGateway;
use Amp\Websocket\Server\WebsocketClientHandler;
use Amp\Websocket\Server\WebsocketGateway;
use Amp\Websocket\WebsocketClient;
use Monolog\Logger;
use function Amp\ByteStream\getStdout;

class ampwebsocket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ampwebsocket';

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

    }
}
