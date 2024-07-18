<?php

// =====================================================================================================================
// use项目

// =====================================================================================================================
// 项目基础方法

if (!function_exists("handle_response")) {
    function handle_response($data = array(), $message = "", $code = 200)
    {
        return response()->json(compact("data", "message"), $code);
    }
}

if (!function_exists("curlGet")) {
    function curlGet($url, $data)
    {
        if (empty($url) || empty($data)) {
            return false;
        }

        $url = $url . "?" . http_build_query($data);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);// 返回内容不输出到页面
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        $output = curl_exec($curl);

//        Log::info(curl_error($curl));
//        dd(curl_error($curl));

        if (!$output) {
            return false;
        }
        curl_close($curl);


        return json_decode($output, true);
    }
}

if (!function_exists("curlPost")) {
    function curlPost($url, $data)
    {
        if(empty($url) || empty($data))
        {
            return false;
        }
        
        $headers = array('Content-Type:application/json');
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        if(!$output)
        {
            $temp = curl_errno($curl);
            return false;
        }
        curl_close($curl);

        return json_decode($output, true);
    }
}

if (!function_exists("obj_to_array")) {
    function obj_to_array($obj)
    {
        return is_null($obj) ? [] : $obj->toArray();
    }
}

if (!function_exists("is_right_data")) {
    function is_right_data($data, $key)
    {
        // 定义了数据且数据为 真, true, 0, "0"
        $result = false;
        if (isset($data[$key])) {
            if (is_numeric($data[$key])) {
                $result = true;
            } elseif (is_bool($data[$key])) {
                $result = true;
            } else {
                if ($data[$key]) {
                    $result = true;
                }
            }
        }
        return $result;
    }
}

if (!function_exists("is_wrong_data")) {
    function is_wrong_data($data, $key)
    {
        // 没有定义了数据
        // 数据定义了但数据为 null
        // 数据定义了但数据为 ""
        // 数据定义了但数据为 []空数组
        $result = false;
        if (!isset($data[$key])) {
            $result = true;
        }
        if (isset($data[$key])) {
            if (is_null($data[$key])) {
                $result = true;
            } elseif (is_numeric($data[$key])) {
                $result = false;
            } elseif (is_bool($data[$key])) {
                $result = false;
            } else {
                if (!$data[$key]) {
                    $result = true;
                }
            }
        }

        return $result;
    }
}

// =====================================================================================================================
// 项目相关方法

if (!function_exists("getTgUrl")) {
    function getTgUrl()
    {
        return config("constants.bot_url_welcome");
    }
}

if (!function_exists("getWebhookInfo")) {
    function getWebhookInfo()
    {
        $base_url = getTgUrl() . "getWebhookInfo";

        return curlGet($base_url, ["aaa" => 1]);
    }
}

if (!function_exists("deleteWebhook")) {
    function deleteWebhook()
    {
        $base_url = getTgUrl() . "deleteWebhook";

        return curlGet($base_url, ["drop_pending_updates" => true]);
    }
}

if (!function_exists("setWebhook")) {
    function setWebhook()
    {
        $base_url = getTgUrl() . "setWebhook";
        $data = [
            "url" => "https://checktest.qffda.xyz/api/tg",
            "ip_address" => "13.212.178.202",
            "max_connections" => 100,
            "allowed_updates" => json_encode([
                "channel_post",
                "chat_member",
                "message",
                "edited_message",
                "edited_channel_post",
                "callback_query",
                "chat_join_request",
            ]),
        ];

        return curlGet($base_url, $data);
    }
}

if (!function_exists("adminUrl")) {
    function adminUrl($path)
    {
        $path = "/" . config('admin.route.prefix') . "/" . $path;

        return url($path);
    }
}

if (!function_exists("haveChinese")) {
    function haveChinese($text)
    {
        $result = false;
        if (preg_match('/[\x{4e00}-\x{9fa5}]/u', $text) > 0) {
            $result = true;
        }

        return $result;
    }
}

// ---------------------------------------------------------------------------------------------------------------------
// tg 相关方法
// 发送数据给 TG

if (!function_exists("sendMessage")) {
    function sendMessage($chat_id, $text)
    {
        $tgUrl = getTgUrl() . "sendMessage";

        return curlGet($tgUrl, compact("chat_id", "text"));
    }
}

if (!function_exists("updateChatTitle")) {
    function updateChatTitle($chat_id, $title)
    {
        $tgUrl = getTgUrl() . "setChatTitle";

        return curlGet($tgUrl, compact("chat_id", "title"));
    }
}

if (!function_exists("deleteMessage")) {
    function deleteMessage($chat_id, $message_id)
    {
        $tgUrl = getTgUrl() . "deleteMessage";

        return curlGet($tgUrl, compact("chat_id", "message_id"));
    }
}

