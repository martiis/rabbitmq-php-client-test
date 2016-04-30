<?php

namespace Martiis\RabbitMQTryout\Command;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SenderCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('sender')
            ->addArgument('message', InputArgument::REQUIRED)
            ->addArgument('routing_key', InputArgument::OPTIONAL, '', '');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->exchange_declare('gamma', 'direct', false, false, true);
        $channel->basic_publish(
            new AMQPMessage($input->getArgument('message')),
            'gamma',
            $input->getArgument('routing_key')
        );

        $channel->close();
        $connection->close();
    }
}