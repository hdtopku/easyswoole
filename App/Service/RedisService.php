<?php
/**
 * Created by PhpStorm.
 * User: lxh
 * Date: 2020-01-30
 * Time: 15:30
 */

namespace App\Service;


use EasySwoole\Http\WebService;

class RedisService extends WebService
{
    function get_cli()
    {
        $redis = new \EasySwoole\Redis\Redis(new \EasySwoole\Redis\Config\RedisConfig([
            'host' => '106.14.82.34',
            'port' => '6379',
            'auth' => 'wz95ctxb3hvxezu57ko',
            'serialize' => \EasySwoole\Redis\Config\RedisConfig::SERIALIZE_NONE
        ]));
        return $redis;
    }


    function get($key) {
        return $this->get_cli()->get($key);
    }

    function set($key, $val) {
        $this->get_cli()->set($key, $val);
    }
}