<?php
/**
 * Old School Terminal Chat Server.
 *
 * @see https://en.wikipedia.org/wiki/ANSI_escape_code#Colors
 */

/**
 * 1. Create a socket.
 * 2. Bind an IP address and port number.
 * 3. Listen
 */
$serverSocket = stream_socket_server('tcp://127.0.0.1:3333', $errorNumber, $errorMessage);

if ($serverSocket === false) {
    die($errorMessage);
}

/**
 * Switch to non-blocking mode.
 */
stream_set_blocking($serverSocket, 0);

$clients = [$serverSocket];

while (true) {
    $read = $clients;
    $write = null;
    $except = null;

    /**
     * 4. Is something happening?
     */
    if (stream_select($read, $write, $except, 1) < 1) {
        continue;
    }

    /**
     * 5. Accept if there is a new connection.
     */
    if (in_array($serverSocket, $read)) {
        if (($newClient = stream_socket_accept($serverSocket)) === false) {
            die('Accept failed');
        }

        fwrite($newClient, "\x1b[1;31mMerhaba!\x1b[0m\n");

        $clients[] = $newClient;

        $serverKey = array_search($serverSocket, $read);
        unset($read[$serverKey]);
    }

    /**
     * 6. Read / Write / Close
     */
    foreach ($read as $readSocket) {
        $clientKey = array_search($readSocket, $clients);

        if (($data = fread($readSocket, 1024)) === false) {
            unset($clients[$clientKey]);
            continue;
        }

        $data = trim($data);

        if ($data == 'q' || $data == 'quit') {
            fwrite($readSocket, "\x1b[1;34mBye!\x1b[0m\n");
            fclose($readSocket);
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

                fwrite($clientSocket, "$name: $data\n");
            }
        }
    }
}

fclose($serverSocket);
