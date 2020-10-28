<?php

namespace App;

use AMQPChannel;
use AMQPChannelException;
use AMQPConnection;
use AMQPConnectionException;
use AMQPExchange;
use AMQPExchangeException;
use AMQPQueue;
use AMQPQueueException;
use Exception;

class Base
{
    protected $hosts = [];
    protected $options = [];
    protected $connection = null;
    protected $channel = null;
    protected $config = [];

    public function __construct($hosts = null, $options = null)
    {
        $this->getConfig();
        $this->hosts = $hosts ? $hosts : $this->getDefaultHosts();

        $this->options = $options ? $options : $this->getDefaultOptions();
    }

    /**
     * Get RabbitMQ Connect
     *
     * @return AMQPConnection|null
     * @throws AMQPConnectionException
     * @throws Exception
     */
    public function getConnect()
    {
        if ($this->connection) {
            return $this->connection;
        }

        $connection = new AMQPConnection($this->hosts);
        $connection->connect();

        if (!$connection->isConnected()) {
            throw new Exception('Connection failed');
        }

        $this->connection = $connection;

        return $this->connection;
    }

    /**
     * Get Channel
     * @param null|AMQPConnection $connection
     * @return AMQPChannel|null
     * @throws Exception
     */

    public function getChannel($connection = null)
    {
        if ($this->channel) {
            return $this->channel;
        }

        $connection = $connection ? $connection : $this->connection;
        $channel = new AMQPChannel($connection);

        if (!$channel->isConnected()) {
            throw  new Exception("Connection through channel failed");
        }

        $this->channel = $channel;

        return $channel;
    }

    /**
     *
     * Set Exchange
     *
     * @param AMQPChannel $channel
     * @return AMQPExchange
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     * @throws AMQPExchangeException
     * @throws Exception
     */
    public function getExchange($channel = null)
    {
        $options = $this->options;
        $channel = $channel ? $channel : $this->getChannel();
        $exchange = new AMQPExchange($channel);
        $exchange->setName($options['exchangeName']);
        $exchange->setType($options['exchangeType']);
        $exchange->setArgument($options['exchangeArg']['key'], $options['exchangeArg']['value']);
        $exchange->declareExchange();

        return $exchange;
    }

    /**
     *
     * Set Exchange
     *
     * @param AMQPChannel $channel
     * @return AMQPQueue
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     * @throws AMQPQueueException
     * @throws Exception
     */
    public function getQueue($channel = null)
    {
        $options = $this->options;
        $channel = $channel ? $channel : $this->getChannel();
        $queue = new AMQPQueue($channel);
        $queue->setName($options['queueName']);
        $queue->setFlags($options['queueFlags']);
        $queue->declareQueue();

        $queue->bind($options['exchangeName'], $options['routeKey']);

        return $queue;
    }

    public function getConfig()
    {
        $config = require 'config.php';
        if (!is_array($config)) {
            return [];
        }

        $this->config = $config;

        return $config;
    }

    /**
     * @return array[]
     */
    protected function getDefaultHosts()
    {
        return isset($this->config['hosts']) ? $this->config['hosts'] : [];
    }

    /**
     * @return string[]
     */
    protected function getDefaultOptions()
    {
        return isset($this->config['options']) ? $this->config['options'] : [];
    }
}