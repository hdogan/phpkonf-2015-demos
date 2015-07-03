<?php
/**
 * HTTP Server.
 */
$httpServer = new swoole_http_server('127.0.0.1', 80);
$htmlFile = file_get_contents('http.html');

$httpServer->on('request', function($request, $response) use ($htmlFile) {
    $response->header('Content-Type', 'text/html; charset=utf-8');
    $response->end($htmlFile);
});

$httpServer->start();
