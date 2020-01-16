<?php
/**
 * Created by PhpStorm.
 * User: lxh
 * Date: 2020-01-09
 * Time: 00:46
 */

namespace App\HttpController;


use App\Model\AM\Idea;
use EasySwoole\Http\AbstractInterface\Controller;

class Jetbrains extends Controller
{

    function index()
    {
        $req = $this->request()->getRequestParam();
        if (array_key_exists('k', $req)) {
            $key = $req['k'];
            //redis
            $validTime = date('Y-m-d H:i:s', strtotime('-8 minutes'));
            $oneMonth = date('Y-m-d H:i:s', strtotime('-1 months'));
            $data = Idea::create()->findOne(
                ['visit_key' => $key, 'status' => [[0, 1], 'IN'], 'create_time' => [$oneMonth, '>=']]);
            if ($data) {
                if ($data['status'] == 0 || ($data['status'] == 1 && $data['update_time'] >= $validTime)) {
                    // redis
                    $d = '7NYXX5E2OU-eyJsaWNlbnNlSWQiOiI3TllYWDVFMk9VIiwibGljZW5zZWVOYW1lIjoi5qe/5p6XIOWVhuWfjiIsImFzc2lnbmVlTmFtZSI6IiIsImFzc2lnbmVlRW1haWwiOiIiLCJsaWNlbnNlUmVzdHJpY3Rpb24iOiIiLCJjaGVja0NvbmN1cnJlbnRVc2UiOmZhbHNlLCJwcm9kdWN0cyI6W3siY29kZSI6IklJIiwiZmFsbGJhY2tEYXRlIjoiMjAyMC0wMS0wNSIsInBhaWRVcFRvIjoiMjAyMS0wMS0wNCJ9LHsiY29kZSI6IkFDIiwiZmFsbGJhY2tEYXRlIjoiMjAyMC0wMS0wNSIsInBhaWRVcFRvIjoiMjAyMS0wMS0wNCJ9LHsiY29kZSI6IkRQTiIsImZhbGxiYWNrRGF0ZSI6IjIwMjAtMDEtMDUiLCJwYWlkVXBUbyI6IjIwMjEtMDEtMDQifSx7ImNvZGUiOiJQUyIsImZhbGxiYWNrRGF0ZSI6IjIwMjAtMDEtMDUiLCJwYWlkVXBUbyI6IjIwMjEtMDEtMDQifSx7ImNvZGUiOiJHTyIsImZhbGxiYWNrRGF0ZSI6IjIwMjAtMDEtMDUiLCJwYWlkVXBUbyI6IjIwMjEtMDEtMDQifSx7ImNvZGUiOiJETSIsImZhbGxiYWNrRGF0ZSI6IjIwMjAtMDEtMDUiLCJwYWlkVXBUbyI6IjIwMjEtMDEtMDQifSx7ImNvZGUiOiJDTCIsImZhbGxiYWNrRGF0ZSI6IjIwMjAtMDEtMDUiLCJwYWlkVXBUbyI6IjIwMjEtMDEtMDQifSx7ImNvZGUiOiJSUzAiLCJmYWxsYmFja0RhdGUiOiIyMDIwLTAxLTA1IiwicGFpZFVwVG8iOiIyMDIxLTAxLTA0In0seyJjb2RlIjoiUkMiLCJmYWxsYmFja0RhdGUiOiIyMDIwLTAxLTA1IiwicGFpZFVwVG8iOiIyMDIxLTAxLTA0In0seyJjb2RlIjoiUkQiLCJmYWxsYmFja0RhdGUiOiIyMDIwLTAxLTA1IiwicGFpZFVwVG8iOiIyMDIxLTAxLTA0In0seyJjb2RlIjoiUEMiLCJmYWxsYmFja0RhdGUiOiIyMDIwLTAxLTA1IiwicGFpZFVwVG8iOiIyMDIxLTAxLTA0In0seyJjb2RlIjoiUk0iLCJmYWxsYmFja0RhdGUiOiIyMDIwLTAxLTA1IiwicGFpZFVwVG8iOiIyMDIxLTAxLTA0In0seyJjb2RlIjoiV1MiLCJmYWxsYmFja0RhdGUiOiIyMDIwLTAxLTA1IiwicGFpZFVwVG8iOiIyMDIxLTAxLTA0In0seyJjb2RlIjoiREIiLCJmYWxsYmFja0RhdGUiOiIyMDIwLTAxLTA1IiwicGFpZFVwVG8iOiIyMDIxLTAxLTA0In0seyJjb2RlIjoiREMiLCJmYWxsYmFja0RhdGUiOiIyMDIwLTAxLTA1IiwicGFpZFVwVG8iOiIyMDIxLTAxLTA0In0seyJjb2RlIjoiUlNVIiwiZmFsbGJhY2tEYXRlIjoiMjAyMC0wMS0wNSIsInBhaWRVcFRvIjoiMjAyMS0wMS0wNCJ9XSwiaGFzaCI6IjE2MDk0OTY4LzAiLCJncmFjZVBlcmlvZERheXMiOjcsImF1dG9Qcm9sb25nYXRlZCI6ZmFsc2UsImlzQXV0b1Byb2xvbmdhdGVkIjpmYWxzZX0=-MqE/Rk6NYDhRo5AKqUGrvFc1MErGZz0v6PERjwOUFg7bg7Cv4N8wxmx1msUXdEZVbeNJyZB2YmhRuzFz82lFQlxJV9hhawyuwVl93pKOvj6udHeP1cOtPSL3GcePvnMk61QNwggu9g7zvjC3q24pzP1S6UHQTTBNXjV3qzosfyjgzVEsJeuu4wCy+cdiXE65wjULvFjlYRAzU725Mb7j5v2pcD7bfTmDVgkQ2VRqCeTpUo90N5wT0LwF79ideE04eezlbna/5uih/adBbWhChcVL2cWUf0TH0jKPblwhLG1IzCiX8vPIRy2NfpSURMIwRxg6X7yd1EE955rIa19pxA==-MIIElTCCAn2gAwIBAgIBCTANBgkqhkiG9w0BAQsFADAYMRYwFAYDVQQDDA1KZXRQcm9maWxlIENBMB4XDTE4MTEwMTEyMjk0NloXDTIwMTEwMjEyMjk0NlowaDELMAkGA1UEBhMCQ1oxDjAMBgNVBAgMBU51c2xlMQ8wDQYDVQQHDAZQcmFndWUxGTAXBgNVBAoMEEpldEJyYWlucyBzLnIuby4xHTAbBgNVBAMMFHByb2QzeS1mcm9tLTIwMTgxMTAxMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAxcQkq+zdxlR2mmRYBPzGbUNdMN6OaXiXzxIWtMEkrJMO/5oUfQJbLLuMSMK0QHFmaI37WShyxZcfRCidwXjot4zmNBKnlyHodDij/78TmVqFl8nOeD5+07B8VEaIu7c3E1N+e1doC6wht4I4+IEmtsPAdoaj5WCQVQbrI8KeT8M9VcBIWX7fD0fhexfg3ZRt0xqwMcXGNp3DdJHiO0rCdU+Itv7EmtnSVq9jBG1usMSFvMowR25mju2JcPFp1+I4ZI+FqgR8gyG8oiNDyNEoAbsR3lOpI7grUYSvkB/xVy/VoklPCK2h0f0GJxFjnye8NT1PAywoyl7RmiAVRE/EKwIDAQABo4GZMIGWMAkGA1UdEwQCMAAwHQYDVR0OBBYEFGEpG9oZGcfLMGNBkY7SgHiMGgTcMEgGA1UdIwRBMD+AFKOetkhnQhI2Qb1t4Lm0oFKLl/GzoRykGjAYMRYwFAYDVQQDDA1KZXRQcm9maWxlIENBggkA0myxg7KDeeEwEwYDVR0lBAwwCgYIKwYBBQUHAwEwCwYDVR0PBAQDAgWgMA0GCSqGSIb3DQEBCwUAA4ICAQAF8uc+YJOHHwOFcPzmbjcxNDuGoOUIP+2h1R75Lecswb7ru2LWWSUMtXVKQzChLNPn/72W0k+oI056tgiwuG7M49LXp4zQVlQnFmWU1wwGvVhq5R63Rpjx1zjGUhcXgayu7+9zMUW596Lbomsg8qVve6euqsrFicYkIIuUu4zYPndJwfe0YkS5nY72SHnNdbPhEnN8wcB2Kz+OIG0lih3yz5EqFhld03bGp222ZQCIghCTVL6QBNadGsiN/lWLl4JdR3lJkZzlpFdiHijoVRdWeSWqM4y0t23c92HXKrgppoSV18XMxrWVdoSM3nuMHwxGhFyde05OdDtLpCv+jlWf5REAHHA201pAU6bJSZINyHDUTB+Beo28rRXSwSh3OUIvYwKNVeoBY+KwOJ7WnuTCUq1meE6GkKc4D/cXmgpOyW/1SmBz3XjVIi/zprZ0zf3qH5mkphtg6ksjKgKjmx1cXfZAAX6wcDBNaCL+Ortep1Dh8xDUbqbBVNBL4jbiL3i3xsfNiyJgaZ5sX7i8tmStEpLbPwvHcByuf59qJhV/bZOl8KqJBETCDJcY6O2aqhTUy+9x93ThKs1GKrRPePrWPluud7ttlgtRveit/pcBrnQcXOl1rHq7ByB8CFAxNotRUYL9IF5n3wJOgkPojMy6jetQA5Ogc8Sm7RG6vg1yow==';
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
        } elseif (array_key_exists('go', $req)) {
            $count = $req['go'];
            if ($count) {
                $count = intval($count);
                $limit_count = 2;
                if ($count >= $limit_count) {
                    $count = $limit_count;
                }
                $data = [];
                while ($count > 0) {
                    $count--;
                    $key = $this->getValidCode();
                    Idea::create(['visit_key' => $key])->save();
                    $link = 'http://a.taojingling.cn/jet?k=' . $key;
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
}