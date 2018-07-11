<?php

namespace app\admin\controller;

use app\tool\Tool;
use think\Db;
use think\Exception;
use think\Request;
use app\common\model\AuthRule as AuthRuleModel;
use app\common\model\AuthGroup as AuthGroupModel;

class AuthGroup extends Base
{
    /**
     * 显示资源列表
     * @param AuthGroupModel $AuthGroupModel
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index(AuthGroupModel $AuthGroupModel)
    {
        $rows = $AuthGroupModel->paginate(10);
        return $this->fetch('index', ['rows' => $rows]);
    }

    /**
     * 显示创建资源表单页.
     */
    public function create()
    {
        $page = $this->fetch('create');
        return Tool::showPage(['page' => $page]);
    }

    /**
     * 保存新建的资源
     * @param AuthGroupModel $AuthGroupModel
     * @param Request $request
     * @return \think\response\Json
     */
    public function save(AuthGroupModel $AuthGroupModel, Request $request)
    {
        $data = $request->post();
        $validate = $this->validate($data, "app\\admin\\validate\\AuthGroup");
        if (true !== $validate) return Tool::showError($validate);
        if ($AuthGroupModel->allowField(['is_super', 'group_name', 'desc'])->save($data)) return Tool::showSuccess('添加角色成功!'); else return Tool::showError('添加角色失败!');
    }

    /**
     * 显示编辑资源表单页
     * @param AuthGroupModel $AuthGroupModel
     * @param int $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit(AuthGroupModel $AuthGroupModel, $id = 0)
    {
        $result = $AuthGroupModel->where('id', $id)->find();
        if (null === $result) return Tool::showError('不存在的数据!');
        $this->assign('row', $result->getData());
        $page = $this->fetch('edit');
        return Tool::showPage(['page' => $page]);
    }

    /**
     * 保存更新的资源
     * @param AuthGroupModel $AuthGroupModel
     * @param Request $request
     * @param $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function update(AuthGroupModel $AuthGroupModel, Request $request, $id)
    {
        $result = $AuthGroupModel->where('id', $id)->find();
        if (null === $result) return Tool::showError('不存在的数据!');

        $data = $request->post();
        $data['id'] = $request->get('id', 0);
        $validate = $this->validate($data, "app\\admin\\validate\\AuthGroup.edit");
        if (true !== $validate) return Tool::showError($validate);
        if ($result->allowField(['is_super', 'group_name', 'desc'])->save($data)) return Tool::showSuccess('更新角色成功!'); else return Tool::showError('更新角色失败!');
    }

    /**
     * 删除指定资源
     * @param AuthGroupModel $AuthGroupModel
     * @param int $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function delete(AuthGroupModel $AuthGroupModel, $id = 0)
    {
        $result = $AuthGroupModel->where('id', $id)->find();
        if (null === $result) return Tool::showError('不存在的数据!');
        if ($result->delete()) return Tool::showSuccess('删除角色成功!'); else return Tool::showError('删除角色失败!');
    }

    /**
     * 批量删除角色
     * @param AuthGroupModel $AuthGroupModel
     * @param array $id
     * @return \think\response\Json
     * @throws \Exception
     */
    public function batch(AuthGroupModel $AuthGroupModel, $id = [])
    {
        $result = $AuthGroupModel->whereIn('id', $id)->delete();
        if ($result) return Tool::showSuccess('删除角色成功!'); else return Tool::showError('删除角色失败!');
    }

    /**
     * @param AuthGroupModel $AuthGroupModel
     * @param AuthRuleModel $AuthRuleModel
     * @param int $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function privilege(AuthGroupModel $AuthGroupModel, AuthRuleModel $AuthRuleModel, $id = 0)
    {
        $data = $AuthGroupModel->where('id', $id)->find();
        $AuthGroupRuleModel = new \app\common\model\AuthGroupRule();
        $existsData = $AuthGroupRuleModel->where('gid', $id)->column('rid');
        if (null === $data) return Tool::showError('角色数据不存在!');
        $this->assign('rules', (new \app\tool\Tree())->load($AuthRuleModel::getAllRule()));
        $this->assign('existsData', $existsData);
        $page = $this->fetch('privilege');
        return Tool::showPage(['page' => $page]);
    }

    /**
     * @param AuthGroupModel $AuthGroupModel
     * @param Request $request
     * @param int $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function saveAuth(AuthGroupModel $AuthGroupModel, Request $request, $id = 0)
    {
        $AuthGroupRuleModel = new \app\common\model\AuthGroupRule();

        $existsData = $AuthGroupRuleModel->where('gid', $id)->column('rid');
        $data = $AuthGroupModel->where('id', $id)->find();
        if (null === $data) return Tool::showError('角色数据不存在!');
        $rid = $request->post('rid/a', []);


        $items = [];
        $insertData = array_diff($rid, $existsData);
        $deleteData = array_diff($existsData, $rid);

        foreach ($insertData as $key => $value) {
            $items[] = [
                'gid' => $id,
                'rid' => $value
            ];
        }
        Db::startTrans();
        try {
            $AuthGroupRuleModel->where('gid', $id)->whereIn('rid', $deleteData)->delete();
            $AuthGroupRuleModel->saveAll($items);
            Db::commit();
            return Tool::showSuccess('操作成功!');
        } catch (Exception $e) {
            Db::rollback();
            return Tool::showError('操作失败!');
        }
    }
}
