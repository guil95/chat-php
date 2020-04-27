<?php
require '../vendor/autoload.php';

use React\Socket\ConnectionInterface;

$pool = new \App\ConnectionPoll(new \App\Logger());
$sender = new \App\Sender($pool);

$loop = React\EventLoop\Factory::create();
$socket = new React\Socket\Server('127.0.0.1:8080', $loop);

$socket->on('connection', function(ConnectionInterface $connection) use ($pool, $sender){

    $connection->write('Write your name:'.PHP_EOL);

    $connection->on('data', function($data) use ($connection, $pool, $sender){

        $user = $pool->get($connection);

        if(!$user) {
            $pool->add($connection, $data);
            $sender->all(sprintf('Usuário %s conectado', $pool->get($connection)));
            return;
        }

        $message = str_replace(["\n", "\r"], "", $data);

        if ($message == '\q') {
            $connection->close();
            return;
        }

        $sender->all(sprintf('%s: %s', $user, $message));
    });

    $connection->on('close', function() use ($connection, $pool, $sender){
        $sender->all(sprintf('User %s left.', $pool->get($connection)));
        $pool->close($connection);
    });
});

echo "Listening on {$socket->getAddress()}\n";

$loop->run();