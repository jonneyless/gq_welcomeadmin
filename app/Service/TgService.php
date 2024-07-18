<?php

namespace App\Service;

use Illuminate\Support\Facades\Log;

class TgService
{
    public static function handleMsg48($message, $group_tg_id, $user_tg_id, $msg_tg_id, $created_at_timestamp)
    {
        if (is_right_data($message, "new_chat_members") or 
            is_right_data($message, "left_chat_member") or
            is_right_data($message, "pinned_message") or 
            is_right_data($message, "new_chat_title") or
            is_right_data($message, "boost_added") or 
            is_right_data($message, "new_chat_photo")) {
            return;
        }
        
        $group = GroupService::one_by_cache($group_tg_id);
        
        if (!$group) {
            return;
        }
        
        $text = "";
        if (is_right_data($message, "text")) {
            $text = $message["text"];
        }
        if (is_right_data($message, "caption")) {
            $text = $message["caption"];
        }
        if (is_right_data($message, "sticker") or 
            is_right_data($message, "video") or 
            is_right_data($message, "photo") or 
            is_right_data($message, "document") or 
            is_right_data($message, "dice") or 
            is_right_data($message, "voice") or 
            is_right_data($message, "poll") or 
            is_right_data($message, "contact") or 
            is_right_data($message, "location") or 
            is_right_data($message, "audio")) {
            $text = "media";
        }

        $flag = $group["flag"];
        $business_detail_type = $group["business_detail_type"];
        
        $created_at = date("Y-m-d H:i:s", $created_at_timestamp);
        
        $has_at = 2;
        $fullname_is_en = 2;
        if ($flag == 2) {
            if (is_right_data($message, "entities")) {
                $has_at = check_has_at($message["entities"]);
            } elseif (is_right_data($message, "caption_entities")) {
                $has_at = check_has_at($message["caption_entities"]);
            }

            $fromer = $message["from"]; # 判定过了
            $firstname = "";
            $lastname = "";
            if (is_right_data($fromer, "first_name")) {
                $firstname = $fromer["first_name"];
            }
            if (is_right_data($fromer, "last_name")) {
                $lastname = $fromer["last_name"];
            }
            $fullname = $firstname . $lastname;
            if (is_all_en($fullname)) {
                $fullname_is_en = 1;
            }
        }
        
        // Log::info($message);
        
        RedisService::saveMsg48(compact("group_tg_id", "user_tg_id", "msg_tg_id", "created_at", "created_at_timestamp", "has_at", "fullname_is_en", "flag", "business_detail_type"));
    }
    
