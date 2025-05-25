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

        $logHandler = new StreamHandler(getStdout());
$logHandler->setFormatter(new ConsoleFormatter());
$logger = new Logger('server');
$logger->pushHandler($logHandler);

$server = SocketHttpServer::createForDirectAccess($logger);

$server->expose(new Socket\InternetAddress('127.0.0.1', 1337));
$server->expose(new Socket\InternetAddress('[::1]', 1337));

$errorHandler = new DefaultErrorHandler();

$acceptor = new AllowOriginAcceptor(
    ['http://localhost:1337', 'http://127.0.0.1:1337', 'http://[::1]:1337'],
);

$clientHandler = new class implements WebsocketClientHandler {
    public function __construct(
        private readonly WebsocketGateway $gateway = new WebsocketClientGateway(),
    ) {
    }

    public function handleClient(
        WebsocketClient $client,
        Request $request,
        Response $response,
    ): void {
        $this->gateway->addClient($client);

        foreach ($client as $message) {
            $this->gateway->broadcastText(sprintf(
                '%d: %s',
                $client->getId(),
                (string) $message,
            ));
        }
    }
    };

    $websocket = new Websocket($server, $logger, $acceptor, $clientHandler);

    $router = new Router($server, $logger, $errorHandler);
    $router->addRoute('GET', '/broadcast', $websocket);
    $router->setFallback(new DocumentRoot($server, $errorHandler, __DIR__ . '/public'));

    $server->start($router, $errorHandler);

    // Await SIGINT or SIGTERM to be received.
    $signal = trapSignal([SIGINT, SIGTERM]);

    $logger->info(sprintf("Received signal %d, stopping HTTP server", $signal));

    $server->stop();
    }
}
