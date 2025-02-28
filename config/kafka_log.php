<?php

return [
    // topic name
    'topic'         => env('LOG_KAFKA_TOPIC'),
    // kafka brokers - "10.0.0.1,10.0.0.2"
    'brokers'       => env('LOG_KAFKA_BROKER_LIST'),
    // timeout - ms
    'flush_timeout' => env('KAFKA_LOG_FLUSH_TIMEOUT', 100)
];
