<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new \Symfony\Component\Console\Application('RabbitMQ tryout');

$app->add(new \Martiis\RabbitMQTryout\Command\Worker2Command());
$app->add(new \Martiis\RabbitMQTryout\Command\WorkerCommand());
$app->add(new \Martiis\RabbitMQTryout\Command\SenderCommand());

$app->run();
