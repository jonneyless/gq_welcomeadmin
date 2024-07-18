<?php


namespace App\Service;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserService
{
    public static function one($condition = [])
    {
        $query = User::query();

        if (is_right_data($condition, "id")) {
            $query->where("id", $condition["id"]);
        }
        if (is_right_data($condition, "tg_id")) {
            $query->where("tg_id", $condition["tg_id"]);
        }

        $query->orderBy("id", "desc");

        return $query->first();
    }

    public static function get($condition = [])
    {
        $query = User::query();

        if (is_right_data($condition, "tg_id")) {
            $query->where("tg_id", $condition["tg_id"]);
        }

        $query->orderBy("id", "desc");

        return $query->get();
    }

    public static function search($condition = [])
    {
        $query = User::query();

        if (is_right_data($condition, "group_tg_id")) {
            $query->where("user_group.group_tg_id", $condition["group_tg_id"]);
        }

        if (is_right_data($condition, "search_text")) {
            $search_text = $condition["search_text"];
            $search_text = str_replace("@", "", $search_text);
            $search_type = 1;

            if (is_right_data($condition, "search_type")) {
                $search_type = $condition["search_type"];
            }

            if ($search_type == 1) {
                $query->where("fullname", "like", "%" . $search_text . "%");
            } elseif ($search_type == 2) {
                $query->where("username", "like", "%" . $search_text . "%");
            } elseif ($search_type == 3) {
                $query->where("fullname", $search_text);
            } elseif ($search_type == 4) {
                $query->where("username", $search_text);
            } elseif ($search_type == 5) {
                $query->where("tg_id", $search_text);
            }
        }
        
        if (is_right_data($condition, "group_tg_id")) {
            $query->where("user_group.group_tg_id", $condition["group_tg_id"]);
        }
        
        if (is_right_data($condition, "user_status")) {
            if (in_array($condition["user_status"], [1, 2, 3, 4])) {
                $query->where("user_group.status", $condition["user_status"]);
            }
        }
        
        $except_game = false; // 默认所有用户
        if (is_right_data($condition, "except_game") && $condition["except_game"] == 1) {
            $except_game = true;
        }

        $page = false; // 默认不分页
        if (is_right_data($condition, "page") && is_numeric($condition["page"])) {
            $page = true;
        }
        
        if ($page) {
            return static::search_page($query, $condition, $except_game);
        } else {
            return static::search_no_page($query, $except_game);
        }
    }

    private static function search_page($query, $condition, $except_game)
    {
        $query->leftJoin("user_group", "users_new.tg_id", "=", "user_group.user_tg_id");

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
        
        $data = $query->orderBy("user_group.id", "desc")
            ->skip(($page - 1) * $page_len)
            ->limit($page_len)
            ->select("users_new.id", "user_group.group_tg_id", "users_new.tg_id as user_tg_id", "users_new.username", "users_new.firstname", "users_new.lastname", "users_new.fullname", "user_group.status")
            ->get();
        $data = obj_to_array($data);
        
        $arr = [];
        $arr_temp = [];
        foreach ($data as $item) {
            $key = $item["group_tg_id"] . $item["user_tg_id"];
            if (!in_array($key, $arr_temp)) {
                array_push($arr, $item);
                array_push($arr_temp, $key);
            }
        }
        $data = $arr;
        
        $groups = GroupService::cache();
        
        foreach ($data as $key => $item) {
            $data[$key]["title"] = $item["group_tg_id"];

            foreach ($groups as $group) {
                if ($group["tg_id"] == $item["group_tg_id"]) {
                    $data[$key]["title"] = $group["title"];
                    
                    if ($except_game) {
                        if ($group["flag"] == 4) {
                            unset($data[$key]);
                        }
                    }
                    
                    break;
                }
            }
            
            $admin = GroupAdminService::get([
                "chat_id" => $item["group_tg_id"],
                "user_id" => $item["user_tg_id"],
                "is_one_obj" => true,
            ]);
            if ($admin) {
                $data[$key]["is_admin"] = 1;
            } else {
                $data[$key]["is_admin"] = 2;
            }
        }
        
        $data = array_values($data);
        
        return compact("count", "data");
    }

    private static function search_no_page($query, $except_game)
    {
        $data = $query->leftJoin("user_group", "users_new.tg_id", "=", "user_group.user_tg_id")
            ->orderBy("users_new.id", "desc")
            ->limit(8000)
            ->select("users_new.id", "user_group.group_tg_id", "user_group.user_tg_id", "users_new.username", "users_new.firstname", "users_new.lastname", "users_new.fullname", "user_group.status")
            ->get();

        $data = obj_to_array($data);
        
        $arr = [];
        $arr_temp = [];
        foreach ($data as $item) {
            $key = $item["group_tg_id"] . $item["user_tg_id"];
            if (!in_array($key, $arr_temp)) {
                array_push($arr, $item);
                array_push($arr_temp, $key);
            }
        }
        $data = $arr;
        
        if ($except_game) {
            $groups = GroupService::cache();
            
            foreach ($data as $key => $item) {
                foreach ($groups as $group) {
                    if ($group["tg_id"] == $item["group_tg_id"]) {
                        if ($except_game) {
                            if ($group["flag"] == 4) {
                                unset($data[$key]);
                            }
                        }
                        break;
                    }
                }
            }
            
            return $data;
        } else {
            return $data;
        }
    }
}