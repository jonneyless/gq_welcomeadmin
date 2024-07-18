<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Models\Group;
use App\Models\GroupAdmin;
use App\Service\OfficialUserService;
use App\Service\GroupService;


class HandleInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'handleInit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        print("init...\n");
        $key = "initGroup";
        $redis = app('redis.connection');
        while (true) {
            print(sprintf("len %s \n", $redis->llen($key)));
            $item = $redis->lpop($key);
            
            if ($item) {
                $item = json_decode($item, true);
                
                $group_tg_id = strval($item["group_tg_id"]);
                $from_tg_id = $item["from_tg_id"];
                $num = $item["num"];
                
                $official = OfficialUserService::one([
                    "tg_id" => $from_tg_id
                ]);
                if (!$official) {
                    continue;
                }
                
                $groupOld = GroupService::one_by_num($num);
                if (!$groupOld) {
                    sendMessage($group_tg_id, sprintf("待恢复的群 %s：不存在或未注销", $num));
                    continue;
                }
                $rules = $groupOld["rules"];
                
                $adminBoss = GroupAdmin::query()->where("chat_id", $groupOld["chat_id"])
                    ->where("custom_title", "like", "%本公群老板%")
                    ->first();
                    
                $adminYewus = GroupAdmin::query()->where("chat_id", $groupOld["chat_id"])
                    ->where("custom_title", "like", "%本公群业务员%")
                    ->get();
                    
                $text = sprintf("恢复公群 %s\n", $num);
                $text .= $this->get_admin_text($adminBoss, $adminYewus);
                
                if ($rules) {
                    $result = sendMessage($group_tg_id, $rules);
                    
                    if ($result && is_right_data($result, "result")) {
                        $text .= "群规发送成功\n";
                        $resultResult = $result["result"];
                        if (is_right_data($resultResult, "message_id")) {
                            $result = pinChatMessage($group_tg_id, $resultResult["message_id"]);
                            if ($result && is_right_data($result, "ok") && $result["ok"]) {
                                $text .= "群规置顶成功\n";
                            }
                        }
                    } else {
                        $text .= "群规发送失败\n";
                    }
                } else {
                    $text .= sprintf("未找到群规\n", $num);
                }
                
                $result = updateChatTitle($group_tg_id, $groupOld["title"]);
                 if ($result && is_right_data($result, "ok") && $result["ok"]) {
                     $text .= "标题修改成功\n";
                 } else {
                     $text .= "标题修改失败\n";
                 }
                
                $result = transferJz($groupOld["chat_id"], $group_tg_id);
                $text .= $this->get_jz_text($result);
                
                sendMessage($group_tg_id, $text);

            } else {
                // print(date("Y-m-d H:i:s"));
                // print("\n");
                
                sleep(1);
            }
        }
    }
    
    public function get_jz_text($result)
    {
        $text = "";
        
        if ($result && is_right_data($result, "message")) {
            $message = $result["message"];
            if ($message == "success") {
                $text .= "记账机器人账单和操作人同步成功";
            } elseif ($message == "no key" or $message == "key error" or $message == "no from_group_tg_id" or $message == "no to_group_tg_id") {
                $text .= "记账机器人数据同步失败";
            } elseif ($message == "no groupFrom") {
                $text .= "注销群不存在记账机器人";
            } elseif ($message == "no groupTo") {
                $text .= "当前群不存在记账机器人";
            } elseif ($message == "haveData") {
                $text .= "当前群存在记账数据，无法同步";
            }
        } else {
            $text .= "记账机器人数据同步失败\n";
        }
        
        return $text;
    }
    
    public function get_admin_text($adminBoss, $adminYewus)
    {
        $text_admin = "\n群老板：";
        if(!$adminBoss) {
            $text_admin .= "不存在";
        } else {
            $text_admin .= sprintf("%s %s %s", $adminBoss["user_id"], $adminBoss["username"], trim($adminBoss["firstname"] . $adminBoss["lastname"]));
        }
        $text_admin .= "\n";
        if (!$adminYewus) {
            $text_admin .= "业务员不存在\n";
        } else {
            $text_admin .= "业务员：\n";
            foreach ($adminYewus as $adminYewu) {
                $text_admin .= sprintf("%s %s %s\n", $adminYewu["user_id"], $adminYewu["username"], $adminYewu["firstname"] . $adminYewu["lastname"]);
            }
        }
        $text_admin .= "\n";
        
        return $text_admin;
    }
}
