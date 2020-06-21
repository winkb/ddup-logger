<?php

namespace Ddup\Logger\Cli;

use Ddup\Part\Libs\OutCli;
use Ddup\Part\Libs\OutCliColor;
use Psr\Log\LoggerInterface;

class CliLogger implements LoggerInterface
{
    public function emergency($message, array $context = array())
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    public function alert($message, array $context = array())
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    public function critical($message, array $context = array())
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    public function error($message, array $context = array())
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    public function warning($message, array $context = array())
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    public function notice($message, array $context = array())
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    public function info($message, array $context = array())
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    public function debug($message, array $context = array())
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    public function log($level, $message, array $context = array())
    {
        $this->writeLog($level, $message, $context);
    }

    private function writeLog($level, $message, array $context = array())
    {
        $ouput = [
            'level'   => $level,
            'message' => $message,
            'context' => $context
        ];

        OutCli::printLn($ouput, $this->getColor($level));
    }

    private function getColor($level)
    {
        switch ($level) {
            case 'info':
                return OutCliColor::green();
            case 'notice':
                return OutCliColor::blue();
            case 'alert':
                return OutCliColor::white();
            case 'debug':
                return OutCliColor::purple();
            case 'critical':
                return OutCliColor::orange();
            case 'emergency':
                return OutCliColor::orange();
            case 'warning':
                return OutCliColor::orange();
            case 'error':
                return OutCliColor::red();
        }

        return OutCliColor::green();
    }

}