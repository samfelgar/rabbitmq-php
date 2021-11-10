<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once 'vendor/autoload.php';

$connection = new AMQPStreamConnection('192.168.0.110', 5672, 'guest', 'guest');

$channel = $connection->channel();

$channel->exchange_declare('logs', 'fanout');

$data = implode(' ', array_slice($argv, 1));

if (empty($data)) {
    $data = 'info: Hello World!';
}

$message = new AMQPMessage($data, [
    'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
]);

$channel->basic_publish($message, 'logs');

echo "Log sent [$data]" . PHP_EOL;

$channel->close();
$connection->close();