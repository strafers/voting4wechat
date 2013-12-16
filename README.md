微信公众平台投票抽奖应用
=============

## About

这个应用在 ``第三届全球移动互联网博览会(Global Mobile Internet Exposition，GMIE)`` 用做现场观众通过微信公众平台对参展的团队进行投票和参与现场抽奖使用的。

公司临时把这个任务指派给我这个小前端做，而且时间也只是3天而已，做的时候有些考虑不周的地方，正式用过之后才发现了问题。

因为这个小应用是公司友情帮主办方做的，而且又一次性，所以我把一些逻辑问题修改了下，放到 github ,有需要的同学可以仿照着，或者直接修改下就可以用了。

## Feature

*  观众关注微信公众平台，输入展位号，对心仪的团队进行投票
*  实时展示投票排名 TOP 10 团队 ``http://domain/?a=index``
*  现场从微信投票记录中抽奖，并下发中奖通知 ``http://domain/?a=rand_page``

## Usage

*  因为使用了微信公众平台的高级接口，所以你的公众号应该是 ``服务号``
*  通过 ``/config/db.config.php`` 配置你的数据库链接，并导入 ``/data/app_cxg.sql`` 初始化数据库
*  通过 ``/config/app.config.php`` 配置你的个性设置

## Preview

#### TOP 10

![default](http://dearb.u.qiniudn.com/20131216162419.png)


#### Rand Page

![default](http://dearb.u.qiniudn.com/20131216162541.png)

## Thanks

Designed by [@Ray]

## License

Copyright (c) 2013 Belin Chung. MIT Licensed, see [LICENSE] for details.

[LICENSE]: https://github.com/BelinChung/voting4wechat/blob/master/LICENSE.md
[@Ray]: http://rayps.com/
