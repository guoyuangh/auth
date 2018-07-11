<?php
/**
 * User: 李昊天
 * Date: 18/7/10
 * Time: 上午8:00
 * Email: haotian0607@gmail.com
 */

namespace app\admin\controller;


use app\tool\Tool;
use think\Controller;
use app\common\model\User as UserModel;
use think\facade\Cookie;
use think\facade\Session;
use think\helper\Hash;
use think\Queue;
use think\Request;

class Login extends Controller
{
    public function initialize()
    {
        if (Session::has('user')) {
            return $this->redirect('admin/main/index');
        }

        if (Cookie::get('autoLogin')) {
            return $this->autoLogin();
        }
    }

    /**
     * 显示登录表单
     * @return mixed
     */
    public function index()
    {
        return $this->fetch('/main/login');
    }

    /**
     * 执行登录逻辑
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login(Request $request)
    {
        $data = $request->post();
        $validate = $this->validate($data, "app\\admin\\validate\\Login");
        if (true !== $validate) return Tool::showError($validate, ['token' => $request->token()]);
        $user = (new UserModel())->where('mobile', $data['account'])->find();
        if (null === $user) return Tool::showError('用户名或密码不正确!', ['token' => $request->token()]);
        if (false === Hash::check($data['password'], $user['password'])) return Tool::showError('用户名或密码不正确!', ['token' => $request->token()]);
        if ($user['status'] !== 1) return Tool::showError('该用户已经被禁用!', ['token' => $request->token()]);
        if ($user['admin'] !== 1) return Tool::showError('该用户无权限登录后台!', ['token' => $request->token()]);
        Session::set('preLoginData', [
            'pre_ip' => $user['last_login_ip'],
            'pre_time' => $user['last_login_time'],
        ]);
        $result = $user->visible(['mobile', 'id','nickname','login_number']);
        $result = $result->toArray();
        Session::set('user', $result);
        if (($data['auto'] ?? '') === 'yes') {
            Cookie::set('autoLogin', $result, ['expire' => 86400]);
        }

        $result['ip'] = $request->ip();

        Queue::push("app\job\LoginJob@saveLog", $result, 'saveLoginLog');
        return Tool::showSuccess('登录成功!', ['url' => url('admin/Main/index')]);
    }

    /**
     * @return bool|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function autoLogin()
    {
        $data = Cookie::get('autoLogin');
        $user = (new UserModel())->where('id', $data['id'])->find();
        if (null == $user) {
            Cookie::delete('autoLogin');
            return;
        }

        $result = $user->visible(['mobile', 'id']);
        $result = $result->toArray();
        Session::set('user', $result);
        $result['ip'] = request()->ip();
        Queue::push("app\job\LoginJob@saveLog", $result, 'saveLoginLog');
        return $this->success('登录成功!', 'admin/main/index');
    }
}