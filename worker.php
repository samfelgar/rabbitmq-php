<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;

require_once __DIR__ . '/vendor/autoload.php';

$connection = new AMQPStreamConnection('192.168.0.110', 5672, 'guest', 'guest');

$channel = $connection->channel();

$channel->exchange_declare('logs', 'fanout');

[$queue] = $channel->queue_declare('logs', false, true, false, false);

$channel->queue_bind($queue, 'logs');

echo " [*] Waiting for logs. To exit press CTRL+C\n";

$callback = function ($msg) {
    echo ' [x] ', $msg->body, "\n";
};

$channel->basic_consume($queue, '', false, false, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();
$connection->close();
