<?php
namespace phpkonf;

use Ratchet\Http\HttpServerInterface;
use Guzzle\Http\Message\RequestInterface;
use Ratchet\ConnectionInterface;

class Http implements HttpServerInterface {
    public function onOpen(ConnectionInterface $connection, RequestInterface $request = null) {
	$connection->send('Content-Type: text/html; charset=utf-8');
	$connection->send(file_get_contents('index.html'));
	$connection->close();
    }

    public function onMessage(ConnectionInterface $from, $message) {
    }

    public function onClose(ConnectionInterface $connection) {
    }

    public function onError(ConnectionInterface $from, \Exception $error) {
        $from->close();
    }
}
