<?php
/**
 * Created by PhpStorm.
 * User: lxh
 * Date: 2020-01-31
 * Time: 21:11
 */

namespace App\Service;


use EasySwoole\Http\WebService;

class HttpService extends WebService
{
    function request($url, $data, $method='GET')
    {
        $postdata = http_build_query(
            $data
        );

        $opts = array('http' =>
            array(
                'method' => $method,
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);
        return $result;
    }

    function get($url, $data) {
        return $this->request($url, $data, 'GET');
    }

    function post($url, $data) {
        return $this->request($url, $data, 'POST');
    }


}