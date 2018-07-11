<?php
/**
 * User: 李昊天
 * Date: 18/7/9
 * Time: 下午10:45
 * Email: haotian0607@gmail.com
 */

namespace app\common\model;


use think\Model;

class AuthRule extends Model
{
    /**
     * @return array
     * @throws \think\exception\DbException
     */
    public static function getAllRule()
    {
        $result = self::order('sort','desc')->select();
        return self::getTree($result->toArray());
    }
    /**
     * @param $list
     * @param int $id
     * @param int $level
     * @return array
     */
    public static function getTree($list, $id = 0, $level = 0)
    {

        static $tree = array();
        foreach ($list as $row) {
            if ($row['pid'] == $id) {
                $row['level'] = $level;
                $tree[] = $row;
                self::getTree($list, $row['id'], $level + 1);
            }
        }
        return $tree;
    }

    /**
     * @param int $id
     * @return array
     * @throws \think\exception\DbException
     */
    public static function getChilds($id=0)
    {
        if (!$id) {
            return [];
        }
        $cats = self::getAllRule();
        // 找到其中的当前分类的后代分类和自己
        $ids = [];// 所有的后代分类id(包含自己的)
        $begin = false;// 设置开始标记
        foreach($cats as $cat) {
            if ($cat['id'] == $id) {
                $begin = true;
                $curr = $cat;// 记录自己
            }
            // 从自己开始
            if(! $begin)  continue;
            // 到 第一个level不大于自己(自己不应该终止)的终止
            if ($cat['id'] != $curr['id'] && $cat['level'] <= $curr['level']) break;

            // 记录下来全部的id(包含自己的及其后代的)
            $ids[] = $cat['id'];
        }
        return $ids;
    }
}