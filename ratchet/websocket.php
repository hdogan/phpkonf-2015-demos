<?php
require 'vendor/autoload.php';

use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use phpkonf\WebSocket;

$ioServer = IoServer::factory(
	new HttpServer(
		new WsServer(new WebSocket())
	),
	8080
);

$ioServer->run();
