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

    public static function feeBank(){
        $fee= array();
        $fee[]=['id'=>1, 'name'=>'Mất phí'];
        $fee[]=['id'=>0, 'name'=>'Không mất phí'];
        return $fee;
    }

    public static function shareStatus(){
        $share= array();
        $share[]=['id'=>1, 'name'=>'Đã Share'];
        $share[]=['id'=>0, 'name'=>'Chưa Share'];
        return $share;
    }

    public static function typeRequest(){
        $type= array();
        $type[]=['id'=>1, 'name'=>'Nạp tiền'];
        $type[]=['id'=>0, 'name'=>'Rút tiền'];
        return $type;
    }

    public static function statusRequest(){
        $status= array();
        $status[]=['id'=>1, 'name'=>'Đã duyệt'];
        $status[]=['id'=>0, 'name'=>'Đang chờ'];
        return $status;
    }
}