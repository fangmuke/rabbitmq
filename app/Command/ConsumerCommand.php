<?php

namespace App\Command;

use App\Consumer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class ConsumerCommand extends Command
{
    protected static $defaultName = 'rabbitmq:consumer';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>' . date('Y-m-d H:i:s') . " 开始消费</info>");

        try {
            $consumer = new Consumer();
            $consumer->start();
        } catch (Throwable $throwable) {
            $output->writeln('<error>' . date('Y-m-d H:i:s') . " 消费服务出错，原因：" . $throwable->getMessage() . '</error>');
        }

        $output->writeln('<info>' . date('Y-m-d H:i:s') . " 消费结束</info>>");

        return Command::SUCCESS;
    }
}