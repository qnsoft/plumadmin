<p align="center">
  <a href="https://www.yuque.com/u1048634/rwos6m" target="blank"><img src="https://file.huamzl.wang/plumadmin_logo.png" width="200" alt="PlumAdmin Logo" /></a>
</p>
<p align="center">
    <code>PlumAdmin</code>后端基于<a href="https://github.com/top-think/think">Thinkphp</a> 前端基于<a href="https://github.com/PanJiaChen/vue-admin-template">vue-admin-template</a>开发而成的后台管理系统
</p>



<p align="center">
    <img src="https://svg.hamm.cn/badge.svg?key=Base&value=ThinkPHP6"/>
    <img src="https://svg.hamm.cn/badge.svg?key=Data&value=MySQL5.6"/>
    <img src="https://svg.hamm.cn/badge.svg?key=Runtime&value=PHP7.4"/>
    <img src="https://svg.hamm.cn/badge.svg?key=License&value=MIT"/>
</p >


### 地址

* **gitee**  [传送门](https://gitee.com/plum429/plumadmin)
* **文档**   [传送门](https://www.yuque.com/u1048634/rwos6m)
* **预览** [传送门](http://preview.plumcloud.top)

### 功能

* <code>用户管理</code>
* <code>权限管理</code>
* <code>角色管理</code>
* <code>个人设置</code>
* <code>上传设置</code> 支持本地 七牛云 腾讯云 阿里云
* <code>定时器</code> 基于workerman,支持windows开发环境
* <code>队列</code> 基于workerman,支持windows开发环境
* <code>错误日志</code> 支持发送错误日志邮件通知

### 计划

- [x] <code>文档</code> 框架搭建流程等说明文档
- [x] `预览` 管理后台的预览
- [ ] `文件管理`
- [x] `上传支持视频和文件` 目前前端还未兼容视频和文件的上传
- [ ] `excel导出导入` 考虑性能问题采用前端来处理
- [x] `重构上传` 目前是采用直接存储图片连接的形式,迁移图片时不方便,将重构采用关联文件表的形式
- [ ] `快捷开发` 采用可视化方式构建常用的一些开发代码
- [ ] `代码注释` 代码规范性,以及注释,方便二次开发
- [ ] `主题` 优化前端页面
- [ ] `vite构建` 前端使用vite构建,使其更快

### 快速上手
##### 部署后端
```shell
//下载源码 or git clone https://github.com/plum429/plumadmin.git
git clone https://gitee.com/plum429/plumadmin.git
//安装composer依赖
cd plumadmin && composer install 
//配置信息,复制.env.example修改成.env,补充信息
cp .example.env .env
//提前创建好数据库,插入表和数据
php think migrate:run
php think seed:run
```
##### 部署前端
```shell
//前端代码,在template/admin文件夹中
//进入文件夹,安装依赖
cd template/admin && npm install
//src/config.js可以修改一些配置,线上请求地址无特殊需求无需调整
//打包,打包会自动打包到软件/publick/admin下
npm run build
```
##### 部署伪静态
```nginx
#如果使用了宝塔,可以用宝塔直接配置thinkphp的伪静态,记得将public设置为运行目录
#如果没有使用宝塔,在nginx配置里加如下代码
location / {
	if (!-e $request_filename){
		rewrite  ^(.*)$  /index.php?s=$1  last;   break;
	}
}
```
### THINK

- [vue-admin-template](https://github.com/PanJiaChen/vue-admin-template)
- [thinkphp](https://github.com/top-think/think)
- [workerman](https://github.com/walkor/Workerman)
- [thinkphp-filesystem-cloud](https://github.com/QThans/thinkphp-filesystem-cloud)

### License

[MIT](https://github.com/PanJiaChen/vue-element-admin/blob/master/LICENSE)

Copyright (c) 2021-present plum429
