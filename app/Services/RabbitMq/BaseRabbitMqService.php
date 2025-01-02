<?php

namespace App\Services\RabbitMq;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

abstract class BaseRabbitMqService
{
    protected $connection;
    protected $channel;
    protected $queue = [];

    public function __construct()
    {
        $this->connection = $this->getConnection();
        $this->channel = $this->connection->channel();
        register_shutdown_function([$this, "close"]);
    }

    abstract function getConnection(): AMQPStreamConnection;

    protected function publishMessage($queue, $message)
    {
        $this->checkQueueExists($queue);
        $message = new AMQPMessage(json_encode($message), ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
        $this->channel->basic_publish($message, routing_key: $queue);
    }

    private function checkQueueExists($queue)
    {
        if (!in_array($queue, $this->queue)) {
            $this->channel->queue_declare($queue, false, true, false, false);
            array_push($this->queue, $queue);
        }
    }

    private function close()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
