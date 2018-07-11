<?php
/**
 * User: 李昊天
 * Date: 18/7/11
 * Time: 上午2:07
 * Email: haotian0607@gmail.com
 */

namespace app\job;

use think\queue\Job;
use app\common\model\LoginLog;
use app\common\model\User as UserModel;

class LoginJob
{
    /**
     * @param Job $job
     * @param $data
     * @throws \think\Exception
     */
    public function saveLog(Job $job, $data)
    {
        $saveLog = LoginLog::createLog($data);
        if ($saveLog) {
            (new UserModel())->where('id', $data['id'])->setInc('login_number', 1);
            (new UserModel())->where('id', $data['id'])->update([
                'last_login_ip' => $data['ip'],
                'last_login_time' => time()
            ]);
            $job->delete();
            return;
        }
    }
}