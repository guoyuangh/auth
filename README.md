#### 演示后台地址:https://admin.dreamcreating.cn/admin 

账号:18888888888  密码:18888888888

演示站和github项目没有同步更新,一般情况在github更新之后2小时内更新演示站代码,如有特殊情况会延迟

php版本 >= 7.0  因为里面使用了只有PHP7支持的 ?? ?: 语法

用户登录:表单Token,可以选择自动登录,
对于代码有不合理的地方希望大家提出来!

## 参与开发
Email:haotian0607@gmail.com

交流QQ群: 814270669
加群连接:<a target="_blank" href="//shang.qq.com/wpa/qunwpa?idkey=68670d406ff42150f78000829448ebf700c3a92617025155f9864366c3d04654">点击加入</a>

目录结构

    auth权限验证类 在/extend/auth/Auth.php里面
    配置文件在/config/auth.php里面   
 
## 后续功能
登录失败次数限制:进行中...

    自动登录时的环境检测: 进行中...
    多端登录逼下线:进行中...
    Api接口管理:进行中...
    系统设置:进行中...
    
### 更新记录
##### 2018/07/12
    修复在用户列表分页样式错乱问题
    增加CORS跨域请求
    修复后台登录记录本该是可以查看本周的,结果只显示当天的
    
##### 2018/07/15
    重新提交代码,因为之前提交的时候居然没有将css js提交上去 对此深感抱歉;
    提交vendor目录下类库 如果下载之后在您机器上出现问题请将vendor目录清空并执行下面的命令
    
        安装验证码类库: composer require topthink/think-captcha
        安装时间戳操作类库: composer require topthink/think-helper
        安装队列任务类库 : composer require topthink/think-queue