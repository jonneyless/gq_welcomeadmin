<?php


namespace App\Service;

use App\Models\Group;
use Illuminate\Support\Facades\Cache;

class GroupService
{
    public static function cache()
    {
        $groups = Cache::get("welcome_groups1");
        if (!$groups) {

            $groups = Group::query()
                ->where("status_in", 1)
                ->where(function ($query) {
                    $query->where('flag', 2)
                        ->orWhere('flag', 4);
                })
                ->select("id", "chat_id as tg_id", "title", "flag", "business_detail_type")
                ->get();

            $groups = obj_to_array($groups);

            Cache::put("welcome_groups1", $groups, 1);
        }

        return $groups;
    }

    public static function one_by_cache($chat_id)
    {
        return Group::query()->where("chat_id", $chat_id)->first();
    }

    public static function one_by_num($num)
    {
        $query = Group::query();
        
        $query->where("status_in", 2)
            ->where("group_num", $num);
        
        $query->where(function ($query) {
            $query->where('flag', 2)
                  ->orWhere('flag', 4);
        });
        
        return $query->first();
    }

    public static function get($condition = [])
    {
        $query = Group::query();

        if (is_right_data($condition, "id")) {
            $query->where("id", $condition["id"]);
        }
        if (is_right_data($condition, "chat_id")) {
            $query->where("chat_id", $condition["chat_id"]);
        }

        if (is_right_data($condition, "chat_ids")) {
            $query->whereIn("chat_id", $condition["chat_ids"]);
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

    public static function setWelcomeInfo($group, $welcome_info)
    {
        $group->welcome_info = $welcome_info;
        $group->save();
    }

    public static function changeFlag($group, $flag)
    {
        $group->flag = $flag;
        $group->save();
    }

    public static function changeBusinessType($group, $type)
    {
        $group->business_type = $type;
        $group->save();
    }

    public static function changeBusinessDetailType($group, $type)
    {
        $group->business_detail_type = $type;
        $group->save();
    }

    public static function changeTradeType($group, $type)
    {
        $group->trade_type = $type;
        $group->save();
    }

    public static function changePeopleLimit($group, $type)
    {
        $group->people_limit = $type;
        $group->save();
    }

    public static function changeWelcomeStatus($group, $type)
    {
        $group->welcome_status = $type;
        $group->save();
    }

    public static function changeLimitOneTime($group, $type)
    {
        $group->limit_one_time = $type;
        $group->save();
    }

    public static function setVal($group, $key, $val)
    {
        $group->$key = $val;
        $group->save();
    }

    public static function setTime($group, $startTime, $endTime)
    {
        $group->started_at = $startTime;
        $group->ended_at = $endTime;
        $group->save();
    }

    public static function setApproveVal($group, $key, $val)
    {
        if ($key == "status_approve_one") {
            $group->status_approve_one = $val;
        } elseif ($key == "status_approve_two") {
            $group->status_approve_two = $val;
        } elseif ($key == "status_approve_three") {
            $group->status_approve_three = $val;
        } elseif ($key == "status_approve_four") {
            $group->status_approve_four = $val;
        } elseif ($key == "status_approve_five") {
            $group->status_approve_five = $val;
        }

        $group->save();
    }

    public static function open($chat)
    {
        $chat->opened = 1;
        $chat->save();
    }

    public static function close($chat)
    {
        $chat->opened = 2;
        $chat->save();
    }

    public static function changeTradeRate($group, $val)
    {
        $group->trade_rate = $val;
        $group->save();
    }

    public static function flushAdmin($group)
    {
        $chat_id = $group["chat_id"];
        $result = getChatAdministrators($chat_id);

        if ($result && is_right_data($result, "ok") && $result["ok"]) {
            if (is_right_data($result, "result")) {
                $admins = $result["result"];

                $ids = [];
                foreach ($admins as $admin) {
                    $custom_title = "";
                    if (is_right_data($admin, "custom_title")) {
                        $custom_title = $admin["custom_title"];
                    }
                    
                    $user = $admin["user"];
                    $user = AssistService::handleFrom($user);

                    array_push($ids, $user["id"]);

                    $userData = $user;
                    $userData["chat_id"] = $chat_id;
                    $userData["status"] = $admin["status"];
                    $userData["custom_title"] = $custom_title;
                    
                    GroupAdminService::createAndUpdate($userData);
                }

                GroupAdminService::clear($chat_id, $ids);
            }
        }
    }
}