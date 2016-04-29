<?php

namespace Martiis\RabbitMQTryout\Command;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DirectWorkerCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('direct:worker');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->exchange_declare('testing_direct', 'direct', false, false, false);
        list($queueName, ,) = $channel->queue_declare('', false, false, true, false);
        $channel->queue_bind($queueName, 'testing_direct');

        $io = new SymfonyStyle($input, $output);

        $callback = function (AMQPMessage $message) use ($io) {
            $io->block($message->getBody(), 'x', 'info');
        };

        $channel->basic_consume($queueName, '', false, true, false, false, $callback);
        $io->block('Bring it on. To exit press CTRL+C', '*', 'comment');

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}