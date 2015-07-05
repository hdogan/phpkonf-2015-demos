<?php
namespace phpkonf;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocket implements MessageComponentInterface {
    public function onOpen(ConnectionInterface $connection) {
        $data = [
            'time' => time(),
            'message' => 'Merhaba!',
        ];

        $connection->send($data);
    }

    public function onMessage(ConnectionInterface $from, $message) {
        $data = [
            'time' => time(),
            'message' => $message,
        ];

        $from->send($data);
    }

    public function onClose(ConnectionInterface $connection) {
    }

    public function onError(ConnectionInterface $connection, \Exception $e) {
        $connection->close();
    }
}
