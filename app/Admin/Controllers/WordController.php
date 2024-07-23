<?php


namespace App\Admin\Controllers;

use App\DataModels\Word as DataWord;
use App\Http\Controllers\Controller;
use App\Service\WordService;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Http\Request;

/**
 * type =
 *  1发言敏感词
 *      level
 *          1一级
 *          4二级
 *          2三级
 *  2监控词
 *  3监控屏蔽词
 *  4昵称限制词
 *      level
 *          1一级
 *          2二级
 *  9用户名限制词
 *      level
 *          1一级
 *          2二级
 */
class WordController extends Controller
{
    public function index(Content $content)
    {
        Admin::disablePjax();
        return $content
            ->title('发言敏感词')
            ->description(' ')
            ->row(function (Row $row) {
                $row->column(6, function (Column $column) {
                    $column->append(DataWord::index());
                });
            });
    }

    public function in(Content $content)
    {
        Admin::disablePjax();
        return $content
            ->title('昵称限制词')
            ->description(' ')
            ->row(function (Row $row) {
                $row->column(6, function (Column $column) {
                    $column->append(DataWord::in());
                });
            });
    }

    public function username(Content $content)
    {
        Admin::disablePjax();
        return $content
            ->title('用户名限制词')
            ->description(' ')
            ->row(function (Row $row) {
                $row->column(6, function (Column $column) {
                    $column->append(DataWord::username());
                });
            });
    }

    public function intro(Content $content)
    {
        Admin::disablePjax();

        return $content
            ->title('简介限制词')
            ->description(' ')
            ->row(function (Row $row) {
                $row->column(6, function (Column $column) {
                    $column->append(DataWord::intro());
                });
            });
    }

    public function data(Request $request)
    {
        $result = WordService::get([
            "type" => request()->get("type", 1),
            "is_arr" => true,
        ]);

        return array(
            "draw" => $request->get("draw"),
            'recordsTotal' => count($result),
            "recordsFiltered" => count($result),
            'data' => $result,
        );
    }

    public function add()
    {
        $parameter = request()->all();

        if (is_wrong_data($parameter, "name")) {
            return handle_response([], "error");
        }
        // if (is_wrong_data($parameter, "type")) {
        //     return handle_response([], "error1");
        // }
        if (is_wrong_data($parameter, "level")) {
            return handle_response([], "error1");
        }
        if (!in_array($parameter["level"], [1, 2, 3, 4, 9])) {
            return handle_response([], "error1");
        }
        if (is_right_data($parameter, "type")) {
            if ($parameter["type"] == 9) {
                if (haveChinese($parameter["name"])) {
                    return handle_response([], "用户名需要为纯英文");
                }
            }
        }
        
        
        $name = $parameter["name"];
        if (strpos($name, "(.*)") !== false) {
            $pattern1 = $name;
            
            $pattern1 = str_replace("?", "", $pattern1);
            $pattern1 = str_replace("/", "", $pattern1);
            $pattern1 = str_replace("|", "", $pattern1);
            $pattern1 = str_replace("!", "", $pattern1);
            $pattern1 = str_replace("^", "", $pattern1);
            $pattern1 = str_replace("$", "", $pattern1);
            $pattern1 = str_replace("+", "", $pattern1);
            $pattern1 = str_replace("[", "", $pattern1);
            $pattern1 = str_replace("]", "", $pattern1);
            $pattern1 = str_replace("{", "", $pattern1);
            $pattern1 = str_replace("}", "", $pattern1);
            
            $pattern1_len = strlen($pattern1);
            
            if ($pattern1_len < 6 or $pattern1[$pattern1_len - 1] == ")" or $pattern1[0] == "(") {
                return handle_response([], "正则敏感词格式不对");
            }
            
            $count_kuohao_zuo = substr_count($pattern1, "(");
            $count_kuohao_you = substr_count($pattern1, ")");
            $count_dian = substr_count($pattern1, ".");
            $count_xing = substr_count($pattern1, "*");
            
            if ($count_kuohao_zuo != $count_kuohao_you) {
                return handle_response([], "正则敏感词格式不对");
            }
            
            if ($count_dian != $count_xing) {
                return handle_response([], "正则敏感词格式不对");
            }
            
            if ($pattern1[$pattern1_len - 1] == "." or $pattern1[0] == ".") {
                return handle_response([], "正则敏感词格式不对");
            }
            
            if ($pattern1[$pattern1_len - 1] == "*" or $pattern1[0] == "*") {
                return handle_response([], "正则敏感词格式不对");
            }
            
            if ($pattern1[$pattern1_len - 1] == "+" or $pattern1[0] == "+") {
                return handle_response([], "正则敏感词格式不对");
            }
            
            if ($pattern1[$pattern1_len - 1] == "?" or $pattern1[0] == "?") {
                return handle_response([], "正则敏感词格式不对");
            }
            
            $parameter["name"] = $pattern1;
        }
        
        $word = WordService::get([
            "name" => $parameter["name"],
            "type" => request()->get("type", 1),
            "is_one_obj" => true,
        ]);
        if ($word) {
            return handle_response([], "已存在");
        }

        WordService::create([
            "name" => $parameter["name"],
            "level" => $parameter["level"],
            "type" => request()->get("type", 1),
        ]);

        return handle_response([], "成功");
    }

    public function delete()
    {
        $parameter = request()->all();

        if (is_wrong_data($parameter, "id")) {
            return handle_response([], "error1");
        }

        $word = WordService::get([
            "id" => $parameter["id"],
            "type" => request()->get("type", 1),
            "is_one_obj" => true,
        ]);
        if (!$word) {
            return handle_response([], "error");
        }

        WordService::delete($word);

        return handle_response([], "success");
    }

    public function changeLevel()
    {
        $parameter = request()->all();

        if (is_wrong_data($parameter, "id")) {
            return handle_response([], "error1");
        }
        if (is_wrong_data($parameter, "level")) {
            return handle_response([], "error1");
        }
        if (!in_array($parameter["level"], [1, 2, 3, 4, 9])) {
            return handle_response([], "error1");
        }

        $word = WordService::get([
            "id" => $parameter["id"],
            "type" => request()->get("type", 1),

            "is_one_obj" => true,
        ]);
        if (!$word) {
            return handle_response([], "error");
        }

        WordService::changeLevel($word, $parameter["level"]);

        return handle_response([], "success");
    }
}