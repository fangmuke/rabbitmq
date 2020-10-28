<?php

namespace App\Command;

use App\Producer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class ProducerCommand extends Command
{
    protected static $defaultName = 'rabbitmq:producer';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>' . date('Y-m-d H:i:s') . " 开始生产</info>");

        try {
            $consumer = new Producer();
            $consumer->start();
        } catch (Throwable $throwable) {
            $output->writeln('<error>' . date('Y-m-d H:i:s') . " 生产服务出错，原因：" . $throwable->getMessage() . '</error>');
        }

        $output->writeln('<info>' . date('Y-m-d H:i:s') . " 生产结束</info>>");

        return Command::SUCCESS;
    }
}