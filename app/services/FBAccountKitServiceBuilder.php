<?php

namespace app\services;

use FB\Accountkit\Client;
use FB\Accountkit\Config;

class FBAccountKitServiceBuilder
{
    public static function build(array $params)
    {
        $config = new Config($params['appId'], $params['appSecret']);

        return new Client($config);
    }
}