    public static function handleIn($chat_member)
    {
        if (is_right_data($chat_member, "chat") and is_right_data($chat_member, "from")) {
            $chat = $chat_member["chat"];
            $from = $chat_member["from"];
            
            $chat = NewAssistService::handleChat($chat);
            $from = NewAssistService::handleFrom($from);
            
            // if ($chat["id"] != "-1001677560391") {
            //     return;
            // }
            // Log::info($chat_member);
            
            if (is_right_data($chat_member, "date")) {
                $created_at_timestamp = $chat_member["date"];
                
                $invite_link = false;
                if (is_right_data($chat_member, "invite_link")) {
                    $invite_link = $chat_member["invite_link"];
                    $invite_link = NewAssistService::handleInviteLink($invite_link);
                    $invite_link["created_at"] = date("Y-m-d H:i:s", $created_at_timestamp);
                }
                
                if (is_right_data($chat_member, "old_chat_member") and is_right_data($chat_member, "new_chat_member")) {
                    $old_chat_member = $chat_member["old_chat_member"];
                    $new_chat_member = $chat_member["new_chat_member"];
                    
                    if (is_right_data($old_chat_member, "status") and is_right_data($new_chat_member, "status") and is_right_data($old_chat_member, "user") and is_right_data($new_chat_member, "user")) {
                        $status_old = $old_chat_member["status"];
                        $status_new = $new_chat_member["status"];
                        
                        $user_old = $old_chat_member["user"];
                        $user_new = $new_chat_member["user"];
                        
                        $user_old = NewAssistService::handleFrom($user_old);
                        $user_new = NewAssistService::handleFrom($user_new);
                        
                        if ($user_new["id"] != $from["id"]) {
                            // 禁言或踢出或移除黑名单或拉人或通过链接进群的数据
                            // from为操作人, $user_old和$user_new为被操作人
                            // Log::info($chat_member);
                            $from = $user_new;
                        }
                        
                        if ($invite_link and $status_new == "member") {
                            if ($status_old == "left" or $status_old == "kicked") {
                                // 进群处理
                                $from_temp = $from;
                                $from_temp["group_tg_id"] = $chat["id"];
                                $from_temp["invite_link"] = $invite_link;
                                
                                // 处理新进群用户数据
                                // 用户判断发言中的变化
                                // 额外增加判断：用户的信息是否变化了
                                RedisService::changeUserNew($from_temp);
                            }
                        }

                        // 进群
                        // $status_old = left
                        // $status_new = member
                        
                        // 离群
                        // $status_old = left
                        // $status_new = member
                        
                        // 禁言
                        // $status_old = member
                        // $status_new = restricted
                        
                        // 更改禁言权限
                        // $status_old = restricted
                        // $status_new = restricted
                        
                        // 解除禁言
                        // $status_old = restricted
                        // $status_new = member
                        
                        // 踢出
                        // $status_old = member or restricted or administrator
                        // $status_new = kicked
                        
                        // 移除黑名单
                        // $status_old = kicked
                        // $status_new = left
                        
                        // 更改管理权限
                        // $status_old = administrator
                        // $status_new = administrator
                        
                        // 上管理
                        // $status_old = member or restricted 
                        // $status_new = administrator
                        
                        // 下管理
                        // $status_old = administrator
                        // $status_new = member or kicked
                        
                        // 被禁言用户离开, 禁言状态不变
                        // $status_old = restricted
                        // $status_new = left
                        
                        $is_admin = -1;
                        $status_in = -1;
                        $status_restrict = -1;
                        $status_ban = -1;
            
                        if ($status_old == "left" and $status_new == "member") {
                            $is_admin = 2;
                            $status_in = 1;
                            $status_restrict = 1;
                            $status_ban = 1;
                        } elseif ($status_old == "member" and $status_new == "left") {
                            $is_admin = 2;
                            $status_in = 2;
                            $status_restrict = 1;
                            $status_ban = 1;
                        } elseif ($status_old == "member" and $status_new == "restricted") {
                            $is_admin = 2;
                            $status_in = 1;
                            $status_restrict = 2;
                            $status_ban = 1;
                        }  elseif ($status_old == "restricted" and $status_new == "restricted") {
                            $is_admin = 2;
                            $status_in = 1;
                            $status_restrict = 2;
                            $status_ban = 1;
                        } elseif ($status_old == "restricted" and $status_new == "member") {
                            $is_admin = 2;
                            $status_in = 1;
                            $status_restrict = 1;
                            $status_ban = 1;
                        } elseif ($status_new == "kicked") {
                            $is_admin = 2;
                            $status_in = 2;
                            $status_restrict = 1;
                            $status_ban = 2;
                        } elseif ($status_old == "kicked" and $status_new == "left") {
                            $is_admin = 2;
                            $status_in = 2;
                            $status_restrict = 1;
                            $status_ban = 1;
                        } elseif ($status_old != "administrator" and $status_new == "administrator") {
                            $is_admin = 1;
                            $status_in = 1;
                            $status_restrict = 1;
                            $status_ban = 1;
                        } elseif ($status_old == "administrator" and $status_new != "administrator") {
                            // 下管理
                            $is_admin = 2;
                            if ($status_new == "kicked") {
                                $status_in = 2;
                                $status_restrict = 1;
                                $status_ban = 2;
                            } elseif ($status_new == "restricted") {
                                $status_in = 1;
                                $status_restrict = 2;
                                $status_ban = 1;
                            } elseif ($status_new == "member") {
                                $status_in = 1;
                                $status_restrict = 1;
                                $status_ban = 1;
                            } elseif ($status_new == "left") {
                                $status_in = 2;
                                $status_restrict = 1;
                                $status_ban = 1;
                            } else {
                                Log::info($chat_member);
                            }
                        } elseif ($status_old == "restricted" and $status_new == "left") {
                            $is_admin = 1;
                            $status_in = 2;
                            $status_restrict = 2;
                            $status_ban = 1;
                        } elseif ($status_old == "administrator" and $status_new == "administrator") {
                            $is_admin = 1;
                            $status_in = 1;
                            $status_restrict = 1;
                            $status_ban = 1;
                        }
                        
                        if ($is_admin == -1 and $status_in == -1 and $status_restrict == -1 and $status_ban == -1) {
                            if ($status_old == "kicked" and $status_new == "member") {
                                $is_admin = 2;
                                $status_in = 1;
                                $status_restrict = 1;
                                $status_ban = 1;
                            } elseif ($status_old == "left" and $status_new == "restricted") {
                                // 对已离群的用户禁言
                                $is_admin = 2;
                                $status_in = 2;
                                $status_restrict = 2;
                                $status_ban = 1;
                            } elseif($status_old == "kicked" and $status_new == "restricted") {
                                // 对已离群的黑名单用户禁言
                                $is_admin = 2;
                                $status_in = 2;
                                $status_restrict = 2;
                                $status_ban = 2;
                            } elseif ($status_old == "member" and $status_new == "member") {
                                // 批准审核链接的时候，客户已经在群里了。
                                $is_admin = 2;
                                $status_in = 1;
                                $status_restrict = 1;
                                $status_ban = 1;
                            } else {
                                Log::info($chat_member);
                            }
                        }

                        $data = compact("is_admin", "status_in", "status_restrict", "status_ban");
                        $data["group_tg_id"] = $chat["id"];
                        $data["user_tg_id"] = $from["id"];
                        $data["created_at"] = date("Y-m-d H:i:s", $created_at_timestamp);
                        $data["updated_at"] = $data["created_at"];
                        RedisService::changeUserGroupNew($data);
                    }
                }
            }
        }
    }
}