<?php

namespace App\DataModels;

use App\Service\ConfigService;
use App\Service\ConfigTextService;

class Config
{
    public static function index()
    {
        $hour_notice_status = ConfigService::val("hour_notice_status");
        $switch_group_status = ConfigService::val("switch_group_status");
        $welcome_true_status = ConfigService::val("welcome_true_status");
        $welcome_false_status = ConfigService::val("welcome_false_status");
        $limit_time = ConfigService::val("limit_time");
        $limit_num = ConfigService::val("limit_num");
        $limit_all_time = ConfigService::val("limit_all_time");
        $limit_all_group_num = ConfigService::val("limit_all_group_num");
        $limit_cancel_restrict = ConfigService::val("limit_cancel_restrict");
        $limit_text_len = ConfigService::val("limit_text_len");
        
        $photo_limit_type_num = ConfigService::val("photo_limit_type_num");
        $photo_limit_time = ConfigService::val("photo_limit_time");
        $photo_limit_day = ConfigService::val("photo_limit_day");
        
        $xianjing_status = ConfigService::val("xianjing_status");
        $xianjing_time = ConfigService::val("xianjing_time");
        $xianjing_num = ConfigService::val("xianjing_num");
        
        $replyKey = "";
        $replyVal = "";
        
        $reply_config_text = ConfigTextService::one("reply");
        if ($reply_config_text) {
            $keyy = $reply_config_text["keyy"];
            
            $replyKey = $keyy;
            $replyVal = $reply_config_text["val"];
        
            $reply_config_text["keyy"] = explode(",", $keyy);
        }
        
        $limits_temp = ConfigService::valLimit();
        $limits = [];
        foreach ($limits_temp as $item) {
            $limits[$item["key"]] = $item["val"];
        }
    
        return view('config.index')->with(compact("hour_notice_status", "switch_group_status",
            "welcome_true_status", "welcome_false_status", "limit_time", "limit_num",
            "limit_all_time", "limit_all_group_num", "limit_cancel_restrict", "limit_text_len", "reply_config_text", "replyKey", "replyVal",
            "photo_limit_type_num", "photo_limit_time", "photo_limit_day", "limits", "xianjing_status", "xianjing_time", "xianjing_num"));
    }
}