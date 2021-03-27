<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/8/15
 * Time: 上午10:39
 */

namespace App\HttpController;


use EasySwoole\Http\AbstractInterface\AbstractRouter;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use FastRoute\RouteCollector;

class Router extends AbstractRouter
{
    function initialize(RouteCollector $routeCollector)
    {
        $routeCollector->get('/user', '/index.html');
        $routeCollector->get('/hdanewom', '/AM/get');
        $routeCollector->get('/hdanewom/rdata', '/RandomData');
        $routeCollector->get('/hdanewom/j', '/Jetbrains');
        $routeCollector->addRoute(['GET', 'POST', 'OPTIONS'], '/sanfsale9831sf/jc', '/Jetbrains/activeCode');
        $routeCollector->addRoute(['GET', 'POST', 'OPTIONS'], '/sanfsale9831sf/ja', '/Jetbrains/account');

        $routeCollector->get('/hdanewom/jt', '/Jet');
        $routeCollector->get('/hdanewom/jb', '/Jet/batch');

        $routeCollector->get('/test', '/Test');

        $routeCollector->get('/', function (Request $request, Response $response) {
            $response->write('this router index');
        });
        $routeCollector->get('/testa', function (Request $request, Response $response) {
            $response->write('this router test');
            return '/a';//重新定位到/a方法
        });
        $routeCollector->get('/user/{id:\d+}', function (Request $request, Response $response) {
            $response->write("this is router user ,your id is {$request->getQueryParam('id')}");//获取到路由匹配的id
            return false;//不再往下请求,结束此次响应
        });
    }
}