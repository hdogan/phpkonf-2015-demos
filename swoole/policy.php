<?php
/**
 * Flash Socket Policy Server
 *
 * @see http://www.adobe.com/devnet/flashplayer/articles/socket_policy_files.html
 */
$policyServer = new swoole_server('127.0.0.1', 843);
$policyFile = file_get_contents('policy.xml');

$policyServer->on('receive', function($server, $client, $from_id, $data) use ($policyFile) {
    $data = trim($data);

    if ($data == '<policy-file-request/>') {
        $server->send($client, $policyFile);
    }

    $server->close($client);
});

$policyServer->start();
