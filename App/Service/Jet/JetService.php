<?php
/**
 * Created by PhpStorm.
 * User: lxh
 * Date: 2020-01-31
 * Time: 21:23
 */

namespace App\Service\Jet;


use App\Model\Jet\JetAccount;
use App\Service\RedisService;
use EasySwoole\Http\WebService;

class JetService extends WebService
{

    function get_mails()
    {
        $redis = new RedisService();
        $words = $redis->get('words');
        return json_decode($words);
    }

    function batch_reg()
    {
        $need_reg_mails = $this->get_need_reg_mails();
        foreach ($need_reg_mails as $key => $val) {
            if (strlen($val) > 3) {
                try {
                    $this->reg($val . '@pku.edu.cn');
                    sleep(rand(3, 8));
                    continue;
                } catch (\Exception $e) {
                    $account = substr($val, 0, -1);
                    JetAccount::create()->update(['status' => 1], ['username' => $account]);
                    sleep(rand(2, 5));
                }
            }
        }
    }

    function get_need_reg_mails()
    {
        $words = $this->get_mails();
        $need_reg_mails = [];
        foreach ($words as $key => $val) {
            $account = substr($val, 0, -1);
            $jet = JetAccount::create()->get(['username' => $account]);
            if (!$jet) {
                $jet = JetAccount::create(
                    ['username' => $account, 'password' => 'Crack168', 'status' => -1]
                )->save();
                if ($jet) {
                    array_push($need_reg_mails, $val);
                }
            }
        }
        return $need_reg_mails;
    }

    function pre_reg()
    {
        $url = 'https://www.jetbrains.com/shop/eform/students';
        $headers = $this->get_jet_headers($url);
        return $headers;
    }

    function reg($email)
    {
        if (!strpos($email, '@pku.edu.cn')) {
            return;
        }
        $pre_result = $this->pre_reg();
        $req = [
            'email' => $email,
            'name.firstName' => 'jet',
            'name.lastName' => 'actived',
            'JSESSIONID-SHOP' => $pre_result['Set-Cookie']['JSESSIONID-SHOP'],
            '_st-SHOP' => $pre_result['Set-Cookie']['_st-SHOP']
        ];
        $data = [
            'applyType' => 'EMAIL',
            'studentType' => 'STUDENT',
            'studyLevel' => 'UNDER',
            'studyIsMajor' => 'MAJOR',
            'studyGraduationDate' => 'May 02, 2023',
            'email' => $req['email'],
            'name.firstName' => $req['name.firstName'],
            'name.lastName' => $req['name.lastName'],
            'countryIso' => 'CN',
            'attachment' => '(binary)',
            'privacyPolicy' => 'on'
        ];


//        $st = 'v6ANu-rv9S7wkV6O-bP7GK9EmJ9WCt6pA5ith-JZR6XvYQNXtw2GKwiSsmVFlZYl';
//        $cook = 'JSESSIONID-SHOP=0F542121E5F111486F12E933E7A5F740; _st-SHOP=v6ANu-rv9S7wkV6O-bP7GK9EmJ9WCt6pA5ith-JZR6XvYQNXtw2GKwiSsmVFlZYl';
        $st = substr($pre_result['Set-Cookie']['_st-SHOP'], 9);
        $cook = $pre_result['Set-Cookie']['JSESSIONID-SHOP'] . '; ' . $pre_result['Set-Cookie']['_st-SHOP'];


        $url = 'https://www.jetbrains.com/shop/eform/students?_st=' . $st;
        $header = 'authority: www.jetbrains.com
method: POST
path: /shop/eform/students?_st=%s
scheme: https
accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3
accept-encoding: gzip, deflate, br
Accept-Language: zh-CN,zh;q=0.9,en;q=0.8
cache-control: no-cache
content-type: application/x-www-form-urlencoded
cookie: %s
origin: https://www.jetbrains.com
pragma: no-cache
referer: https://www.jetbrains.com/shop/eform/students
sec-fetch-mode: navigate
sec-fetch-site: same-origin
sec-fetch-user: ?1
upgrade-insecure-requests: 1
user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
        $header = sprintf($header, $st, $cook);
        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => $header
            )
        );
        if ($data) {
            $postdata = http_build_query(
                $data
            );
            $opts['http']['content'] = $postdata;
        }
        $context = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);
        return $result;
    }

    function get_jet_headers($url, $data = [], $method = 'GET')
    {

        $opts = array('http' =>
            array(
                'method' => $method,
                'header' => 'Content-type: application/x-www-form-urlencoded\r\n' .
                    'authority:www.jetbrains.com\r\n' .
                    'path:/shop/eform/students\r\n' .
                    'scheme:https\r\n' .
                    'accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3\r\n' .
                    'accept-encoding:gzip, deflate, br\r\n' .
                    'Accept-Language:zh-CN,zh;q=0.9,en;q=0.8\r\n' .
                    'sec-fetch-mode:navigate\r\n' .
                    'sec-fetch-site:same-origin\r\n' .
                    'sec-fetch-user:?1\r\n' .
                    'upgrade-insecure-requests:1\r\n' .
                    'user-agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36',
            )
        );
        if ($data) {
            $postdata = http_build_query(
                $data
            );
            $opts['http']['content'] = $postdata;
        }
        $context = stream_context_create($opts);
        $headers = get_headers($url, null, $context);
//        file_get_contents($url, false, $context);
//        $headers = $this->parseHeaders($http_response_header);
        $headers = $this->parseHeaders($headers);
        return $headers;
    }

    function parseHeaders($headers)
    {
        $head = array();
        $set_cookie = [];
        foreach ($headers as $k => $v) {
            $t = explode(':', $v, 2);
            $t[0] = trim($t[0]);
            if ($t[0] == 'Set-Cookie') {
                $t[1] = trim($t[1]);
                $tt = explode(';', $t[1]);
                if (strpos($tt[0], 'JSESSIONID-SHOP') !== false) {
                    $set_cookie['JSESSIONID-SHOP'] = trim($tt[0]);
                } elseif (strpos($t[1], '_st-SHOP') !== false) {
                    $set_cookie['_st-SHOP'] = trim($tt[0]);
                }
            } else if (isset($t[1]))
                $head[$t[0]] = trim($t[1]);
            else {
                $head[] = $v;
                if (preg_match("#HTTP/[0-9\.]+\s+([0-9]+)#", $v, $out))
                    $head['reponse_code'] = intval($out[1]);
            }
        }
        $head['Set-Cookie'] = $set_cookie;
        return $head;
    }
}