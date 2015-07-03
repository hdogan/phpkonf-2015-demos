<?php
/**
 * WebSocket Server.
 *
 * @see http://www.w3.org/TR/2012/CR-websockets-20120920/
 * @see https://tools.ietf.org/html/rfc6455
 */
$websocketServer = new swoole_websocket_server('127.0.0.1', 8080);

$websocketServer->on('open', function($server, $request) {
    $data = json_encode([
        'time' => time(),
        'message' => 'Merhaba!',
    ]);

    $server->push($request->fd, $data);
});

$websocketServer->on('message', function ($server, $frame) {
    $data = json_encode([
        'time' => time(),
        'message' => $frame->data,
    ]);

    $server->push($frame->fd, $data);
});

$websocketServer->start();
