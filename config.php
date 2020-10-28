<?php
return [
    'hosts' => [
        [
            'host' => 'localhost',
            'port' => 5672,
            'user' => 'guest',
            'password' => 'guest',
            'vhost' => '/'
        ]
    ],
    'options' => [
        'queueName' => 'delayed_queue_test',
        'queueFlags' => AMQP_DURABLE,
        'routeKey' => 'delayed_route_test',
        'exchangeName' => 'delayed_exchange_test',
        'exchangeType' => 'x-delayed-message',
        'exchangeArg' => ['key' => 'x-delayed-type', 'value' => 'fanout'],
    ]
];