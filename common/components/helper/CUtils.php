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

    public static function status()
    {
        $status = array();
        $status[] = ['id' => 1, 'name' => 'Hoạt động'];
        $status[] = ['id' => 0, 'name' => 'Ngưng'];
        return $status;
    }

    public static function feeBank()
    {
        $fee = array();
        $fee[] = ['id' => 1, 'name' => 'Mất phí'];
        $fee[] = ['id' => 0, 'name' => 'Không mất phí'];
        return $fee;
    }

    public static function shareStatus()
    {
        $share = array();
        $share[] = ['id' => 1, 'name' => 'Đã Share'];
        $share[] = ['id' => 0, 'name' => 'Chưa Share'];
        return $share;
    }

    public static function typeTransaction()
    {
        $type = array();
        $type[] = ['id' => 1, 'name' => 'Thu'];
        $type[] = ['id' => 2, 'name' => 'Chi'];
        $type[] = ['id' => 3, 'name' => 'Pending'];
        return $type;
    }

    public static function typeRequest()
    {
        $type = array();
        $type[] = ['id' => 1, 'name' => 'Rút tiền'];
        $type[] = ['id' => 2, 'name' => 'Nạp tiền'];
        return $type;
    }

    public static function statusRequest()
    {
        $status = array();
        $status[] = ['id' => 1, 'name' => 'Đã duyệt'];
        $status[] = ['id' => 2, 'name' => 'Đang chờ'];
        return $status;
    }

    public static function statusAds()
    {
        $status = array();
        $status[] = ['id' => 0, 'name' => 'Chờ duyệt'];
        $status[] = ['id' => 1, 'name' => 'Đã duyệt'];
        $status[] = ['id' => 2, 'name' => 'Từ chối'];
        return $status;
    }
    public static function statusCheck()
    {
        $status = array();
        $status[] = ['id' => 1, 'name' => 'Duyệt quảng cáo'];
        $status[] = ['id' => 2, 'name' => 'Từ chối'];
        return $status;
    }
    public static function statusUser()
    {
        $status = array(
            ['id' => '', 'name' => 'Chưa xác minh'],
            ['id' => 1, 'name' => 'Đã xác minh'],
            ['id' => 2, 'name' => 'Cần xác minh']
        );
        return $status;
    }
}