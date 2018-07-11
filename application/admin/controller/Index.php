<?php
/**
 * User: 李昊天
 * Date: 18/7/9
 * Time: 上午5:10
 * Email: haotian0607@gmail.com
 */
namespace app\admin\controller;

use think\Controller;
use think\facade\Cookie;
use think\facade\Session;

class Index extends Controller
{
    public function index()
    {
        return $this->redirect('admin/Login/index');
    }

    /**
     *
     */
    public function logout()
    {
        Session::delete('user');
        Cookie::delete('autoLogin');
        return $this->error('退出成功!','admin/Login/index');
    }
}