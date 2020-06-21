<?php

namespace Ddup\Logger\Aliyun\Config;


use Ddup\Part\Struct\StructReadable;

class AliyunLogStruct extends StructReadable
{
    public $access_key_id;
    public $access_key_secret;
    public $endpoint;
    public $project;
    public $store;
    public $topic;
}