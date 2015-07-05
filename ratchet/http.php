<?php
require 'vendor/autoload.php';

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use phpkonf\Http;

$ioServer = IoServer::factory(new HttpServer(new Http()), 80);
$ioServer->run();
