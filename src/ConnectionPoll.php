<?php

namespace App;

use Psr\Log\LoggerInterface;
use React\Socket\ConnectionInterface;

final class ConnectionPoll
{
    private \SplObjectStorage $users;

    private LoggerInterface $logger;

    /**
     * ConnectionPoll constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->users = new \SplObjectStorage();
        $this->logger = $logger;
    }

    /**
     * @param ConnectionInterface $connection
     * @return array|string
     */
    public function get(ConnectionInterface $connection)
    {
        try {
            return $this->users->offsetGet($connection);
        } catch (\UnexpectedValueException $e) {
            return [];
        }
    }

    /**
     * @param ConnectionInterface $connection
     * @param string $name
     */
    public function add(ConnectionInterface $connection, string $name)
    {
        $this->logger->info(
            sprintf(
                'Nova conexão, %s - %s',
                $name,
                date('d/m/Y H:i:s')
            )
        );

        $this->users->offsetSet($connection, $this->formatName($name));
    }

    /**
     * @return \SplObjectStorage
     */
    public function getAll()
    {
        return $this->users;
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function close(ConnectionInterface $connection)
    {
        $this->logger->info(
            sprintf(
                'Usuário %s saiu - %s',
                $this->get($connection),
                date('d/m/Y H:i:s')
            )
        );
        $this->users->offsetUnset($connection);
    }

    /**
     * @param string $name
     * @return string|string[]
     */
    private function formatName(string $name)
    {
        return str_replace(["\n", "\r"], "", $name);
    }
}