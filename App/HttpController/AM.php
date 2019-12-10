<?php
/**
 * Created by PhpStorm.
 * User: lxh
 * Date: 2019-10-31
 * Time: 10:44
 */

namespace App\HttpController;


use App\Model\AM\Links;
use App\Model\AM\Operator;
use EasySwoole\Http\AbstractInterface\Controller;

class AM extends Controller
{
    function get()
    {
        $data = ['unUsed' => [], 'using' => [], 'used' => [], 'recycle' => [], 'item' => []];
        $req = $this->request()->getRequestParam();
        if (array_key_exists('link', $req)) {
            $req['link'] = trim($req['link']);
            $data['item'] = Links::create()->get(['link' => $req['link']]);
        }
        if (array_key_exists('id', $req)) {
            $data['item'] = Links::create()->get($req['id']);
        }
        if (array_key_exists('status', $req)
            || array_key_exists('operator_id', $req)
            || array_key_exists('link', $req)) {
            if ($data['item']) {
                Links::create()->update($req, ['id' => $data['item']['id']]);
                $data['item'] = Links::create()->get(['link' => $data['item']['link']]);
            } else if (array_key_exists('link', $req) && $req['link']) {
                Links::create($req)->save();
                $data['item'] = Links::create()->get(['link' => $req['link']]);
            } else if (array_key_exists('status', $req) && $req['status'] == 1) {
                $link = Links::create()->get(['status'=>0]);
                var_dump($link);
                $link->update($req, ['id'=>$link['id']]);
            }
        }
        $tenDays = date('Y-m-d H:i:s', strtotime('-10 days'));
        $where = ['update_time' => [$tenDays, '>=']];
        if (array_key_exists('operator_id', $req)) {
            $where['operator_id'] = $req['operator_id'];
        }
        $res = Links::create()->where($where)
            ->join('operator', 'operator.oid=links.operator_id', 'LEFT')
            ->limit(1000)->order('update_time', 'DESC')->all();
        if ($res) {
            foreach ($res as $key => $val) {
                $val['short_link'] = substr($val['link'], 46, 4);
                if ($val->status === 0) {
                    array_push($data['unUsed'], $val);
                } else if ($val->status === 1) {
                    array_push($data['using'], $val);
                } else if ($val->status === 2) {
                    array_push($data['used'], $val);
                } else if ($val->status === 3) {
                    array_push($data['recycle'], $val);
                }
            }
            if ($data['item']) {
                $data['item']['short_link'] = substr($data['item']['link'], 46, 4);
            }
        }
        $data['operator'] = Operator::create()->all();
        $this->response()->write(json_encode(
            ['errno' => '0', 'errmsg' => 'ok', 'data' => $data],
            JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES));
    }

    function update()
    {
        $result = ['errno' => '0', 'errmsg' => 'ok', 'data' => []];
        $req = $this->request()->getRequestParam();
//        if (!array_key_exists('id', $req)) {
//            $result['errno'] = '10011';
//            $result['errmsg'] = 'id is required';
//            $this->response()->write(json_encode($result));
//        }
        $where = [];
        foreach (['status', 'link', 'id', 'operator_id', 'short_link'] as $key => $val) {
            if (array_key_exists($val, $req)) {
                $where[$val] = $req[$val];
            }
        }
        var_dump($where);
        Links::create()->saveAll([$where]);
        $this->response()->write(json_encode($result));
    }


    function index()
    {
        // TODO: Implement index() method.
    }
}