<?php
/**
 * User: 李昊天
 * Date: 18/7/9
 * Time: 上午7:21
 * Email: haotian0607@gmail.com
 */

namespace app\admin\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
        'nickname' => 'require|max:10',
        'mobile' => 'require|mobile|unique:user,mobile',
        'password' => 'require|min:9',
        'admin' => 'require|in:0,1',
    ];

    protected $message = [
        'nickname.require'    => '请输入用户昵称!',
        'nickname.max'    => '用户昵称不得大于:rule个字符!',
        'mobile.require'    => '请输入手机号码!',
        'mobile.mobile'    => '手机号码格式错误!',
        'mobile.unique'    => '手机号码已经被使用!',
        'password.require'    => '请输入密码!',
        'admin.require' => '请选择是否为后台管理!',
        'admin.in' => '请选择是否为后台管理!',
    ];

    protected function sceneEdit()
    {
        return $this->only(['mobile','password','admin'])
            ->remove('password','require')
            ->append('mobile','unique:user,mobile^id');
    }
}