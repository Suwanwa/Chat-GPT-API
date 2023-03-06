# Chat-GPT-API
有哪些功能？
* 基于GPT 3.5 Turbo
* 支持上下文
* 支持人设创建
* 支持创建会话
* 支持Cookie提交
* 单PHP API实现
* HTML页面

`PHP需要自行申请Cookie并以GET方式提交`

在线体验:https://ai.suwanya.cn

😐在线体验暂时不支持修改System角色信息以及刷新会话

😐在线体验网页里，刷新会话请自行访问https://ai.suwanya.cn/api/AI.php?type=new

😊此类问题将会在后续更新解决，记得给我点Star！
___
请求参数
```
参数  类型  参数值   描述

word  必填  string  此值是你提交给AI的问题

type  可选   new    当此值为new时创建新会话
