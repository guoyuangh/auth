<?php
/**
 * User: 李昊天
 * Date: 18/7/9
 * Time: 上午5:38
 * Email: haotian0607@gmail.com
 */

namespace app\admin\controller;

use app\common\model\LoginLog as LoginLogmodel;
use app\tool\Tool;
use app\tool\Tree;
use auth\Auth;
use think\facade\Cache;
use think\facade\Session;

class Main extends Base
{
    /**
     * @param \app\common\model\AuthRule $AuthRule
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index(\app\common\model\AuthRule $AuthRule)
    {
        $rows = (new Auth())->getMenu(Session::get('user.id'));

        $menu = $AuthRule::getTree($rows);

        return $this->fetch('index', ['rows' => (new Tree())->load($menu)]);
    }

    /**
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function welcome()
    {
        $beginWeek = mktime(0,0,0,date("m"),date("d")-date("w")+1,date("Y"));
        $endWeek = mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y"));

        $logRows = (new LoginLogmodel())->where('uid', parent::$uid)->whereBetweenTime('create_time',$beginWeek,$endWeek)->order('create_time', 'desc')->paginate(10);

        return $this->fetch('welcome', [
            'logRows' => $logRows,
            'userData' => parent::getUserdata(),
        ]);
    }

    public function clearCache()
    {
        Cache::clear();
        return Tool::showSuccess('操作成功!');
    }
}