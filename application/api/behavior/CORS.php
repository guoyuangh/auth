<?php
/**
 * User: 李昊天
 * Date: 18/7/12
 * Time: 上午12:43
 * Email: haotian0607@gmail.com
 */

namespace app\api\behavior;

use think\Request;

class CORS
{
    public static function appInit(Request $request)
    {
        header('Access-Control-Allow-Origin: * ');
        header("Access-Control-Allow-Headers: token,Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: POST,GET');
        if ($request->isOptions()) {
            exit();
        }

//        $origin = $request->domain();
//
//        $allow_origin = array(
//            'http://app.cn',
//            'http://api.app.cn',
//        );
//        if (in_array($origin, $allow_origin)) {
//
//        }
    }
}