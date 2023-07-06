# Chat-GPT-API
有哪些功能？
* 允许查询余额
* 单PHP API实现
* 免代理
* 支持POST和GET两种方法
* 支持JSON输出
* 支持GPT 3.5 Turbo，GPT 4
* 支持上下文
* 支持人设提交
* 支持创建会话
* 支持Cookie提交

在线体验:https://chat.suwanya.cn

API:http://yyyy.wiki:9920/api

😊记得给我点Star！

___
请求参数
```
参数        类型   参数值        描述

word        必填   text         此值是你提交给AI的问题

temperature 可选   string       此值为思维发散程度，0-2，默认为0.7

key         可选   string       此值为用户标识

system      可选   text         此值是全局人设

type        可选   money|json   此值为json时返回json，为money时返回余额

reset       可选   true/false   此值为true则刷新会话，默认为false
