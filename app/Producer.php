<?php

namespace App;

class Producer extends Base
{
    public function start()
    {
        $options = $this->options;
        $this->getConnect();
        $this->getChannel();
        $exchange = $this->getExchange();
        while (true) {
//                $delayTime = mt_rand(1000, 6000);
            $delayTime = 5000;
            $message = json_encode(['order_id' => time(), 'delay_time' => $delayTime]);
            $exchange->publish($message, $options['routeKey'], AMQP_NOPARAM, ['headers' => ['x-delay' => $delayTime]]);
            echo " [x] Sent $message\n";
            sleep(rand(0, 1));
        }
    }
}