if (!function_exists("banChatMember")) {
    function banChatMember($chat_id, $user_id)
    {
        $tgUrl = getTgUrl() . "banChatMember";

        return curlGet($tgUrl, compact("chat_id", "user_id"));
    }
}

if (!function_exists("unbanChatMember")) {
    function unbanChatMember($chat_id, $user_id)
    {
        $only_if_banned = true;
        $tgUrl = getTgUrl() . "unbanChatMember";

        return curlGet($tgUrl, compact("chat_id", "user_id", "only_if_banned"));
    }
}

if (!function_exists("approveChatJoinRequest")) {
    function approveChatJoinRequest($chat_id, $user_id)
    {
        $base_url = getTgUrl() . "approveChatJoinRequest";

        $flag = true;
        $num = 0;
        $result = false;

        while ($flag && $num < 3) {
            $result = curlGet($base_url, compact("chat_id", "user_id"));

            $num = $num + 1;
            if ($result && is_right_data($result, "ok") && $result["ok"]) {
                $flag = false;
                $num = 9;
            }
        }

        return $result;
    }
}

if (!function_exists("declineChatJoinRequest")) {
    function declineChatJoinRequest($chat_id, $user_id)
    {
        $base_url = getTgUrl() . "declineChatJoinRequest";

        $flag = true;
        $num = 0;
        $result = false;

        while ($flag && $num < 3) {
            $result = curlGet($base_url, compact("chat_id", "user_id"));

            $num = $num + 1;
            if ($result && is_right_data($result, "ok") && $result["ok"]) {
                $flag = false;
                $num = 9;
            }
        }

        return $result;
    }
}

if (!function_exists("restrictUser")) {
    function restrictUser($chat_id, $user_id, $day = 7)
    {
        $tgUrl = getTgUrl() . "restrictChatMember";
        $data = [
            "chat_id" => $chat_id,
            "user_id" => $user_id,
            "permissions" => [
                "can_send_messages" => false,
            ],
            "until_date" => time() + 3600 * 24 * $day
        ];

        return curlGet($tgUrl, $data);
    }
}

if (!function_exists("cancelRestrictUser")) {
    function cancelRestrictUser($chat_id, $user_id)
    {
        $tgUrl = getTgUrl() . "restrictChatMember";
        $data = [
            "chat_id" => $chat_id,
            "user_id" => $user_id,
            "permissions" => json_encode([
                "can_send_messages" => true,
                "can_send_media_messages" => true,
                "can_send_polls" => true,
                "can_send_other_messages" => true,
                "can_add_web_page_previews" => true,
                "can_change_info" => true,
                "can_invite_users" => true,
                "can_pin_messages" => true,
            ]),
            "until_date" => time() + 3600 * 24 * 999
        ];

        return curlGet($tgUrl, $data);
    }
}

if (!function_exists("getChat")) {
    function getChat($chat_id)
    {
        $tgUrl = getTgUrl() . "getChat";
        return curlGet($tgUrl, compact("chat_id"));
    }
}

if (!function_exists("getChatMembersCount")) {
    function getChatMembersCount($chat_id)
    {
        $tgUrl = getTgUrl() . "getChatMembersCount";
        return curlGet($tgUrl, compact("chat_id"));
    }
}

if (!function_exists("getChatAdministrators")) {
    function getChatAdministrators($chat_id)
    {
        $tgUrl = getTgUrl() . "getChatAdministrators";
        return curlGet($tgUrl, compact("chat_id"));
    }
}

if (!function_exists("pinChatMessage")) {
    function pinChatMessage($chat_id, $message_id)
    {
        $tgUrl = getTgUrl() . "pinChatMessage";
        return curlGet($tgUrl, compact("chat_id", "message_id"));
    }
}

if (!function_exists("setChatPermissions")) {
    function setChatPermissions($chat_id, $open = true)
    {
        $base_url = getTgUrl() . "setChatPermissions";
        $data = [
            "chat_id" => $chat_id,
            "permissions" => json_encode([
                "can_send_messages" => $open,
                "can_send_media_messages" => $open,
                "can_send_other_messages" => $open,
                "can_add_web_page_previews" => $open,
                "can_invite_users" => true,
            ]),
        ];
        return curlGet($base_url, $data);
    }
}

if (!function_exists("getChatMember")) {
    function getChatMember($chat_id, $user_id)
    {
        $tgUrl = getTgUrl() . "getChatMember";
        return curlGet($tgUrl, compact("chat_id", "user_id"));
    }
}

if (!function_exists("leaveChat")) {
    function leaveChat($chat_id)
    {
        $tgUrl = getTgUrl() . "leaveChat";
        return curlGet($tgUrl, compact("chat_id"));
    }
}

