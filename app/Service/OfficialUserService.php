<?php

namespace App\Service;

use App\Models\OfficialUser;

class OfficialUserService
{
    public static function one($condition = [])
    {
        $query = OfficialUser::query();

        if (is_right_data($condition, "id")) {
            $query->where("id", $condition["id"]);
        }
        if (is_right_data($condition, "tg_id")) {
            $query->where("tg_id", $condition["tg_id"]);
        }
        if (is_right_data($condition, "username")) {
            $query->where("username", $condition["username"]);
        }

        return $query->first();
    }
}