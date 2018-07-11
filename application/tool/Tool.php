<?php
/**
 * User: 李昊天
 * Date: 18/7/9
 * Time: 上午7:37
 * Email: haotian0607@gmail.com
 */

namespace app\tool;

class Tool
{
    /**
     * @param $data
     * @return \think\response\Json
     */
    public static function showPage($data)
    {
        $response['status'] = 1;
        $response['data'] = $data;
        return json($response);
    }

    /**
     * @param string $msg
     * @param array $data
     * @return \think\response\Json
     */
    public static function showSuccess($msg = '', $data = [])
    {
        return self::showData(1, $msg, $data);
    }

    /**
     * @param string $msg
     * @param array $data
     * @return \think\response\Json
     */
    public static function showError($msg = '', $data = [])
    {
        return self::showData(0, $msg, $data);
    }

    /**
     * @param int $status
     * @param string $msg
     * @param array $data
     * @return \think\response\Json
     */
    public static function showData($status = 0, $msg = '', $data = [])
    {
        $response['status'] = $status;
        $response['msg'] = $msg;
        $data && $response['data'] = $data;
        return json($response);
    }
}