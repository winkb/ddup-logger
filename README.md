# ddup-logger

```php
function getLogger($client){
    switch ($client){
        case "ali":
            return new \Ddup\Logger\Aliyun\AliyunLogger(
                new \Ddup\Logger\Aliyun\Config\AliyunLogStruct(
                    [
                        'access_key_id'     => '',
                        'access_key_secret' => '',
                        'endpoint'          => '',
                        'project'           => '',
                        'store'             => '',
                        'topic'             => ''
                    ]
                ));
        break;
        case "lazy":
            return new \Ddup\Logger\Cli\LazyLogger;
        break;
        default:
            return  new \Ddup\Logger\Cli\CliLogger;
    }   
}
```

```php

getLogger("lazy")->info('不处理', [
    'con' => '登高一呼时才懂，始终在为你心痛'
]);

getLogger("ali")->info('写入阿里云', [
    'con' => '登高一呼时才懂，始终在为你心痛'
]);

getLogger("cli")->info('输出到终端', [
    'con' => '登高一呼时才懂，始终在为你心痛'
]);

```