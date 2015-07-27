<?php
/**
 * WebSocket Server.
 */
require_once 'vendor/autoload.php';

use Workerman\Worker;

$websocketServer = new Worker('websocket://127.0.0.1:8080');

$websocketServer->onConnect = function ($connection) {
    $data = json_encode([
        'time' => time(),
        'message' => 'Merhaba!',
    ]);

    $connection->send($data);
};

$websocketServer->onMessage = function ($connection, $data) {
    $data = json_encode([
        'time' => time(),
        'message' => htmlspecialchars($data, ENT_NOQUOTES|ENT_HTML5, 'UTF-8'),
    ]);

    $connection->send($data);
};

$websocketServer->onClose = function ($connection) {
};

$websocketServer->runAll();
