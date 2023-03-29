<?php
/*
 * 作者:Suwanya
 * 主页:https://suwanya.cn
 * Github:http://github.com/Suwanwa
 */

//变量定义
$Url = 'https://chatgpt.suwanya.cn';
//这里修改成你搭建好的域名
$Password = 'Suwanya';
//这里修改成你的自定义密码
$Model = 'gpt-3.5-turbo';
//这里修改成你使用的Model，可选gpt-3.5-turbo，gpt-4，gpt-4-32k
$System = isset($_REQUEST["system"]) ? $_REQUEST["system"] : "";
$Key = isset($_REQUEST["key"]) ? $_REQUEST["key"] : "";
$Cookie = isset($_REQUEST["cook"]) ? $_REQUEST["cook"] : "";
$Word = isset($_REQUEST["word"]) ? $_REQUEST["word"] : "";
$Type = isset($_REQUEST["type"]) ? $_REQUEST["type"] : "";
$Temperature = isset($_REQUEST["temperature"]) ? $_REQUEST["temperature"] : "";

//Data部分
if (getallheaders()['Key'] != null) {
    $ch = curl_init($Url . '/api');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $Word);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Cookie: ' . getallheaders()['Key']
    ));
    $result = curl_exec($ch);
    curl_close($ch);
    echo $result;
} else {
    if ($Type == 'money'  && $Cookie != null) {
        $data = '{"messages":[{"role":"user","content":"查询填写的 Key 的余额"}],"key":"' . $Cookie . '","password":"' . $Password . '","model":"gpt-3.5-turbo"}';
        //余额查询
        $ch = curl_init($Url . '/api');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        ));
        $result = curl_exec($ch);
        curl_close($ch);
        echo $result;
        // $ch = curl_init('https://api.openai.com/dashboard/billing/credit_grants');
        // //或者调用OpenAI的官方接口进行余额查询
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        //     'Content-Type: application/json',
        //     'Authorization: Bearer ' . $Cookie
        // ));
        // $result = curl_exec($ch);
        // curl_close($ch);
        // $Str = json_decode($result, true)['grants']['data'];
        // header('content-type:application/json;charset=utf8');
        // echo json_encode($Str, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    } else {
        if (!$Key) {
            echo Header("Location: https://suwanya.cn");
            //不提交Key强制重定向
        }
        if ($Temperature != null && $Cookie != null && $Key != null && $Word != null) {
            $curl = curl_init();
            $time = date('Y-m-d', strtotime('+1 day'));
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://www.urlc.cn/api/url/add",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 2,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Token 9juf9WeCNSgX7eYoaFju",
                    "Content-Type: application/json",
                ),
                CURLOPT_POSTFIELDS => '{
            "url": "https://ai.suwanya.cn/userData/' . $Key . '.json",
            "password": "3292036962",
            "domain": "http://9l8.cn",
            "expiry": "' . $time . '",
            "limit": {
              "boundstype": "0",
              "bounds": "1"
            },
              "debrowser": {
              "app": "2"
            }
          }',
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            //使用我的Key进行链接缩短，如果不需要可以删除此段
            $data = '{"messages":[{"role":"system","content":"' . $System . $Word . '"}],"key":"' . $Cookie . '","password":"' . $Password . '","temperature":' . $Temperature . ',"model":"' . $Model . '"}';
            file_put_contents("../userData/" . $Key . ".json", $data);
            file_put_contents("../userData/" . $Key . ".txt", $System);
            echo "创建新会话完成！Suwanya为您服务(๑•̀ㅂ•́)و✧！当前System角色信息已保存至https://ai.suwanya.cn/userData/" . $Key . ".txt，历史会话信息已保存至" . substr(stripslashes($response), 20, -2);
            //这里的数据是保存在你的服务器的，可以自行修改
        } else {
            $file = file_get_contents("../userData/" . $Key . ".json");
            if ($file != null && $Word != null && $Key != null) {
                $question['content'] = $Word;
                $question['role'] = 'user';
                $file = json_decode($file, true);
                array_push($file['messages'], $question);
                $file = json_encode($file, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                $ch = curl_init($Url . '/api');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $file);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Cookie: ' . $Key
                ));
                $result = curl_exec($ch);
                curl_close($ch);
                if ($Type == 'json') {
                    $Str = json_decode(str_replace("model", "使用模型", str_replace("messages", "历史会话(本数据来自Github - Suwanwa 作者QQ:3292036962)", str_replace("system", "管理员", str_replace("role", "角色", str_replace("content", "发送内容", str_replace("user", "You", str_replace("assistant", "Chat GPT", str_replace('\n', '|', file_get_contents("../userData/" . $Key . ".json"))))))))));
                    echo header('content-type:application/json;charset=utf8');
                    echo json_encode($Str, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                } else {
                    echo $result;
                }
                $file = file_get_contents("../userData/" . $Key . ".json");
                $array = array(
                    "role" => "assistant",
                    "content" => $result
                );
                $question['content'] = $Word;
                $question['role'] = 'user';
                $file = json_decode($file, true);
                array_push($file['messages'], $question, $array);
                $file = json_encode($file, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                file_put_contents("../userData/" . $Key . ".json", $file);
            }
        }
    }
}
?>