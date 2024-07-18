<?php

namespace App\Http\Controllers;

use App\Service\WordService;
use App\Models\GroupAdmin;


class ApiController extends Controller
{
    public function admins()
    {
        $parameter = request()->all();
        
        if (is_wrong_data($parameter, "chat_id")) {
            return handle_response([], "no chat_id");
        }
        
        $chat_id = $parameter["chat_id"];
        
        $admins = GroupAdmin::query()->where("chat_id", $chat_id)->get();
        
        return handle_response($admins, "敏感词添加成功");
    }
    
    public function createWord()
    {
        $parameter = request()->all();

        if (is_wrong_data($parameter, "name")) {
            return handle_response([], "敏感词不能为空");
        }
        if (is_wrong_data($parameter, "level")) {
            return handle_response([], "敏感词等级错误");
        }
        if (!in_array($parameter["level"], [1, 2, 3])) {
            return handle_response([], "敏感词等级为 1，2，3级");
        }
        if (is_right_data($parameter, "type")) {
            if ($parameter["type"] == 9) {
                if (haveChinese($parameter["name"])) {
                    return handle_response([], "用户名敏感词需要为纯英文");
                }
            }
        }
        
        $name = $parameter["name"];
        if (strpos($name, "(.*)") !== false) {
            $pattern1 = $name;
            
            $pattern1 = str_replace("（", "", $pattern1);
            $pattern1 = str_replace("）", "", $pattern1);
            
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
    
        $level = $parameter["level"];
        if ($level == 2) {
            $level = 4;
        } elseif ($level == 3) {
            $level = 2;
        }
    
        $word = WordService::get([
            "name" => $parameter["name"],
            "type" => request()->get("type", 1),
            "is_one_obj" => true,
        ]);
        if ($word) {
            if ($word["level"] != $level) {
                $word->level = $level;
                $word->save();
                
                $msg = sprintf("敏感词等级已改为%s级", $parameter["level"]);
                
                // return handle_response([], $msg);
                return handle_response([], "敏感词添加成功");
            } else {
                // return handle_response([], "敏感词已存在");
                return handle_response([], "敏感词添加成功");
            }
        } 
        
        WordService::create([
            "name" => $parameter["name"],
            "level" => $level,
            "type" => request()->get("type", 1),
        ]);

        return handle_response([], "敏感词添加成功");
    }
}
