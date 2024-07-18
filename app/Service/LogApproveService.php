<?php


namespace App\Service;

use App\Models\LogApprove;

class LogApproveService
{
    public static function get($condition = [])
    {
        $query = LogApprove::query();

        if (is_right_data($condition, "id")) {
            $query->where("id", $condition["id"]);
        }
        if (is_right_data($condition, "chat_id")) {
            $query->where("group_tg_id", $condition["chat_id"]);
        }
        if (is_right_data($condition, "user_tg_id")) {
            $query->where("user_tg_id", $condition["user_tg_id"]);
        }
        if (is_right_data($condition, "status")) {
            $query->where("status", $condition["status"]);
        }
        if (is_right_data($condition, "created_at_lt")) {
            $query->where("created_at", "<", $condition["created_at_lt"]);
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

    public static function update($logApprove, $status, $reason)
    {
        $group_tg_id = $logApprove["group_tg_id"];
        $user_tg_id = $logApprove["user_tg_id"];

        $logApprove->status = $status;
        $logApprove->reason = $reason;
        $logApprove->updated_at = date("Y-m-d H:i:s");
        $logApprove->save();

        if ($status == 1) {
            approveChatJoinRequest($group_tg_id, $user_tg_id);
        } elseif ($status == 3) {
            declineChatJoinRequest($group_tg_id, $user_tg_id);
        }
    }
    
    public static function search($condition = [])
    {
        $query = LogApprove::query();
        
        if (is_right_data($condition, "group_tg_id")) {
            $query->where("group_tg_id", $condition["group_tg_id"]);
        }
        if (is_right_data($condition, "status")) {
            $query->where("status", $condition["status"]);
        }
        
        $sort = 1;
        if (is_right_data($condition, "sort")) {
            $sort = $condition["sort"];
            if ($sort == 1) {
                $query->orderBy("id", "desc");
            } elseif ($sort == 2) {
                $query->orderBy("fullname", "asc");
            } elseif ($sort == 3) {
                $query->orderBy("fullname", "desc");
            }
        }
        
        $page = 1;
        if (is_right_data($condition, "page")) {
            $page = $condition["page"];
        }

        $page_len = 10;
        if (is_right_data($condition, "page_len")) {
            $page_len = $condition["page_len"];
        }

        $queryTemp = $query;
        $count = $queryTemp->count();

        $data =  $query
            ->skip(($page - 1) * $page_len)
            ->limit($page_len)
            ->get();
            
        $data = obj_to_array($data);

        return compact("count", "data", "sort");
    }
}
