<?php


namespace App\Service;


use App\Models\GroupTradeReport;
use Illuminate\Support\Facades\DB;


class GroupTradeReportService
{
    public static function del($ids)
    {
        DB::table('group_trade_report')->whereIn('id', $ids)->delete();
    }
    
    public static function over($ids)
    {
        DB::table('group_trade_report')->whereIn('id', $ids)->update(['status' => 12]);
    }
    
    public static function change($ids, $group)
    {
        DB::table('group_trade_report')->whereIn('id', $ids)->update(['group_tg_id' => $group["chat_id"], "title" => $group["title"]]);
    }
    
    public static function get_ids($condition = [])
    {
        $query = GroupTradeReport::query();
        
        if (is_right_data($condition, "title")) {
            $query->where("title", "like", "%" . $condition["title"] . "%");
        }
        
        if (is_right_data($condition, "group_tg_id")) {
            $query->where("group_tg_id", strval($condition["group_tg_id"]));
        }
        
        // if (is_right_data($condition, "type") && $condition["type"] >= 0) {
        //     $query->where("status", $condition["type"]);
        // }
        
        if (is_right_data($condition, "startTime")) {
            $query->where("created_at", ">=", $condition["startTime"]);
        }
        
        if (is_right_data($condition, "endTime")) {
            $query->where("created_at", "<", $condition["endTime"]);
        }
        
        
        return obj_to_array($query->pluck("id"));
    }
    
    public static function search($condition = [])
    {
        $query = GroupTradeReport::query();
        
        if (is_right_data($condition, "title")) {
            $query->where("title", "like", "%" . $condition["title"] . "%");
        }
        
        if (is_right_data($condition, "group_tg_id")) {
            $query->where("group_tg_id", strval($condition["group_tg_id"]));
        }
        
        if (is_right_data($condition, "type") && $condition["type"] >= 0) {
            $query->where("status", $condition["type"]);
        }
        
        if (is_right_data($condition, "startTime")) {
            $query->where("created_at", ">=", $condition["startTime"]);
        }
        
        if (is_right_data($condition, "endTime")) {
            $query->where("created_at", "<", $condition["endTime"]);
        }
        
        $queryTemp = $query;

        $count = $queryTemp->count();

        $page = 1;
        if (is_right_data($condition, "page")) {
            $page = $condition["page"];
        }

        $page_len = 10;
        if (is_right_data($condition, "page_len")) {
            $page_len = $condition["page_len"];
        }
        
        $data = $query->orderBy("id", "desc")
            ->skip(($page - 1) * $page_len)
            ->limit($page_len)
            ->get();
        $data = obj_to_array($data);
        
        foreach ($data as $key => $item) {
            $data[$key]["title"] = str_limit($item["title"], 20, "...");
            $data[$key]["info"] = str_limit($item["info"], 40, "...");
            $data[$key]["admin_info"] = sprintf("%s<br/>%s", $item["admin_tg_id"], ($item["admin_username"] ? "@" . $item["admin_username"] : ""));
            $data[$key]["user_info"] = sprintf("%s<br/>%s", $item["user_tg_id"], ($item["user_username"] ? "@" . $item["user_username"] : ""));
            $data[$key]["status"] = static::get_status_text($item["status"]);
        }
        
        return compact("count", "data");
    }
    
    public static function get_status_text($type)
    {
        $types = [
            1 => '已确定(已发送在群里)',
            2 => '待定',
            3 => '管理取消',
            4 => '客户取消',
            5 => '客户已经确认',
            6 => '官方取消',
            9 => '发送群里失败',
            10 => '管理已完成',
            11 => '客户已完成',
            12 => '官方已完成',
            13 => '报备后取消中',
            14 => '报备后成功取消',
        ];
        
        foreach ($types as $key => $val) {
            if ($type == $key) {
                return sprintf('<span class="label label-primary">%s</span>', $val);
            }
        }
        
        return "";
    }
}