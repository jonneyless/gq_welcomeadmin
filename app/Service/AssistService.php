<?php

namespace App\Service;

class AssistService
{
    public static function saveRedisData4handleUser($data)
    {
        $redis = app('redis.connection');
        $redis->select(1);
        $key = "handle_user";

        $redis->rpush($key, json_encode($data));
    }

    public static function saveRedisData4handleMesage($data)
    {
        $redis = app('redis.connection');
        $redis->select(1);
        $key = "handle_message";

        $redis->rpush($key, json_encode($data));
    }

    public static function saveRedisData4TG($data)
    {
        $redis = app('redis.connection');
        $redis->select(1);
        $key = "welcome_admin_tg";

        $redis->rpush($key, json_encode($data));
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
        
        $fullname = "";
        $full_name = "";
        
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
        
        $first_name = htmlspecialchars($first_name);
        $last_name = htmlspecialchars($last_name);
        
        $fullname = $first_name . $last_name;
        $full_name = $fullname;
        

        return [
            "id" => $user_id,
            "user_id" => $user_id,
            "tg_id" => $user_id,
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

}