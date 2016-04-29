<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new \Symfony\Component\Console\Application('RabbitMQ tryout');

$app->add(new \Martiis\RabbitMQTryout\Command\DirectWorkerCommand());
$app->add(new \Martiis\RabbitMQTryout\Command\DirectSenderCommand());

$app->run();
