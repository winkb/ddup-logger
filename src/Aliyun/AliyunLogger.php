<?php

namespace Ddup\Logger\Aliyun;

use Aliyun\SLS\Client;
use Aliyun\SLS\Models\LogItem;
use Aliyun\SLS\Requests\PutLogsRequest;
use Ddup\Logger\Aliyun\Config\AliyunLogStruct;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Arr;
use Psr\Log\LoggerInterface as PsrLoggerInterface;

class AliyunLogger implements PsrLoggerInterface
{

    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @var Client
     */
    private $logger;

    private $config;

    private $topic;

    public function __construct(AliyunLogStruct $config, Dispatcher $dispatcher = null)
    {
        if (isset($dispatcher)) {
            $this->dispatcher = $dispatcher;
        }

        $this->logger = new Client($config->endpoint, $config->access_key_id, $config->access_key_secret);
        $this->topic  = $config->topic;
        $this->config = $config;
    }

    public function alert($message, array $context = [])
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }

    public function critical($message, array $context = [])
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }

    public function error($message, array $context = [])
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }

    public function warning($message, array $context = [])
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }

    public function notice($message, array $context = [])
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }

    public function info($message, array $context = [])
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }

    public function debug($message, array $context = [])
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }

    public function log($level, $message, array $context = [])
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }

    public function emergency($message, array $context = array())
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }

    protected function writeLog($level, $message, $context)
    {
        $data = [
            'level'   => $level,
            'message' => $message,
            'context' => $this->formatContext($context),
        ];

        $logItem = new LogItem($data);
        $request = new PutLogsRequest($this->config->project, $this->config->store, $this->topic, null, [$logItem]);

        $response = $this->logger->putLogs($request);

        $this->fireLogEvent($level, $message = $this->formatMessage($message), $context);

        return Arr::get($response->getAllHeaders(), '_info.http_code') === 200;
    }

    public function setTopic($topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Fires a log event.
     *
     * @param  string $level
     * @param  string $message
     * @param  array $context
     *
     */
    protected function fireLogEvent($level, $message, array $context = [])
    {

        if (!isset($this->dispatcher)) {
            return;
        }

        // If the event dispatcher is set, we will pass along the parameters to the
        // log listeners. These are useful for building profilers or other tools
        // that aggregate all of the log messages for a given "request" cycle.

        $this->dispatcher->dispatch('illuminate.log', compact('level', 'message', 'context'));
    }

    protected function formatContext($context)
    {
        if ($context instanceof Jsonable) {
            return $context->toJson();
        } elseif ($context instanceof Arrayable) {
            $context = $context->toArray();
        }

        return json_encode($context, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Format the parameters for the logger.
     *
     * @param  mixed $message
     *
     * @return mixed
     */
    protected function formatMessage($message)
    {
        if (is_array($message)) {
            return var_export($message, true);
        } elseif ($message instanceof Jsonable) {
            return $message->toJson();
        } elseif ($message instanceof Arrayable) {
            return var_export($message->toArray(), true);
        }

        return $message;
    }
}