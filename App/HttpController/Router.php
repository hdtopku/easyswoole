<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/8/15
 * Time: 上午10:39
 */

namespace App\HttpController;


use EasySwoole\Http\AbstractInterface\AbstractRouter;
use FastRoute\RouteCollector;

class Router extends AbstractRouter
{
    function initialize(RouteCollector $routeCollector)
    {
        $routeCollector->post('/am/video/parse', '/VideoController/parse');
        $routeCollector->all('/am/nfsmabeawn', '/AM/get');
        $routeCollector->all('/am/j', '/Jetbrains');
        $routeCollector->addRoute(['GET', 'POST', 'OPTIONS'], '/am/jc', '/Jetbrains/activeCode');
        $routeCollector->addRoute(['GET', 'POST', 'OPTIONS'], '/am/ja', '/Jetbrains/account');

        $routeCollector->all('/am/jt', '/Jet');
        $routeCollector->all('/am/jb', '/Jet/batch');
    }
}