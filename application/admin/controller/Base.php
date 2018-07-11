<?php
/**
 * User: 李昊天
 * Date: 18/7/10
 * Time: 上午7:13
 * Email: haotian0607@gmail.com
 */

namespace app\admin\controller;

use auth\Auth;
use think\Controller;
use think\facade\Config;
use think\facade\Session;
use app\common\model\User as UserModel;

class Base extends Controller
{
    protected static $uid = '';

    public function initialize()
    {
        $this->checkLogin();
        self::$uid = Session::get('user.id');
        $this->checkAuth();
    }

    private function checkLogin()
    {
        if (!Session::has('user')) return $this->error('请登录后再操作!', 'admin/Login/index');
    }

    private function checkAuth()
    {
        $request = \think\facade\Request::instance();
        $controller = $request->controller();
        $action = $request->action();
        $module = $request->module();
        $rule = strtolower($module . '/' . $controller . '/' . $action);

        $exclude_rule = Config::get('auth.exclude_rule');
        array_walk($exclude_rule, function (&$value) {
            $value = strtolower($value);
        });

        if (in_array($rule, $exclude_rule)) {
            return true;
        }
        $auth = (new Auth(Config::pull('auth')))->check([$rule], self::$uid);
        if (false === $auth) {
            return $this->error(Config::get('auth.not_auth_tip'));
        }
    }

    /**
     * @return array|null|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getUserData()
    {
        return (new UserModel())->where('id',self::$uid)->find();
    }
}