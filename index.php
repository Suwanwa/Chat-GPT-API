<?php
/*
 * 作者:Suwanya
 * Github:https://github.com/Suwanwa
 */

// 方法定义
function Cookie()
{
    $poems = "填写你的Key，可填写多个，每行一个";
    $poems = explode("\n", $poems);
    return trim($poems[rand(0, count($poems) - 1)]);
}

// 变量定义
$Word = isset($_REQUEST["word"]) ? $_REQUEST["word"] : "";
$System = isset($_REQUEST["system"]) ? $_REQUEST["system"] : "";
$Temperature = isset($_REQUEST["temperature"]) ? $_REQUEST["temperature"] : 0.7;
$Key = isset($_REQUEST["key"]) ? $_REQUEST["key"] : "Suwanya";
$Reset = isset($_REQUEST["reset"]) ? $_REQUEST["reset"] : false;
$Type = isset($_REQUEST["type"]) ? $_REQUEST["type"] : "";

// Data部分
if ($Type == 'money') {
    $ch = curl_init('https://api.openai.com/v1/dashboard/billing/subscription');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . Cookie()
    ));
    $result = curl_exec($ch);
    curl_close($ch);
    header('content-type:application/json;charset=utf8');
    echo $result;
    return;
}

if (!$Word and $Reset == false) {
    // 如果用户没有输入问题，返回错误信息
    if ($Type == 'json') {
        echo json_encode(['error' => '你还没有问我问题呢']);
        return;
    } else {
        echo '你还没有问我问题呢';
        return;
    }
}

// 为每个用户创建一个唯一的文件来存储会话信息
$File = "Data/{$Key}.json";

if ($Reset || !file_exists($File)) {
    // 如果用户想要重置会话，或者会话文件不存在，就创建一个新的会话文件
    $Messages = [['role' => 'system', 'content' => $System]];
} else {
    // 否则，加载已有的会话
    $Messages = json_decode(file_get_contents($File), true);
}

// 添加用户的问题到会话
$Messages[] = ['role' => 'user', 'content' => $Word];

// 设置请求体
$Data = [
    'model' => 'gpt-3.5-turbo',
    'messages' => $Messages,
    'temperature' => $Temperature
];

// 请求API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . Cookie()
));
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($Data));
$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

// 解析结果
$result = json_decode($result, true);

// 将AI的回答添加到会话，并保存会话
$Messages[] = ['role' => 'assistant', 'content' => $result['choices'][0]['message']['content']];
file_put_contents($File, json_encode($Messages, JSON_UNESCAPED_UNICODE));

// 返回AI的回答
if ($Type == 'json') {
    $Str = json_decode(file_get_contents("Data/" . $Key . ".json"), true);
    echo header('content-type:application/json;charset=utf8');
    echo json_encode($Str, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} else {
    echo $result['choices'][0]['message']['content'];
}
?>
