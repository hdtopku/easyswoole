<?php
/**
 * Created by PhpStorm.
 * User: lxh
 * Date: 2020-01-30
 * Time: 15:30
 */

namespace App\Service;


use EasySwoole\RedisPool\RedisPool;

class RedisService
{

    function get($key)
    {
        return RedisPool::defer()->get($key);
    }

    function set($key, $val)
    {
        RedisPool::defer()->set($key, $val);
    }
}