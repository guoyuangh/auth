演示后台地址:https://admin.dreamcreating.cn/admin 
==============================================

php版本 >= 7.0  因为里面使用了只有PHP7支持的 ?? ?: 语法

## 参与开发
haotian0607@gmail.com

目录结构
www  WEB部署目录（或者子目录）
├─application           应用目录
│  ├─common             公共模块目录
│  ├─admin              后台模块目录
│  │  ├─api             后台模块Api接口目录（放置相应的Api请求文件）
│  │  ├─behavior        表现层目录
│  │  ├─controller      控制器目录
│  │  ├─model           模型目录
│  │  ├─service         服务目录
│  │  ├─view            视图目录
│  │  ├─transformers    Api接口装换器目录 （放置相应的Api转换器文件）
│  │  ├─validate        验证类目录
│  │  └─ ...            更多类库目录
│  │
├─route                 路由定义目录
│  ├─admin.php          后台路由
├─extend                扩展类库目录
   ├─auth               auth权限验证类