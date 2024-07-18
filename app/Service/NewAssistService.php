<?php

namespace App\Service;

class NewAssistService
{
    public static function handle_text($text)
    {
        $text = str_replace(" ", "", $text);
        $text = str_replace("𝅹", "", $text);
        $text = str_replace(",", "", $text);
        $text = str_replace(".", "", $text);
        $text = str_replace("，", "", $text);
        $text = str_replace("。", "", $text);
        $text = str_replace("+", "", $text);
        $text = str_replace("-", "", $text);
        $text = str_replace("*", "", $text);
        $text = str_replace("/", "", $text);
        $text = str_replace("(", "", $text);
        $text = str_replace("（", "", $text);
        $text = str_replace(")", "", $text);
        $text = str_replace("）", "", $text);
        $text = str_replace("、", "", $text);
        $text = str_replace("'", "", $text);
        $text = str_replace("​", "", $text);
        
        $text = htmlspecialchars($text);

        return $text;
    }
    
    public static function handleChat($chat)
    {
        $chat_id = $chat["id"];
        $username = "";
        $title = "";
        $type = "";

        if (is_right_data($chat, "username")) {
            $username = $chat["username"];
        }
        if (is_right_data($chat, "title")) {
            $title = $chat["title"];
        }
        if (is_right_data($chat, "type")) {
            $type = $chat["type"];
        }

        return [
            "id" => $chat_id,
            "chat_id" => $chat_id,
            "tg_id" => $chat_id,
            "group_tg_id" => $chat_id,
            "username" => $username,
            "title" => $title,
            "type" => $type,
        ];
    }

    public static function handleFrom($from)
    {
        $user_id = $from["id"];
        $username = "";
        $first_name = "";
        $last_name = "";
        
        $is_bot = false;
        if (is_right_data($from, "username")) {
            $username = $from["username"];
        }
        if (is_right_data($from, "first_name")) {
            $first_name = $from["first_name"];
        }
        if (is_right_data($from, "last_name")) {
            $last_name = $from["last_name"];
        }
        if (is_right_data($from, "is_bot")) {
            $is_bot = $from["is_bot"];
        }
        
        $first_name = static::handle_text($first_name);
        $last_name = static::handle_text($last_name);
        
        $fullname = $first_name . $last_name;
        $full_name = $fullname;
        
        return [
            "id" => $user_id,
            "user_id" => $user_id,
            "tg_id" => $user_id,
            "user_tg_id" => $user_id,
            "username" => $username,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "firstname" => $first_name,
            "lastname" => $last_name,
            "fullname" => $fullname,
            "full_name" => $full_name,
            "is_bot" => $is_bot,
        ];
    }
    
    public static function handleInviteLink($link)
    {
        $obj = [
            "invite_link" => "",
            "name" => "",
            "creator" => "",
            "expire_date" => -1,
            "pending_join_request_count" => -1,
            "creates_join_request" => -1,
            "is_primary" => -1,
            "is_revoked" => -1,
            "member_limit" => -1,
        ];
        
        if (is_right_data($link, "invite_link")) {
            $obj["invite_link"] = $link["invite_link"];
        }
        if (is_right_data($link, "name")) {
            $obj["name"] = $link["name"];
        }
        if (is_right_data($link, "creator")) {
            $obj["creator"] = static::handleFrom($link["creator"]);
        }
        if (is_right_data($link, "expire_date")) {
            $obj["expire_date"] = $link["expire_date"];
        }
        if (is_right_data($link, "pending_join_request_count")) {
            $obj["pending_join_request_count"] = $link["pending_join_request_count"];
        }
        if (is_right_data($link, "creates_join_request")) {
            $obj["creates_join_request"] = $link["creates_join_request"] ? 1 : 2;
        }
        if (is_right_data($link, "is_primary")) {
            $obj["is_primary"] = $link["is_primary"] ? 1 : 2;
        }
        if (is_right_data($link, "is_revoked")) {
            $obj["is_revoked"] = $link["is_revoked"] ? 1: 2;
        }
        
        return $obj;
    }
}