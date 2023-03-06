/*
 * 作者:GS-Meida
 * 邮箱:adm@igmsy.com
 * 主页:https://www.igmsy.com
 */

/* 搜索 */
var helangSearch = {
	/* 元素集 */
	els: {},
	/* 初始化 */
	init: function () {
		var _this = this;
		this.els = {
			input: $("#search-input")
		};
		$(document).ready(function () {
			// Al hacer submit en el formulario
			$('#gpt3-form').submit(function (e) {
				// 防止表单的默认行为
				e.preventDefault();

				// 检索文本字段的值
				var prompt = $('#search-input').val();

				// 验证文本字段不为空
				if (!prompt) {
					$('#msg').html('<div class="msg-body" role="alert"><table width="100%"><td width="20px"><svg t="1677744235347" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="4712" width="100%" height="100%" style="margin-top:5px;"><path d="M512 512m-512 0a512 512 0 1 0 1024 0 512 512 0 1 0-1024 0Z" fill="#EA0000" p-id="4713"></path><path d="M661.44 306.56l66.56 66.496-147.84 147.712 147.84 147.776-66.56 66.56-147.776-147.84-147.712 147.84-66.56-66.56 147.712-147.776L299.392 373.12l66.56-66.56 147.712 147.712 147.84-147.712z" fill="#FFFFFF" p-id="4714"></path></svg></td><td>提问内容不能为空...</td></table></div>' + $('#msg').html());
					return;
				}

				// 在处理请求时显示加载消息
				$('#btn').html('思考中...');
				$('#btn').attr('disabled', 'disabled');

				// 向服务器发出 AJAX 请求
				$.ajax({
					type: 'POST',
					url: '/api/Chat.php',
					data: { prompt: prompt },
					success: function (text) {
						// 在 ID 为 gpt3-response 的元素中显示来自服务器的响应
						$('#msg').html(text + $('#msg').html());
						$('#btn').html('<svg t="1677725480478" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="4072" width="32" height="32"><path d="M35.15807894 382.42251307l947.585056-144.68161067-767.92547414 254.38525013zM219.58738454 503.2555072l767.92547306-254.38525013-670.94109653 306.852208 11.12935467 233.716448z" fill="#ffffff" p-id="4073"></path><path d="M302.52343574 832.36642453l-12.71926293-233.71644906 166.94032 106.523824zM579.16739414 762.41048l-127.19262506-82.67520533-166.94032-106.523824 670.94109653-306.852208z" fill="#ffffff" p-id="4074"></path></svg>');
						$('#btn').removeAttr('disabled', 'disabled');
					},
				});
			});
		});
	}
};