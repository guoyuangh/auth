<?php

namespace app\admin\controller;

use app\tool\Tool;
use think\Request;
use app\common\model\AuthRule as AuthRuleModel;

class AuthRule extends Base
{
    /**
     * 显示资源列表
     * @param AuthRuleModel $AuthRuleModel
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index(AuthRuleModel $AuthRuleModel)
    {
        $rows = $AuthRuleModel::getAllRule();
        return $this->fetch('index', ['rows' => $rows]);
    }

    /**
     * 显示创建资源表单页.
     * @param AuthRuleModel $AuthRuleModel
     * @return \think\response\Json

     * @throws \think\exception\DbException
     */
    public function create(AuthRuleModel $AuthRuleModel)
    {
        $rows = $AuthRuleModel::getAllRule();
        $page = $this->fetch('create', ['rows' => $rows]);
        return Tool::showPage(['page' => $page]);
    }

    /**
     * 保存新建的资源
     * @param AuthRuleModel $AuthRuleModel
     * @param Request $request
     * @return \think\response\Json
     */
    public function save(AuthRuleModel $AuthRuleModel, Request $request)
    {
        $data = $request->post();
        $validate = $this->validate($data, "app\\admin\\validate\\AuthRule");
        if (true !== $validate) return Tool::showError($validate);

        $result = $AuthRuleModel->allowField(['rule_name','menu_route','sort', 'rule_node', 'is_menu', 'pid', 'menu_ico', 'menu_name'])->save($data);
        if ($result) return Tool::showSuccess('添加规则成功!'); else return Tool::showError('添加规则失败!');
    }


    /**
     * 显示编辑资源表单页
     * @param AuthRuleModel $AuthRuleModel
     * @param int $id
     * @return mixed|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit(AuthRuleModel $AuthRuleModel, $id = 0)
    {
        $row = $AuthRuleModel->where('id', $id)->find();
        if (null === $row) return Tool::showError('不存在的数据!');
        $rows = $AuthRuleModel::getAllRule();

        $page = $this->fetch('edit', ['data' => $row, 'rows' => $rows, 'childs' => $AuthRuleModel::getChilds($id)]);
        return Tool::showPage(['page' => $page]);
    }

    /**
     * 保存更新的资源
     * @param AuthRuleModel $AuthRuleModel
     * @param Request $request
     * @param $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function update(AuthRuleModel $AuthRuleModel, Request $request, $id)
    {
        $row = $AuthRuleModel->where('id', $id)->find();
        if (null === $row) return Tool::showError('不存在的数据!');

        $data = $request->post();
        $validate = $this->validate($data, "app\\admin\\validate\\AuthRule");
        if (true !== $validate) return Tool::showError($validate);
        $result = $row->allowField(['rule_name','menu_route','sort',  'rule_node', 'is_menu', 'pid', 'menu_ico', 'menu_name'])->save($data);
        if ($result) return Tool::showSuccess('更新规则成功!'); else return Tool::showError('更新规则失败!');
    }

    /**
     *  删除指定资源
     * @param AuthRuleModel $AuthRuleModel
     * @param int $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function delete(AuthRuleModel $AuthRuleModel,$id=0)
    {
        $row = $AuthRuleModel->where('id', $id)->find();
        if (null === $row) return Tool::showError('不存在的数据!');

        if ($row->delete()) return Tool::showSuccess('删除规则成功!'); else return Tool::showError('删除规则失败!');
    }

    /**
     * 批量删除
     * @param AuthRuleModel $AuthRuleModel
     * @param array $id
     * @return \think\response\Json
     * @throws \Exception
     */
    public function batch(AuthRuleModel $AuthRuleModel,$id=[])
    {
        $childRule = $AuthRuleModel->whereIn('pid',$id)->select();

//        if(!empty($childRule)) return Tool::showError('该规则有子规则,请先删除子规则!');

        $result = $AuthRuleModel->whereIn('id',$id)->delete();
        if ($result) return Tool::showSuccess('删除规则成功!'); else return Tool::showError('删除规则失败!');
    }
}
