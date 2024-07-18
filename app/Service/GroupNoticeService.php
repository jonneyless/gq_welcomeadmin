<?php

namespace App\Service;

use App\Models\GroupNotice;

class GroupNoticeService
{
    public static function get($condition = [])
    {
        $query = GroupNotice::query();

        if (is_right_data($condition, "id")) {
            $query->where("id", $condition["id"]);
        }
        if (is_right_data($condition, "chat_id")) {
            $query->where("chat_id", $condition["chat_id"]);
        }

        if (is_right_data($condition, "is_one_obj")) {
            return $query->first();
        }
        if (is_right_data($condition, "is_one_arr")) {
            return obj_to_array($query->first());
        }

        $query->orderBy("id", "desc");

        if (is_right_data($condition, "is_arr")) {
            return obj_to_array($query->get());
        }

        return $query->get();
    }

    public static function add($data = [])
    {
        $groupNotice = new GroupNotice();
        $groupNotice->chat_id = $data["chat_id"];
        $groupNotice->msg = $data["msg"];
        $groupNotice->space = $data["space"];
        $groupNotice->flag = $data["flag"];
        $groupNotice->created_at = date("Y-m-d H:i:s");
        $groupNotice->save();
    }

    public static function delete($groupNotice)
    {
        $groupNotice->delete();
    }
}