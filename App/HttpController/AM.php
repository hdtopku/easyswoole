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
                $data['item'] = Links::create()
                    ->join('operator', 'operator.oid=links.operator_id', 'LEFT')
                    ->get(['link' => $data['item']['link']]);
            } else if (array_key_exists('link', $req) && $req['link']) {
                Links::create($req)->save();
                $data['item'] = Links::create()
                    ->join('operator', 'operator.oid=links.operator_id', 'LEFT')
                    ->get(['link' => $req['link']]);
            } else if (array_key_exists('status', $req)
                && ($req['status'] == 1 || $req['status'] == 2)) {
                $link = Links::create()->get(['status' => 0]);
                $link->update($req, ['id' => $link['id']]);
                $data['item'] = Links::create()->get($link['id']);
            }
        }
        $tenDays = date('Y-m-d H:i:s', strtotime('-10 days'));
        $where = ['update_time' => [$tenDays, '>=']];
        if (array_key_exists('operator_id', $req)) {
            $where['operator_id'] = $req['operator_id'];
        }
        $res = Links::create()->where($where)->where('status', 0, '!=')
            ->join('operator', 'operator.oid=links.operator_id', 'LEFT')
            ->limit(1000)->order('update_time', 'DESC')->findAll();
        $unUsed = Links::create()->where('status', 0, '=')
            ->join('operator', 'operator.oid=links.operator_id', 'LEFT')
            ->limit(1000)->order('update_time', 'DESC')->findAll();
        if ($unUsed) {
            $res = array_merge($res, $unUsed);
        }
        if ($res) {
            foreach ($res as $key => $val) {
                $val['short_link'] = substr($val['link'], 46, 4);
                if ($val['status'] === 0) {
                    array_push($data['unUsed'], $val);
                } else if ($val['status'] === 1) {
                    array_push($data['using'], $val);
                } else if ($val['status'] === 2) {
                    array_push($data['used'], array_splice($val, 0, 50));
                } else if ($val['status'] === 3) {
                    array_push($data['recycle'], $val);
                }
            }
            if ($data['item']) {
                $data['item']['short_link'] = substr($data['item']['link'], 46, 4);
            }
        }
        $data['operator'] = Operator::create()->all();
        $data['usedLength'] = Links::create()
            ->where('status', 2)
            ->where('update_time', date("Y-m-d"), '>=')->count();
        $data['yesterdayUsedLength'] = Links::create()
            ->where('status', 2)
            ->where('update_time', date("Y-m-d"), '<')
            ->where('update_time', date("Y-m-d", strtotime("-1 day")), '>=')
            ->count();
        if (array_key_exists('used', $data)) {
            $data['allUsedLength'] = count($data['used']);
            $data['used'] = array_splice($data['used'], 0, 6);
        } else {
            $data['allUsedLength'] = 0;
        }
        $this->response()->write(json_encode(
            ['errno' => '0', 'errmsg' => 'ok', 'data' => $data],
            JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES));
    }


    function index()
    {
        // TODO: Implement index() method.
    }
}