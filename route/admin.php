<?php
/**
 * User: 李昊天
 * Date: 18/7/9
 * Time: 上午5:15
 * Email: haotian0607@gmail.com
 */

use think\facade\Route;

Route::group('user', function () {
    Route::get('index', 'admin/user/index');    //用户列表
    Route::get('create', 'admin/user/create');    //显示创建资源表单页
    Route::post('create', 'admin/user/save');    //保存新建的资源
    Route::get('edit/:id', 'admin/user/edit');    //显示编辑资源表单页
    Route::post('edit/:id', 'admin/user/update');    //保存更新的资源
    Route::get('delete/:id', 'admin/user/delete');   //删除资源
    Route::get('batch', 'admin/user/batch');   //批量删除资源
    Route::get('privilege/:id', 'admin/user/privilege');   //角色授权页面
    Route::post('privilege/:id', 'admin/user/saveAuth');   //保存授权
})->ext('shtml');

Route::group('auth', function () {
    ####---- 角色路由  app\\admin\\controller\\AuthGroup ----####
    Route::group('group', function () {
        Route::get('index', 'admin/AuthGroup/index');    //角色列表
        Route::get('create', 'admin/AuthGroup/create');    //创建角色表单
        Route::post('create', 'admin/AuthGroup/save');    //保存创建角色数据
        Route::get('delete/:id', 'admin/AuthGroup/delete');    //删除角色
        Route::get('edit/:id', 'admin/AuthGroup/edit');    //显示编辑角色表单页
        Route::post('edit/:id', 'admin/AuthGroup/update');    //保存更新的角色
        Route::get('batch', 'admin/AuthGroup/batch');   //批量删除角色
        Route::get('privilege/:id', 'admin/AuthGroup/privilege');   //角色授权页面
        Route::post('privilege/:id', 'admin/AuthGroup/saveAuth');   //保存授权
    });

    ####---- 规则/权限路由  app\\admin\\controller\\AuthRule ----####
    Route::group('rule', function () {
        Route::get('index', 'admin/AuthRule/index');    //规则/权限列表
        Route::get('create', 'admin/AuthRule/create');    //创建规则/权限表单
        Route::post('create', 'admin/AuthRule/save');    //保存创建规则/权限数据
        Route::get('delete/:id', 'admin/AuthRule/delete');    //删除规则/权限
        Route::get('edit/:id', 'admin/AuthRule/edit');    //显示编辑规则/权限表单页
        Route::post('edit/:id', 'admin/AuthRule/update');    //保存更新的规则/权限
        Route::get('batch', 'admin/AuthRule/batch');   //批量删除规则/权限
    });
})->ext('shtml');

Route::group('admin',function (){
    Route::get('login','admin/login/index');    //登录页面
    Route::post('login','admin/login/login'); //登录逻辑
    Route::get('main', 'admin/main/index');    //后台主框架
    Route::get('logout', 'admin/index/logout');    //后台退出
    Route::get('clearCache', 'admin/main/clearCache');    //后台退出
    Route::get('welcome', 'admin/main/welcome')->ext('shtml');    //欢迎页面
})->ext('shtml');