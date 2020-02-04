<?php
/**
 * Created by PhpStorm.
 * User: lxh
 * Date: 2020-02-04
 * Time: 22:56
 */

namespace App\HttpController;


use EasySwoole\Http\AbstractInterface\Controller;

class Test extends Controller
{

    function index()
    {
        // TODO: Implement index() method.
        $arr = [0, 1, 2];
        foreach ($arr as $key => $value) {
            $arr[$key] = $value + 1;
        }
        $this->response()->write(json_encode($arr));
    }
}