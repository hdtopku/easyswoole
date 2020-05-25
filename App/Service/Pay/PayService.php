<?php
/**
 * Created by PhpStorm.
 * User: lxh
 * Date: 2020-02-16
 * Time: 10:53
 */

namespace App\Service\Pay;


use EasySwoole\Http\WebService;

class PayService extends WebService
{
//    global $private_key = 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDHMZ/gvFr4YNX62GubLGSyN5lkt6VrvfxLUrbPKh8amLO8UoGS5lTd4k4SbGpbFqgDneb96d5BI2d44WA6eE8zivqql20OTSVTxsfk6MpWYwIPrlwI6e7t9MaXU7ryOWliUXh1TrrPlUg6FBB1XZ6aUtgn7w1DYy+iw8nAWG6C5FqSxO4rCZjWgPtGf0Yk5YewM8KwiAxGbCI3vIzekE09yYxeiE4ivR1n2jTdB2+n9fKT578i65x65vzw0kX6nHPGOD/W10BfbJjz7WrAMj6nP4NvpO6L7Ap19G5h45Zt64jGOSucvyL3nWuFC/2r1jv4biRit9CVyZ53pZ7kqli/AgMBAAECggEBALvseF4otVZg3V9zsElMH4/3XlMj4v971LsnROq7XW7VI7SGzlHN1cEjkO5WtBxNiqMm5FcmvZUMlsD3N7bR7D6/Xm90vuFgLNgV0F6ItOO9MqOippeOQ+jhJj58MwH04hRssk1RwnK27tZEOabQdSI5CE7ce9HYkIdpWTyK8W+v38Ho3UO49L3MSrTzDAtdrqGdEjLWgSgDJ3D0DVzG3Y899VeGH1TndMIf91nlWUJ2dXtpEc94dr7nPKkVb9Mkugqo4wu5gA0+LSANfo6H5Cuw8Fhi86UxLQgin98J3NROz7UGVSF7+ys0oLQeusJzug458yo0CNGS561REIpWa1ECgYEA62yunxFE9hVXkoffih8TMqRT/o4qBu9/sXnp9muPfuJelduy7xoP43Mc53R58JmyNkSi3Ch0X5Z0F/XVgH+vA5AQMcfiyWgYzoAK4fs9mZWDBCq2zZ5Nxu7hgppVYCdE23y+g3eFzq8gxD+WoOiz3zR9F1DstFvxsLYE/ztrF5kCgYEA2JpT5+Bzb3jPtVReWkTj3fYs2paaeLLcbWmBIIvi5fDPvCdOjwenpG8BBplWNlzSkrgnGWxPfRasLN6oc5RjlMp0kDtQQE0BNk5thLc7egwt66ga7qzIm3UF4OmHbjVpZvyqUSFjsQ63TrMnXoEu32nVOtAFgMH7kIduKRKhShcCgYBmryPHG7gxgYON1RfKXd14xDYinI12MvZGSb+jjKytEj1hLc9w/LQbZ/Ueey5IpDEX/H3wevCvVKdUYBR8lLSYYDjADcg8Qtw3DaAxiu0GVTEtGxhavQZ9k2nnFjvQ0a/18AYEv5gsWzR2hKnFi1oRLAq3pcNos4XKpsmtLZR7WQKBgGW8ZvicZdvPxM+IpyCDBvw0KnEpF7jlWb9SNMJSseoKp6faVn27vLPxJ2wwDVxPUOB0+nhodRVt1sTkd/6BjhxO010DXvg0swMM50fUwGzc15y+QpgkM71Aw9gWF/RdxfoHZv/gTPDW1qZyY2VyC9AH541OM6d8dWo3EjjcdUFVAoGAbTIl88SfwwtFVRoUn1SL9Gk87c5kaJwKhrHvG/Grh6Y1VPb8oQZMnH3fZnYrX4UOISclUczSpNTvuaZJJ2XiMwz8bWvEUKT258wQ+ko7EUN0YNYJ6/7UlpmQ8rPtwk12JdfRtId0dgBTVIL1ri9XBEaDHefAfUpEnyIJsgOkQQY=';

    public function ali_pay() {
        $aliConfig = new \EasySwoole\Pay\AliPay\Config();
        $aliConfig->setGateWay(\EasySwoole\Pay\AliPay\GateWay::NORMAL);
        $aliConfig->setAppId('2021001131604676');
        $aliConfig->setPublicKey('阿里公钥');
        $aliConfig->setPrivateKey('阿里私钥');

        $pay = new \EasySwoole\Pay\Pay();

        $order = new \EasySwoole\Pay\AliPay\RequestBean\Wap();
        $order->setSubject('测试');
        $order->setOutTradeNo(time().'123456');
        $order->setTotalAmount('0.01');

        $res = $pay->aliPay($aliConfig)->wap($order);
        var_dump($res->toArray());

        $html = buildPayHtml(\EasySwoole\Pay\AliPay\GateWay::NORMAL,$res->toArray());
        file_put_contents('test.html',$html);
    }
}