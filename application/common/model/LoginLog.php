<?php
/**
 * User: 李昊天
 * Date: 18/6/7
 * Time: 下午6:27
 * Email: haotian0607@gmail.com
 */

namespace app\common\model;


use think\Model;

class LoginLog extends Model
{
    public static function createLog($data = [])
    {
        $ip = $data['ip'] ?? '';
        $result = @file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip);
        $row = json_decode($result, true);
        if ($row['code'] == 0) {
            $row['data']['uid'] = $data['id'];
            return (new LoginLog)->allowField(true)->create($row['data']);
        } else {
            return false;
        }
    }
}