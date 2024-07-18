<?php

namespace App\Service;

use App\Models\LogOperation;

class LogOperationService
{
    public static $type_in_black = 1;// 加入黑名单
    public static $type_out_black = 2;// 解除黑名单
    public static $type_in_cheat = 3;// 加入骗子库
    public static $type_in_cheat_bank = 4;// 加入骗子库_银行卡
    public static $type_in_cheat_coin = 5;// 加入骗子库_虚拟币

    public static function create($data = [])
    {
        $logOperation = new LogOperation();
        $logOperation->admin_id = $data["admin_id"];
        $logOperation->type = $data["type"];
        $logOperation->info = $data["info"];
        $logOperation->created_at = date("Y-m-d H:i:s");
        $logOperation->save();
    }
}