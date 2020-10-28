<?php

namespace App;

class Consumer extends Base
{
    public function start()
    {
        $this->getConnect();
        $this->getChannel();
        $this->getExchange();
        $queue = $this->getQueue();
        while (true) {
            $message = $queue->get();
            if (!empty($message)) {
                echo '接收时间：' . time() . PHP_EOL;
                echo '接收内容：' . $message->getBody() . PHP_EOL;
                $queue->ack($message->getDeliveryTag());
            }
        }
    }
}