<?php

namespace App;

final class Sender
{
    private ConnectionPoll $poll;

    /**
     * Sender constructor.
     * @param ConnectionPoll $poll
     */
    public function __construct(ConnectionPoll $poll)
    {
        $this->poll = $poll;
    }

    /**
     * @param ConnectionPoll $poll
     * @param string $message
     */
    public function all(string $message)
    {
        foreach ($this->poll->getAll() as $conn) {
            $conn->write($message.PHP_EOL);
        }
    }
}