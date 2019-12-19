<?php
/**
 * Created by PhpStorm.
 * User: lxh
 * Date: 2019-12-17
 * Time: 16:34
 */

namespace App\HttpController;


class RandomData
{
    function getRandomNumberArray($total, $times, $min, $max)
    {
        $data = array();
        if ($min * $times > $total) {
            return array();
        }

        if ($max * $times < $total) {
            return array();
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
            $data[] = $k;
            echo $kmix, $kmax, $kAvg, $kDis, $r, $k, $total;
        }
        return $data;
    }
}