<?php

namespace App\Service;

use App\Models\CheatSpecial;

class CheatSpecialService
{
    public static function one($tgid)
    {
        $tgid = strval($tgid);
        
        $query = CheatSpecial::query();

        $query->where("tgid", $tgid);

        return $query->first();
    }

    public static function create($tgid, $admin_id, $reason=false)
    {
        $cheat = static::one($tgid);
        if (!$cheat) {
            $cheat = new CheatSpecial();
            $cheat->tgid = $tgid;
            $cheat->reason = $reason ? $reason : sprintf("管理员 %s 添加", $admin_id);
            $cheat->admin_id = $admin_id;
            $cheat->created_at = date("Y-m-d H:i:s");
            $cheat->save();
        }
        
        $reason = "担保骗子库同步，" . $reason;
        CheatService::create($tgid, $admin_id, $reason);

        return $cheat;
    }
}