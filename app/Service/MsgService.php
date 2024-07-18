<?php

namespace App\Service;

use App\Models\Msg;
use Illuminate\Support\Facades\Cache;

class MsgService
{
    public static function get($condition = [])
    {
        $query = Msg::query();

        if (is_right_data($condition, "chat_id")) {
            $query->where("chat_id", $condition["chat_id"]);
        }
        if (is_right_data($condition, "user_id")) {
            $query->where("user_id", $condition["user_id"]);
        }

        return $query->get();
    }

    public static function create($data = [])
    {
        $msg = new Msg();
        $msg->chat_id = $data["chat_id"];
        $msg->message_id = $data["message_id"];
        $msg->user_id = $data["user_id"];
        $msg->info = $data["info"];
        $msg->created_at = date("Y-m-d H:i:s");
        $msg->save();
    }

    public static function delete($message)
    {
        $message->flag = 2;
        $message->save();
    }

    public static function search($condition = [])
    {
        $query = Msg::query();

        $query->where("created_at", ">=", date("Y-m-d"));
        
        if (is_right_data($condition, "flag")) {
            $query->where("flag", $condition["flag"]);
        }
        if (is_right_data($condition, "group_tg_id")) {
            $query->where("chat_id", $condition["group_tg_id"]);
        }
        if (is_right_data($condition, "user_tg_id")) {
            $query->where("user_id", $condition["user_tg_id"]);
        }
        if (is_right_data($condition, "search_text")) {
            $query->where("info", "like", "%" . $condition["search_text"] . "%");
        }
        
        $except_game = false; // 默认所有用户
        if (is_right_data($condition, "except_game") && $condition["except_game"] == 1) {
            $except_game = true;
        }

        $page = false; // 默认不分页
        if (is_right_data($condition, "page") && is_numeric($condition["page"])) {
            $page = true;
        }

        $langType = 999;
        if (is_right_data($condition, "langType")) {
            $langType = $condition["langType"];
        }

        if ($page) {
            return static::search_page($query, $condition, $except_game, $langType);
        } else {
            return static::search_no_page($query, $except_game, $langType);
        }
    }

    public static function search_page($query, $condition, $except_game, $langType = 999)
    {
        $queryTemp = $query;

        $count = $queryTemp->count("id");

        $page = 1;
        if (is_right_data($condition, "page")) {
            $page = $condition["page"];
        }

        $page_len = 10;
        if (is_right_data($condition, "page_len")) {
            $page_len = $condition["page_len"];
        }

        $data = $query->skip(($page - 1) * $page_len)
            ->limit($page_len)
            ->select("id", "chat_id as group_tg_id", "user_id as user_tg_id", "message_id as message_tg_id", "info", "flag as status", "created_at")
            ->get();
        $data = obj_to_array($data);

        $groups = GroupService::cache();

        foreach ($data as $key => $item) {
            $user_tg_id = $item["user_tg_id"];
            $key_user = $user_tg_id . "user";
            if (Cache::has($key)) {
                $userObj = Cache::get($key_user);
                $userObj = json_decode($userObj, true);
                
                $data[$key]["user_tg_id"] = $userObj["tg_id"];
                $data[$key]["user_username"] = $userObj["username"];
                $data[$key]["user_fullname"] = $userObj["fullname"];
            } else {
                $userObj = [
                    "tg_id" => "",
                    "username" => "",
                    "fullname" => "",
                ];
                $user = UserService::one([
                    "tg_id" => $user_tg_id,
                ]);
                if ($user) {
                    $userObj = [
                        "tg_id" => $user_tg_id,
                        "username" => $user["username"],
                        "fullname" => $user["fullname"],
                    ];
                    Cache::put($key_user, json_encode($userObj), 10);
                }
                
                $data[$key]["user_tg_id"] = $userObj["tg_id"];
                $data[$key]["user_username"] = $userObj["username"];
                $data[$key]["user_fullname"] = $userObj["fullname"];
            }
            
            $data[$key]["title"] = $item["group_tg_id"];

            foreach ($groups as $group) {
                if ($group["tg_id"] == $item["group_tg_id"]) {
                    if ($except_game) {
                        if ($group["flag"] == 4) {
                            unset($data[$key]);
                        }
                    } else {
                        $data[$key]["title"] = $group["title"];
                    }
                    
                    break;
                }
            }
            
            $user_fullname = $data[$key]["user_fullname"];
            if ($langType == 2) {
                if (haveChinese($user_fullname)) {
                    unset($data[$key]);
                }
            }
        }

        return compact("count", "data");
    }

    private static function search_no_page($query, $except_game, $langType = 999)
    {
        $data = $query
            ->select("id", "chat_id as group_tg_id", "user_id as user_tg_id", "message_id as message_tg_id", "info", "flag as status", "created_at")
            ->orderBy("id", "desc")
            ->get();
            
        $data = obj_to_array($data);
        
        if ($except_game) {
            $groups = GroupService::cache();
            foreach ($data as $key => $item) {
                foreach ($groups as $group) {
                    if ($group["tg_id"] == $item["group_tg_id"]) {
                        if ($group["flag"] == 4) {
                            unset($data[$key]);
                        }
                    }
                }
            }
        }
        
        if ($langType == 2) {
            foreach ($data as $key => $item) {
                $user_tg_id = $item["user_tg_id"];
                $key_user = $user_tg_id . "user";
                if (Cache::has($key)) {
                    $userObj = Cache::get($key_user);
                    $userObj = json_decode($userObj, true);
                    
                    $data[$key]["user_tg_id"] = $userObj["tg_id"];
                    $data[$key]["user_username"] = $userObj["username"];
                    $data[$key]["user_fullname"] = $userObj["fullname"];
                } else {
                    $userObj = [
                        "tg_id" => "",
                        "username" => "",
                        "fullname" => "",
                    ];
                    $user = UserService::one([
                        "tg_id" => $user_tg_id,
                    ]);
                    if ($user) {
                        $userObj = [
                            "tg_id" => $user_tg_id,
                            "username" => $user["username"],
                            "fullname" => $user["fullname"],
                        ];
                        Cache::put($key_user, json_encode($userObj), 10);
                    }
                    
                    $data[$key]["user_tg_id"] = $userObj["tg_id"];
                    $data[$key]["user_username"] = $userObj["username"];
                    $data[$key]["user_fullname"] = $userObj["fullname"];
                }

                $user_fullname = $data[$key]["user_fullname"];
                if ($langType == 2) {
                    if (haveChinese($user_fullname)) {
                        unset($data[$key]);
                    }
                }
            }
        }
        
        return $data;
    }
}