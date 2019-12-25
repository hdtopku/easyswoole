<?php
/**
 * Created by PhpStorm.
 * User: lxh
 * Date: 2019-12-17
 * Time: 16:34
 */

namespace App\HttpController;


use EasySwoole\Http\AbstractInterface\Controller;

class RandomData extends Controller
{
    function get($total = 1005, $avg = 45, $min = 5, $max = 40, $times = 49, $minTimes=16, $step=10)
    {
        if ($min * $times > $total) {
            return array();
        }
        if ($max * $times < $total) {
            return array();
        }
        $arr = array();
        $difArr = array();
        $maxArr = array();
        $minArr = array();
        for ($i = 0; $i < $times; $i++) {
            $minArr[] = 0;
            $maxArr[] = 0;
        }
        while ($times >= 1) {
            $times--;
            $kmix = max($min, $total - $times * $max);
            $kmax = min($max, $total - $times * $min);
            $kAvg = $total / ($times + 1);
            //获取最大值和最小值的距离之间的最小值
            $kDis = min($kAvg - $kmix, $kmax - $kAvg);
            //获取0到1之间的随机数与距离最小值相乘得出浮动区间，这使得浮动区间不会超出范围
            $r = ((float)(rand(1, 10000) / 10000) - 0.5) * $kDis * 2;
            $k = round($kAvg + $r);
            $total -= $k;
            $arr[] = $k;
            $difArr[] = ($avg - $k);
        }
        while ($minTimes >= 1) {
            $minTimes--;
            $pos = $this->getMinPos($difArr);
            $minArr[$pos] = $difArr[$pos] * $step;
            unset($difArr[$pos]);
        }
        foreach ($difArr as $key => $value) {
            $maxArr[$key] = $value * $step;
        }
        foreach ($arr as $key => $value) {
            $arr[$key] = $value * 10;
        }
        $data = ['arr' => $arr, 'minArr'=> $minArr, 'maxArr'=>$maxArr];
        return $data;
    }

    function getMinPos($arr) {
        $pos = 0;
        $min = 9999999999;
        foreach ($arr as $key => $value) {
            if ($value < $min) {
                $min = $value;
                $pos = $key;
            }
        }
        return $pos;
    }

    function index()
    {
        // TODO: Implement index() method.
        $req = $this->request()->getRequestParam();
        $r = ['total'=>10050, 'avg'=>450, 'min'=>50, 'max'=>400];
        $step = 10;
        foreach ($r as $key => $value) {
            if (array_key_exists($key, $req)) {
                $r[$key] = (int) $req[$key] / $step;
            }
        }
        $data = $this->get($r['total'], $r['avg'], $r['min'], $r['max']);
        $res = ['errno' => '0', 'errmsg' => 'ok', 'data' => $data];
        $this->response()->write(json_encode($res));
    }
}