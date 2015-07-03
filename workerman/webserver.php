<?php
/**
 * HTTP Server.
 */
require_once 'vendor/autoload.php';

use Workerman\WebServer;

$webServer = new WebServer('http://127.0.0.1:80');
$webServer->addRoot('localhost', 'public_html/');

WebServer::runAll();
