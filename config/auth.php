<?php
/**
 * User: 李昊天
 * Date: 18/7/10
 * Time: 上午7:30
 * Email: haotian0607@gmail.com
 */
return [
    'not_auth_tip' => '无权限操作!', //无权限的提示信息
    'is_cache' => true,        //是否将规则缓存
    'expire' => 0,              //缓存时间
    'prefix' => '',             //缓存前缀
    'name' => 'user_auth',      //缓存key
    'exclude_rule' => [         //不验证权限的url
        'admin/main/index', //后台首页
        'admin/main/welcome',   //后台欢迎页
        'admin/main/clearCache',   //清除缓存
    ],
];