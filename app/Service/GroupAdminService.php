<?php

namespace App\Service;

use App\Models\GroupAdmin;

class GroupAdminService
{
    public static function one($user_id)
    {
        $query = GroupAdmin::query();
        
        $query->where("user_id", $user_id);
        
        return $query->first();
    }
    
    public static function get($condition = [])
    {
        $query = GroupAdmin::query();

        if (is_right_data($condition, "chat_id")) {
            $query->where("chat_id", $condition["chat_id"]);
        }

        if (is_right_data($condition, "user_id")) {
            $query->where("user_id", $condition["user_id"]);
        }

        if (is_right_data($condition, "group_id")) {
            $query->where("group_id", $condition["group_id"]);
        }

        $query->whereNotNull("status");

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

    public static function create($data)
    {
        $groupAdmin = new GroupAdmin();
        $groupAdmin->chat_id = $data["chat_id"];
        $groupAdmin->user_id = $data["user_id"];
        $groupAdmin->username = $data["username"];
        $groupAdmin->firstname = $data["first_name"];
        $groupAdmin->lastname = $data["last_name"];
        
        $groupAdmin->fullname = $data["first_name"] . $data["last_name"];
        
        $groupAdmin->status = $data["status"];
        $groupAdmin->created_at = date("Y-m-d H:i:s");
        $groupAdmin->custom_title = $data["custom_title"];
        $groupAdmin->save();
    }

    public static function update($groupAdmin, $data)
    {
        $groupAdmin->username = $data["username"];
        $groupAdmin->firstname = $data["first_name"];
        $groupAdmin->lastname = $data["last_name"];
        
        $groupAdmin->fullname = $data["first_name"] . $data["last_name"];
        
        $groupAdmin->status = $data["status"];
        $groupAdmin->updated_at = date("Y-m-d H:i:s");
        $groupAdmin->custom_title = $data["custom_title"];
        $groupAdmin->save();
    }

    public static function createAndUpdate($data = [])
    {
        $groupAdmin = static::get([
            "chat_id" => $data["chat_id"],
            "user_id" => $data["user_id"],
            "is_one_obj" => true,
        ]);
        if ($groupAdmin) {
            static::update($groupAdmin, $data);
        } else {
            static::create($data);
        }
    }

    public static function setUserTitle($groupAdmin, $title)
    {
        $groupAdmin->custom_title = $title;
        $groupAdmin->save();
    }

    public static function clear($chat_id, $ids)
    {
        $admins = static::get(compact("chat_id"));
        foreach ($admins as $admin) {
            if (!in_array($admin["user_id"], $ids)) {
                $admin->delete();
            }
        }
    }
}