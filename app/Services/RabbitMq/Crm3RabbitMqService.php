<?php

namespace App\Services\RabbitMq;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class Crm3RabbitMqService extends BaseRabbitMqService
{
    const QUEUE_NAME_CCM = 'external.ccms.crm3.integrate';

    function getConnection(): AMQPStreamConnection
    {
        $connection = reset(config('queue.connections.rabbitmq_crm3')['hosts']);
        return new AMQPStreamConnection(
            $connection['host'],
            $connection['port'],
            $connection['user'],
            $connection['password'],
            $connection['vhost']
        );
    }

    public function publishMessageCcm($subject, $data)
    {
        $message = [
            'subject' => $subject,
            'data' => $data
        ];
        $this->publishMessage(self::QUEUE_NAME_CCM, $message);
    }
}
