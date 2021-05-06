<?php
/**
 * Created by PhpStorm.
 * User: lxh
 * Date: 2021-05-06
 * Time: 11:35
 */

namespace App\HttpController;


use App\Model\AM\LongShortLinkModel;
use EasySwoole\Http\AbstractInterface\Controller;

class LongShortLink extends Controller
{

    function index()
    {
        // TODO: Implement index() method.
        $short_long_link = LongShortLinkModel::create()->findAll();
        $this->response()->write(json_encode(
            ['errno' => '200', 'data' => $short_long_link],
            JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES));
    }
}