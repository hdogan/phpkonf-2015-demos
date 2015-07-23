<?php
/**
 * HTTP server with PHP streams.
 */
$serverSocket = stream_socket_server('tcp://127.0.0.1:8080', $errorNumber, $errorMessage);

if ($serverSocket === false) {
    die($errorMessage);
}

$htmlFile = file_get_contents('http.html');

for (;;) {
    $clientSocket = @stream_socket_accept($serverSocket);

    if ($clientSocket) {
        $clientIp = stream_socket_get_name($clientSocket, true);
        $clientIp = substr($clientIp, 0, strrpos($clientIp, ':'));
        $response = str_replace('IP_ADDRESS', $clientIp, $htmlFile);

        fwrite($clientSocket, "HTTP/1.1 200 OK\n");
        fwrite($clientSocket, "Content-Type: text/html; charset=utf-8\n");
        fwrite($clientSocket, "Connection: close\n\n");
        fwrite($clientSocket, $response);
        fclose($clientSocket);
    }
}

fclose($serverSocket);
