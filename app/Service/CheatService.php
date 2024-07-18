<?php

namespace App\Service;

use App\Models\Cheat;

class CheatService
{
    public static function one($tgid)
    {
        $query = Cheat::query();

        $query->where("tgid", $tgid);

        return $query->first();
    }

    public static function create($tgid, $admin_id, $reason = "")
    {
        $cheat = static::one($tgid);
        if (!$cheat) {
            $cheat = new Cheat();
            $cheat->tgid = $tgid;
            $cheat->reason = $reason ? $reason : sprintf("admin %s input", $admin_id);
            $cheat->created_at = date("Y-m-d H:i:s");
            $cheat->save();
        }

        return $cheat;
    }
}