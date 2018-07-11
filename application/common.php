<?php
function getTree($list, $id = 0, $level = 0)
{
    static $tree = array();
    foreach ($list as $row) {
        if ($row['pid'] == $id) {
            $row['level'] = $level;
//            $row['name'] = str_repeat('----',$level). $row['name'];
            $tree[] = $row;
            getTree($list, $row['id'], $level + 1);
        }
    }
    return $tree;
}

/**
 * 把返回的数据集转换成Tree
 * @param $list 全部数组
 * @param string $pk 主键
 * @param string $pid 关联外键
 * @param string $child 子类名称
 * @param int $root
 * @return array 返回数组
 */
function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
{
    // 创建Tree
    $tree = array();
    if (is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}

// 模板中权限校验
function AuthCheck($rules, $logic = 'OR')
{
    if(!is_array($rules)){
        $rules = [$rules];
    }
    $auth = new \auth\Auth();
    return $auth->check($rules, \think\facade\Session::get('user.id'), $logic);
}


//$arr->传入数组   $key->判断的key值
function array_unset_tt($arr,$key){
    //建立一个目标数组
    $res = array();
    foreach ($arr as $value) {
        //查看有没有重复项
        if(isset($res[$value[$key]])){
            unset($value[$key]);  //有：销毁
        }else{
            $res[$value[$key]] = $value;
        }
    }
    return $res;

}
