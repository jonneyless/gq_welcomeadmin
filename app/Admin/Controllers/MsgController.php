<?php


namespace App\Admin\Controllers;

use App\DataModels\Msg as DataMsg;
use App\Http\Controllers\Controller;
use App\Service\AssistService;
use App\Service\CheatService;
use App\Service\MsgService;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

class MsgController extends Controller
{
    public function index(Content $content)
    {
        // // $user = auth()->user();
        // // dd($user);
        
        // dd(auth()->user()->isRole('administrator'));
        
        
        Admin::disablePjax();
        return $content
            ->title('聊天记录管理')
            ->description('只显示当日数据')
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->append(DataMsg::index());
                });
            });
    }

    public function search()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "search_text")) {
            return handle_response([], "搜索内容不能为空");
        }

        $result = MsgService::search($parameters);

        return handle_response($result, "success");
    }

    public function delete()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "all")) {
            return handle_response([], "error");
        }

        $admin = auth()->user();

        if ($parameters["all"] == 1) {
            // 存入缓存等待处理
            AssistService::saveRedisData4handleMesage([
                "type" => "delete",
                "all" => 1,
                "search_text" => $parameters["search_text"],
                "except_game" => $parameters["except_game"],
                "admin_id" => $admin["id"],
            ]);
            return handle_response([], "success");
        } else {
            if (is_wrong_data($parameters, "msgs")) {
                return handle_response([], "error");
            }
            // 直接存入redis进行处理
            $msgs = $parameters["msgs"];

            foreach ($msgs as $msg) {
                AssistService::saveRedisData4TG([
                    "type_ops" => "delete",
                    "group_tg_id" => $msg["group_tg_id"],
                    "user_tg_id" => $msg["user_tg_id"],
                    "message_tg_id" => $msg["message_tg_id"],
                    "admin_id" => $admin["id"],
                ]);
            }

            return handle_response([], "success");
        }
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
            AssistService::saveRedisData4handleMesage([
                "type" => "ban",
                "all" => 1,
                "search_text" => $parameters["search_text"],
                "except_game" => $parameters["except_game"],
                "admin_id" => $admin["id"],
            ]);
            return handle_response([], "success");
        } else {
            if (is_wrong_data($parameters, "msgs")) {
                return handle_response([], "error");
            }
            // 直接存入redis进行处理
            $msgs = $parameters["msgs"];

            foreach ($msgs as $msg) {
                AssistService::saveRedisData4TG([
                    "type_ops" => "ban",
                    "group_tg_id" => $msg["group_tg_id"],
                    "user_tg_id" => $msg["user_tg_id"],
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
            AssistService::saveRedisData4handleMesage([
                "type" => "restrict",
                "all" => 1,
                "search_text" => $parameters["search_text"],
                "except_game" => $parameters["except_game"],
                "day" => $day,
                "admin_id" => $admin["id"],
            ]);
            return handle_response([], "success");
        } else {
            if (is_wrong_data($parameters, "msgs")) {
                return handle_response([], "error");
            }
            // 直接存入redis进行处理
            $msgs = $parameters["msgs"];

            foreach ($msgs as $msg) {
                AssistService::saveRedisData4TG([
                    "type_ops" => "restrict",
                    "group_tg_id" => $msg["group_tg_id"],
                    "user_tg_id" => $msg["user_tg_id"],
                    "until_date" => $day,
                    "admin_id" => $admin["id"],
                ]);
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
            AssistService::saveRedisData4handleMesage([
                "type" => "addCheat",
                "all" => 1,
                "search_text" => $parameters["search_text"],
                "except_game" => $parameters["except_game"],
                "admin_id" => $admin["id"],
                "reason" => $reason,
            ]);
            return handle_response([], "success");
        } else {
            if (is_wrong_data($parameters, "msgs")) {
                return handle_response([], "error");
            }
            // 直接存入redis进行处理
            $msgs = $parameters["msgs"];

            foreach ($msgs as $msg) {
                CheatService::create($msg["user_tg_id"], $admin["id"], $reason);
            }

            return handle_response([], "success");
        }
    }
}