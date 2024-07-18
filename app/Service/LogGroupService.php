<?php

namespace App\Service;

use App\Models\LogGroup;
use App\Models\AdminUser;

class LogGroupService
{
    public static function save($data = [])
    {
        $admin = $data["admin"];
        
        $obj = new LogGroup();
        $obj->group_tg_id = $data["group_tg_id"];
        $obj->type = $data["type"];
        $obj->info = $data["info"];
        $obj->admin_id = $admin->id;
        $obj->created_at = date("Y-m-d H:i:s");
        $obj->save();
    }
    
    public static function search($condition = [])
    {
        $query = LogGroup::query();

        if (is_right_data($condition, "group_tg_id")) {
            $query->where("group_tg_id", strval($condition["group_tg_id"]));
        }
        if (is_right_data($condition, "startTime")) {
            $query->where("created_at", ">=", $condition["startTime"]);
        }
        if (is_right_data($condition, "endTime")) {
            $query->where("created_at", "<", $condition["endTime"]);
        }

        $start = 0;
        $len = 10;
        if (is_right_data($condition, "start") && is_right_data($condition, "len")) {
            $start = $condition["start"];
            $len = $condition["len"];
        }
        
        $count = $query->count();

        $query->orderBy("created_at", "desc");
        $query->skip($start)->limit($len);

        $data = $query->get();
        $data = obj_to_array($data);
        
        foreach ($data as $key => $val) {
            $admin_id = $val["admin_id"];
            
            $admin = AdminUser::query()->where("id", $admin_id)->first();
            if ($admin) {
                $data[$key]["admin_id"] = $admin["name"];
            }
        }

        return compact("data", "count");
    }
}