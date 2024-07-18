<?php

namespace App\DataModels;

use App\Service\GroupAdminService;
use App\Service\GroupService;
use App\Models\GroupTradeReport;

class Group
{
    public static function detail($id)
    {
        $group = GroupService::get([
            "id" => $id,
            "is_one_obj" => true,
        ]);
        
        if (!$group) {
            return 404;
        }
        
        $admins = GroupAdminService::get([
            "chat_id" => strval($group["chat_id"]),
            "is_arr" => true,
        ]);
        
        $reports = GroupTradeReport::query()
            ->where("group_tg_id", strval($group["chat_id"]))
            ->where("status_del", 1)
            ->get();
            
        $money_in = 0;
        $money_over = 0;
        $money_yajin = $group["yajin"];
        
        foreach ($reports as $report) {
            $money = $report["money"];
            $status = $report["status"];
            
            if ($status == 5 or $status == 10 or $status == 13 or $status == 1) {
                $money_in = $money_in + $money;
            }
            
            if ($status == 11 or $status == 12) {
                $money_over = $money_over + $money;
            }
        }
        
        $money_sub = round($money_yajin - $money_in);
        
        return view('group.detail', compact("group", "admins", "money_over", "money_in", "money_sub", "reports", "money_yajin"));
    }
}