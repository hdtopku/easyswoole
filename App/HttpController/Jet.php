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
    }

    function batch() {
        go(function () {
            $jet = new JetService();
            $jet->batch_reg();
        });
        $this->response()->write(json_encode(['errno'=>'0']));
    }

    function single() {
        $req = $this->request()->getRequestParam();
        if (!array_key_exists('email', $req) ||
            ((array_key_exists('email', $req) and !$req['email']))) {
            $this->response()->write(json_encode(['errno' => '0']));
            return;
        }
        $email = $req['email'];
        try {
            $jet = new JetService();
            $result = $jet->reg($email);
            $this->response()->write(json_encode($result));
        } catch (\Exception $e) {
            $this->response()->write(json_encode($e));
        }
    }
}