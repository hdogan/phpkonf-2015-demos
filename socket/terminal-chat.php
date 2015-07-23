<?php
/**
 * Old School Terminal Chat Server.
 *
 * @see https://en.wikipedia.org/wiki/ANSI_escape_code#Colors
 */
if (($serverSocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
    die(socket_strerror(socket_last_error()));
}

socket_set_option($serverSocket, SOL_SOCKET, SO_REUSEADDR, 1);

$portNumber = 3000;

if (!socket_bind($serverSocket, '127.0.0.1', $portNumber)) {
    die(socket_strerror(socket_last_error()));
}

if (!socket_listen($serverSocket, 5)) {
    die(socket_strerror(socket_last_error()));
}

socket_set_nonblock($serverSocket);

$clients = [$serverSocket];

while (true) {
    $read = $clients;
    $write = null;
    $except = null;

    if (socket_select($read, $write, $except, 1) < 1) {
        continue;
    }

    if (in_array($serverSocket, $read)) {
        if (($newClient = socket_accept($serverSocket)) === false) {
            die(socket_strerror(socket_last_error()));
        }

        socket_write($newClient, "\x1b[1;31mMerhaba!\x1b[0m\n");

        $clients[] = $newClient;

        $serverKey = array_search($serverSocket, $read);
        unset($read[$serverKey]);
    }

    foreach ($read as $readSocket) {
        $clientKey = array_search($readSocket, $clients);

        if (($data = socket_read($readSocket, 1024, PHP_NORMAL_READ)) === false) {
            unset($clients[$clientKey]);
            continue;
        }

        $data = trim($data);

        if ($data == 'q' || $data == 'quit') {
            socket_write($readSocket, "\x1b[1;34mBye!\x1b[0m\n");
            socket_close($readSocket);
            unset($clients[$clientKey]);
            continue;
        }

        if ($data != '') {
            foreach ($clients as $clientSocket) {
                if ($clientSocket === $serverSocket) {
                    continue;
                }

                $name = $readSocket == $clientSocket
                    ? "\x1b[1;32mYou\x1b[0m"
                    : "\x1b[1;33mClient " . $clientKey . "\x1b[0m";

                socket_write($clientSocket, "$name: $data\n");
            }
        }
    }
}

socket_shutdown($serverSocket);
