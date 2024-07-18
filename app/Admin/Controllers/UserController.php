<?php


namespace App\Admin\Controllers;

use App\DataModels\User as DataUser;
use App\Http\Controllers\Controller;
use App\Service\AssistService;
use App\Service\CheatService;
use App\Service\GroupService;
use App\Service\UserService;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

class UserController extends Controller
{
    public function index(Content $content)
    {
        Admin::disablePjax();
        return $content
            ->title('用户管理')
            ->description(' ')
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->append(DataUser::index());
                });
            });
    }

    public function search()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "search_text")) {
            return handle_response([], "搜索内容不能为空");
        }
        if (is_wrong_data($parameters, "search_type")) {
            return handle_response([], "搜索类型不能为空");
        }

        $result = UserService::search($parameters);

        return handle_response($result, "success");
    }

    public function kick()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "all")) {
            return handle_response([], "error");
        }

        $admin = auth()->user();

        if ($parameters["all"] == 1) {
            // 存入缓存等待处理
            AssistService::saveRedisData4handleUser([
                "type" => "kick",
                "all" => 1,
                "search_type" => $parameters["search_type"],
                "search_text" => $parameters["search_text"],
                "except_game" => $parameters["except_game"],
                "admin_id" => $admin["id"],
            ]);
            return handle_response([], "success");
        } else {
            if (is_wrong_data($parameters, "users")) {
                return handle_response([], "error");
            }
            // 直接存入redis进行处理
            $users = $parameters["users"];

            foreach ($users as $user) {
                AssistService::saveRedisData4TG([
                    "type_ops" => "ban",
                    "group_tg_id" => $user["group_tg_id"],
                    "user_tg_id" => $user["user_tg_id"],
                    "admin_id" => $admin["id"],
                ]);
            }

            return handle_response([], "success");
        }
    }

    public function restrict()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "all")) {
            return handle_response([], "error");
        }

        if (is_wrong_data($parameters, "day")) {
            return handle_response([], "error");
        }

        $day = intval($parameters["day"]);
        $admin = auth()->user();

        if ($parameters["all"] == 1) {
            // 存入缓存等待处理
            AssistService::saveRedisData4handleUser([
                "type" => "restrict",
                "all" => 1,
                "search_type" => $parameters["search_type"],
                "search_text" => $parameters["search_text"],
                "except_game" => $parameters["except_game"],
                "day" => $day,
                "admin_id" => $admin["id"],
            ]);
            return handle_response([], "success");
        } else {
            if (is_wrong_data($parameters, "users")) {
                return handle_response([], "error");
            }
            // 直接存入redis进行处理
            $users = $parameters["users"];

            foreach ($users as $user) {
                AssistService::saveRedisData4TG([
                    "type_ops" => "restrict",
                    "group_tg_id" => $user["group_tg_id"],
                    "user_tg_id" => $user["user_tg_id"],
                    "until_date" => $day,
                    // "until_date" => intval(time() + 86400 * $day),
                    "admin_id" => $admin["id"],
                ]);
            }

            return handle_response([], "success");
        }
    }

    public function deleteAndRestrict()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "all")) {
            return handle_response([], "error");
        }

        $day = intval($parameters["day"]);
        $admin = auth()->user();

        $data = [
            "type" => "deleteAndRestrict",
            "all" => $parameters["all"],
            "search_type" => $parameters["search_type"],
            "search_text" => $parameters["search_text"],
            "except_game" => $parameters["except_game"],
            "users" => [],
            "day" => $day,
            "admin_id" => $admin["id"],
        ];

        if ($parameters["all"] == 2) {
            $data["users"] = $parameters["users"];
        }

        AssistService::saveRedisData4handleUser($data);

        return handle_response([], "success");
    }

    public function cancelRestrict()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "all")) {
            return handle_response([], "error");
        }

        $admin = auth()->user();

        if ($parameters["all"] == 1) {
            // 存入缓存等待处理
            AssistService::saveRedisData4handleUser([
                "type" => "cancel_restrict",
                "all" => 1,
                "search_type" => $parameters["search_type"],
                "search_text" => $parameters["search_text"],
                "except_game" => $parameters["except_game"],
                "admin_id" => $admin["id"],
            ]);
            return handle_response([], "success");
        } else {
            if (is_wrong_data($parameters, "users")) {
                return handle_response([], "error");
            }
            // 直接存入redis进行处理
            $users = $parameters["users"];

            foreach ($users as $user) {
                AssistService::saveRedisData4TG([
                    "type_ops" => "cancel_restrict",
                    "group_tg_id" => $user["group_tg_id"],
                    "user_tg_id" => $user["user_tg_id"],
                    "admin_id" => $admin["id"],
                ]);
            }

            return handle_response([], "success");
        }
    }

    public function deleteAndKick()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "all")) {
            return handle_response([], "error");
        }

        $admin = auth()->user();

        $data = [
            "type" => "deleteAndKick",
            "all" => $parameters["all"],
            "search_type" => $parameters["search_type"],
            "search_text" => $parameters["search_text"],
            "except_game" => $parameters["except_game"],
            "users" => [],
            "admin_id" => $admin["id"],
        ];

        if ($parameters["all"] == 2) {
            $data["users"] = $parameters["users"];
        }

        AssistService::saveRedisData4handleUser($data);

        return handle_response([], "success");
    }

    public function unban()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "all")) {
            return handle_response([], "error");
        }

        $admin = auth()->user();

        if ($parameters["all"] == 1) {
            // 存入缓存等待处理
            AssistService::saveRedisData4handleUser([
                "type" => "unban",
                "all" => 1,
                "search_type" => $parameters["search_type"],
                "search_text" => $parameters["search_text"],
                "except_game" => $parameters["except_game"],
                "admin_id" => $admin["id"],
            ]);
            return handle_response([], "success");
        } else {
            if (is_wrong_data($parameters, "users")) {
                return handle_response([], "error");
            }
            // 直接存入redis进行处理
            $users = $parameters["users"];

            foreach ($users as $user) {
                AssistService::saveRedisData4TG([
                    "type_ops" => "unban",
                    "group_tg_id" => $user["group_tg_id"],
                    "user_tg_id" => $user["user_tg_id"],
                    "admin_id" => $admin["id"],
                ]);
            }

            return handle_response([], "success");
        }
    }

    public function unbanall()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "all")) {
            return handle_response([], "error");
        }

        $admin = auth()->user();

        if ($parameters["all"] == 1) {
            // 存入缓存等待处理
            AssistService::saveRedisData4handleUser([
                "type" => "unbanall",
                "all" => 1,
                "search_type" => $parameters["search_type"],
                "search_text" => $parameters["search_text"],
                "except_game" => $parameters["except_game"],
                "admin_id" => $admin["id"],
            ]);
            return handle_response([], "success");
        } else {
            if (is_wrong_data($parameters, "users")) {
                return handle_response([], "error");
            }
            // 直接存入redis进行处理
            $users = $parameters["users"];

            foreach ($users as $user) {
                $groups = GroupService::cache();
                foreach ($groups as $group) {
                    AssistService::saveRedisData4TG([
                        "type_ops" => "unban",
                        "group_tg_id" => $group["tg_id"],
                        "user_tg_id" => $user["user_tg_id"],
                        "admin_id" => $admin["id"],
                    ]);
                }
            }

            return handle_response([], "success");
        }
    }

    public function addCheat()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "all")) {
            return handle_response([], "error");
        }

        $admin = auth()->user();

        $reason = "";
        if (is_right_data($parameters, "reason")) {
            $reason = $parameters["reason"];
        }

        if ($parameters["all"] == 1) {
            // 存入缓存等待处理
            AssistService::saveRedisData4handleUser([
                "type" => "addCheat",
                "all" => 1,
                "search_type" => $parameters["search_type"],
                "search_text" => $parameters["search_text"],
                "except_game" => $parameters["except_game"],
                "admin_id" => $admin["id"],
                "reason" => $reason,
            ]);
            return handle_response([], "success");
        } else {
            if (is_wrong_data($parameters, "users")) {
                return handle_response([], "error");
            }
            // 直接存入redis进行处理
            $users = $parameters["users"];

            foreach ($users as $user) {
                CheatService::create($user["user_tg_id"], $admin["id"], $reason);
            }

            return handle_response([], "success");
        }
    }
}