if (!function_exists("setAdmin")) {
    function setAdmin($chat_id, $user_id)
    {
        $tgUrl = "https://api.telegram.org/bot5995027011:AAFbO4lMOnv-AYbDYT2NTtLFJ79FkcON5jE/promoteChatMember";

        $data = [
            "chat_id" => $chat_id,
            "user_id" => $user_id,
            // "can_manage_chat" => true,
            // "can_post_messages" => true,
            // "can_edit_messages" => true,
            "can_delete_messages" => true,
            "can_manage_voice_chats" => true,
            "can_restrict_members" => true,
            "can_promote_members" => true,
            "can_change_info" => true,
            "can_invite_users" => true,
            "can_pin_messages" => true,
        ];

        return curlGet($tgUrl, $data);
    }
}

if (!function_exists("setAdmin")) {
    function setEmptyAdmin($chat_id, $user_id)
    {
        $tgUrl = "https://api.telegram.org/bot5995027011:AAFbO4lMOnv-AYbDYT2NTtLFJ79FkcON5jE/promoteChatMember";

        $data = [
            "chat_id" => $chat_id,
            "user_id" => $user_id,
            // "can_manage_chat" => true,
            // "can_post_messages" => true,
            // "can_edit_messages" => true,
            "can_delete_messages" => false,
            "can_manage_voice_chats" => false,
            "can_restrict_members" => false,
            "can_promote_members" => false,
            "can_change_info" => false,
            "can_invite_users" => false,
            "can_pin_messages" => false,
        ];

        return curlGet($tgUrl, $data);
    }
}

if (!function_exists("setAdminSpecial")) {
    function setAdminSpecial($chat_id, $user_id)
    {
        $tgUrl = getTgUrl() . "promoteChatMember";

        $data = [
            "chat_id" => $chat_id,
            "user_id" => $user_id,
            "can_manage_chat" => false,
            // "can_post_messages" => false,
            // "can_edit_messages" => false,
            "can_delete_messages" => true, // 开关
            "can_manage_voice_chats" => false, // 开关
            "can_restrict_members" => false, // 开关
            "can_promote_members" => false,
            "can_change_info" => false,
            "can_invite_users" => false, // 1
            "can_pin_messages" => false, // 1
        ];

        return curlGet($tgUrl, $data);
    }
}

if (!function_exists("removeAdmin")) {
    function removeAdmin($chat_id, $user_id)
    {
        $tgUrl = getTgUrl() . "promoteChatMember";

        $data = [
            "chat_id" => $chat_id,
            "user_id" => $user_id,
            // "can_manage_chat" => true,
            // "can_post_messages" => true,
            // "can_edit_messages" => true,
            "can_delete_messages" => false,
            "can_manage_voice_chats" => false,
            "can_restrict_members" => false,
            "can_promote_members" => false,
            "can_change_info" => false,
            "can_invite_users" => false,
            "can_pin_messages" => false,
        ];

        return curlGet($tgUrl, $data);
    }
}

if (!function_exists("setChatAdministratorCustomTitle")) {
    function setChatAdministratorCustomTitle($chat_id, $user_id, $custom_title)
    {
        $tgUrl = getTgUrl() . "setChatAdministratorCustomTitle";

        $data = [
            "chat_id" => $chat_id,
            "user_id" => $user_id,
            "custom_title" => $custom_title,
        ];

        return curlGet($tgUrl, $data);
    }
}

// =====================================================================================================================

if (!function_exists("curlPostPhoto")) {
    function curlPostPhoto($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1); //POST提交
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: multipart/form-data;",
        ));
        $data =curl_exec($ch);
        curl_close($ch);
        
        return $data;
    }   
}

if (!function_exists("setGroupInitPhoto")) {
    function setGroupInitPhoto($chat_id) 
    {
        $tgUrl = getTgUrl() . "setChatPhoto";
        
        $filename = "/www/wwwroot/gongqun-admin-php/public/img/gong.jpg";
        $photo = curl_file_create($filename, "image/jpeg", "hwdb");
        
        $data = [
            "chat_id" => $chat_id,
            "photo" => $photo,
        ];
                
        $result = curlPostPhoto($tgUrl, $data);
        $flag = false;
        
        dd($result);
    }
}

if (!function_exists("transferJz")) {
    function transferJz($from_group_tg_id, $to_group_tg_id) 
    {
        $url = "http://jz.admin.com:8680/api/transfer";
        
        $data = [
            "key" => "huionedb",
            "from_group_tg_id" => $from_group_tg_id,
            "to_group_tg_id" => $to_group_tg_id
        ];
        
        return curlGet($url, $data);
    }
}

if (!function_exists("is_all_en")) {
    function is_all_en($str)
    {
        return preg_match('/^[A-Za-z]+$/', $str) ? true : false;
    }
}

if (!function_exists("check_has_at")) {
    function check_has_at($entities)
    {
        $flag = false;
        
        foreach ($entities as $item) {
            if (is_right_data($item, "type") and $item["type"] == "mention") {
                $flag = true;
            }
        }
        
        return $flag ? 1 : 2;
    }
}