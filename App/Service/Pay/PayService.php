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
    public function ali_pay() {
        $aliConfig = new \EasySwoole\Pay\AliPay\Config();
        $aliConfig->setGateWay(\EasySwoole\Pay\AliPay\GateWay::NORMAL);
        $aliConfig->setAppId('2017082000295641');
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