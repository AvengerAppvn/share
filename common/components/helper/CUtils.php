<?php
/**
 * Description of CUtils
 *
 */
namespace common\components\helper;

/**
 * List of weeks
 *
 */
class CUtils
{

    public static function status(){
        $status= array();
        $status[]=['id'=>1, 'name'=>'Kích hoạt'];
        $status[]=['id'=>0, 'name'=>'Đóng'];
        return $status;
    }
}