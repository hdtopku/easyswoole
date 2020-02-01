<?php
/**
 * Created by PhpStorm.
 * User: lxh
 * Date: 2020-02-01
 * Time: 10:16
 */

namespace App\HttpController;


use App\Service\Jet\JetService;
use EasySwoole\Http\AbstractInterface\Controller;

class Jet extends Controller
{

    function index()
    {
        // TODO: Implement index() method.
        $jetService = new JetService();
//        $result = $jetService->pre_reg();
        $result = $jetService->reg();
        $this->response()->write(json_encode($result));
    }
}