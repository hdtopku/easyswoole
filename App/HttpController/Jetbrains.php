<?php
/**
 * Created by PhpStorm.
 * User: lxh
 * Date: 2020-01-09
 * Time: 00:46
 */

namespace App\HttpController;


use App\Model\AM\Idea;
use App\Service\RedisService;
use EasySwoole\Http\AbstractInterface\Controller;

class Jetbrains extends Controller
{

    function index()
    {
        $req = $this->request()->getRequestParam();
        if (array_key_exists('q', $req) and $req['q']) {
            $oneMonth = date('Y-m-d H:i:s', strtotime('-1 months'));
            $data = Idea::create()->findOne(['visit_key' => $req['q'],
                'status' => [[0, 1], 'IN'], 'create_time' => [$oneMonth, '>=']]);
            if ($data and $this->isValid($data)) {
                $this->response()->write(json_encode(['errno' => '0', 'update_time' => $data['update_time'], 'count' => $data['visit_count']],
                    JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES));
                return;
            } elseif ($data) {
                $this->response()->write(json_encode(['errno' => '500', 'update_time' => $data['update_time'], 'count' => $data['visit_count']],
                    JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES));
                return;
            }
        } elseif (array_key_exists('k', $req) and $req['k']) {
            $key = $req['k'];
            //redis
            $oneMonth = date('Y-m-d H:i:s', strtotime('-1 months'));
            $data = Idea::create()->findOne(
                ['visit_key' => $key, 'status' => [[0, 1], 'IN'], 'create_time' => [$oneMonth, '>=']]);
            if ($data) {
                if ($this->isValid($data)) {
                    // redis
                    $redis = new RedisService();
                    $d = $redis->get('code');
                    //飞象可用
                    $data['status'] = 1;
                    $data['visit_count'] = $data['visit_count'] + 1;
                    if ($data['update_time'] == $data['create_time']) {
                        $data['update_time'] = date('Y-m-d H:i:s');
                    }
                    Idea::create()->update($data, ['id' => $data['id']]);
                    $this->response()->write(json_encode(
                        ['errno' => '0', 'data' => $d],
                        JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES));
                    return;
                }
            }
        } elseif (array_key_exists('g', $req) and $req['g'] and preg_match("/^-?\d+$/", $req['g'])) {
            $count = $req['g'];
            if ($count) {
                $count = intval($count);
                $limit_count = 50;
                if ($count >= $limit_count) {
                    $count = $limit_count;
                }
                $data = [];
                while ($count > 0) {
                    $count--;
                    $key = $this->getValidCode();
                    Idea::create(['visit_key' => $key])->save();
                    $link = 'http://a.taojingling.cn/j?k=' . $key;
                    array_push($data, $link);
                }
                $this->response()->write(json_encode(
                        ['errno' => '0', 'data' => $data], JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES)
                );
                return;
            }
        }
        $this->response()->write(json_encode(
            ['errno' => '500'],
            JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES));
    }

    function isValid($data)
    {
        $validTime = date('Y-m-d H:i:s', strtotime('-3 minutes'));
        if ($data['status'] == 0 || ($data['status'] == 1 && $data['update_time'] >= $validTime)) {
            return true;
        }
        return false;
    }

    // TODO: Implement index() method.

    function getValidCode()
    {
        while (true) {
            $invitecode = $this->createInvitecode();
            if (!strstr($invitecode, 'qq')) {
                $existCode = Idea::create()->get($invitecode);
                if (!$existCode) {
                    return $invitecode;
                }
            }
        }
    }

    function createInvitecode()
    {
        // 生成字母和数字组成的6位字符串
        $str = range('a', 'z');
        // 去除大写的O，以防止与0混淆
        unset($str[array_search('o', $str)]);
        $arr = array_merge(range(0, 9), $str);
        shuffle($arr);
        $invitecode = '';
        $arr_len = count($arr);
        for ($i = 0; $i < 6; $i++) {
            $rand = mt_rand(0, $arr_len - 1);
            $invitecode .= $arr[$rand];
        }
        return $invitecode;
    }

    function activeCode() {
        $req = $this->request()->getRequestParam();
        $redis = new RedisService();
        if (array_key_exists('code', $req)) {
            $redis->set('code', $req['code']);
        }
        $this->response()->write(json_encode(
                ['errno' => '0', 'data' => $redis->get('code')], JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES)
        );
    }
}