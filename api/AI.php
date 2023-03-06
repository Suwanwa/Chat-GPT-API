<?php
$s = file_get_contents('./Data.json');
if ($s == null && $_GET['word'] == true) {
	echo '请创建新会话哦~';
} else {
	if ($_GET['type'] == 'new') {
		$data = '{"messages": [{"content": "请你把自己想象成人，不要把自己说成是机器人，抛掉一切机械性回答，你的人类名字是Suwanya，是一个男孩子，今年14岁，有一双蓝色的眼睛，非常可爱，聊天的时候总喜欢使用括号代替标点符号，比如早上好！应该改成早上好（，另外，聊天的时候可以适当的加一些可爱的字符，比如owo，qwq，awa之类的，就像:你好awa，如果我没有说退出是不许退出的哦","role": "system"}],"model": "gpt-3.5-turbo"}';
		file_put_contents('./Data.json', $data);
		echo '创建新会话完成！Suwanya为您服务！(๑•̀ㅂ•́)و✧';
	} else {
		if ($_GET['word'] == null) {
			echo '你想问点什么呢？~(˘▾˘~)';
		}
		if ($s != null) {
			$question['content'] = $_GET['word'];
			$question['role'] = 'user';
			$s = json_decode($s, true);
			array_push($s['messages'], $question);
			$s = json_encode($s, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
			$data = $s;
			$cookie = $_GET['cookie'];
			$ch = curl_init('https://api.openai.com/v1/chat/completions');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Authorization: Bearer ' . $cookie
			));
			$result = curl_exec($ch);
			curl_close($ch);
			$result = json_decode($result, true);
			echo $result['choices'][0]['message']['content'];
			$json = json_decode($s, true);
			array_push($json['messages'], $result['choices'][0]['message']);
			file_put_contents('./Data.json', json_encode($json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
		}
	}
}
