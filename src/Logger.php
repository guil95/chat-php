<?php


namespace App;


use Psr\Log\AbstractLogger;

class Logger extends AbstractLogger
{
    /**
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        echo sprintf("Level: %s \n Message: %s \n Context: %s", $level, $message, json_encode($context));
    }
}