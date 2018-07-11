<?php

namespace app\admin\controller;

use app\tool\Tool;
use think\Db;
use think\Exception;
use think\Request;
use think\db\Query;
use think\helper\Hash;
use app\common\model\User as UserModel;

class User extends Base
{
    /**
     * 用户列表
     * @param UserModel $user
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index(UserModel $user)
    {
        $query = new Query();
        $rows = $user->where($query)->paginate(10);

        return $this->fetch('index', ['rows' => $rows]);
    }

    /**
     * 显示创建资源表单页.
     * @return \think\response\Json
     */
    public function create()
    {
        $page = $this->fetch('create');
        return Tool::showPage(['page' => $page, 'title' => '添加用户']);
    }

    /**
     * 保存新建的资源
     * @param Request $request
     * @return string
     */
    public function save(Request $request)
    {
        $data = $request->post();
        $validate = $this->validate($data, "app\\admin\\validate\\User");
        if (true !== $validate) return Tool::showError($validate);
        UserModel::event('before_write', function ($user) {
            $user['password'] = Hash::make($user['password']);
        });
        $result = (new UserModel())->allowField(['nickname','admin','password', 'mobile'])->save($data);
        if ($result) return Tool::showSuccess('添加用户成功!'); else return Tool::showError('添加用户失败!');
    }

    /**
     * 显示编辑资源表单页.
     * @param UserModel $user
     * @param  int $id
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit(UserModel $user, $id = 0)
    {
        $row = $user->where('id', $id)->find();
        if (null === $row) return Tool::showError('不存在的用户!');
        $page = $this->fetch('edit', ['row' => $row->getData()]);
        return Tool::showPage(['page' => $page, 'title' => '编辑用户']);
    }

    /**
     * 保存更新的资源
     * @param UserModel $user 模型对象
     * @param Request $request 请求对象
     * @param $id int 用户ID
     * @return \think\response\Json 更新结果
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function update(UserModel $user, Request $request, $id = 0)
    {
        $row = $user->where('id', $id)->find();
        if (null === $row) return Tool::showError('不存在的用户!');

        $data = $request->post();
        $validate = $this->validate($data, "app\\admin\\validate\\User.edit");
        if (true !== $validate) return Tool::showError($validate);
        $data['password'] = !empty($data['password']) ? Hash::make($data['password']) : $row['password'];
        if ($row->allowField(['nickname','mobile', 'password', 'admin'])->save($data)) return Tool::showSuccess('更新用户成功!'); else return Tool::showError('更新用户失败!');
    }

    /**
     * 删除指定资源
     * @param UserModel $user
     * @param  int $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function delete(UserModel $user, $id = 0)
    {
        $row = $user->where('id', $id)->find();
        if (null === $row) return Tool::showError('不存在的用户!');
        if ($row->delete()) return Tool::showSuccess('删除用户成功!'); else return Tool::showError('删除用户失败!');
    }

    /**
     * 批量删除
     * @param UserModel $user
     * @param array $id
     * @return \think\response\Json
     * @throws \Exception
     */
    public function batch(UserModel $user, $id = [])
    {
        $result = $user->whereIn('id', $id)->delete();
        if ($result) return Tool::showSuccess('删除用户成功!'); else return Tool::showError('删除用户失败!');
    }

    /**
     * @param UserModel $user
     * @param int $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function privilege(UserModel $user, $id = 0)
    {
        $result = $user->where('id', $id)->find();
        if (null === $result) return Tool::showError('不存在的用户!');
        $row = (new \app\common\model\AuthGroup())->select();
        $existsData = (new \app\common\model\AuthGroupUser())->where('uid', $id)->column('gid');
        $this->assign('rows', $row);
        $this->assign('existsData', $existsData);
        $page = $this->fetch('privilege');
        return Tool::showPage(['page' => $page]);
    }

    /**
     * @param UserModel $user
     * @param Request $request
     * @param int $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function saveAuth(UserModel $user, Request $request, $id = 0)
    {
        $result = $user->where('id', $id)->find();
        if (null === $result) return Tool::showError('不存在的用户!');

        $AuthGroupUser = new \app\common\model\AuthGroupUser();
        $existsData = $AuthGroupUser->where('uid', $id)->column('gid');
        $gids = $request->post('gid/a', []);
        $items = [];
        $insertData = array_diff($gids, $existsData);
        $deleteData = array_diff($existsData, $gids);

        foreach ($insertData as $key => $value) {
            $items[] = [
                'uid' => $id,
                'gid' => $value
            ];
        }

        Db::startTrans();
        try {
            $AuthGroupUser->where('uid',$id)->whereIn('gid', $deleteData)->delete();
            $AuthGroupUser->saveAll($items);
            Db::commit();
            return Tool::showSuccess('操作成功!');
        } catch (Exception $e) {
            Db::rollback();
            return Tool::showError('操作失败!');
        }
    }
}
