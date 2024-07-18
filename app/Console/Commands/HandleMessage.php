<?php

namespace App\Console\Commands;

use App\Service\AssistService;
use App\Service\CheatService;
use App\Service\MsgService;
use Illuminate\Console\Command;

class HandleMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'handleMessage';

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
        $redis = app('redis.connection');
        $redis->select(1);
        $key = "handle_message";

        while (true) {
            $item = $redis->lpop($key);
            if ($item) {
                print($item);
                // print("\n");
                $item = json_decode($item, true);
                
                $type = $item["type"];
                $admin_id = $item["admin_id"];

                $condition = [
                    "search_text" => $item["search_text"],
                    "except_game" => $item["except_game"],
                ];

                if ($type == "delete") {
                    $this->handle_delete($condition, $admin_id);
                } elseif ($type == "ban") {
                    $this->handle_ban($condition, $admin_id);
                } elseif ($type == "restrict") {
                    $day = $item["day"];
                    $this->handle_restrict($condition, $day, $admin_id);
                } elseif ($type == "addCheat") {
                    $reason = "";
                    if (is_right_data($item, "reason")) {
                        $reason = $item["reason"];
                    }
                    
                    $this->handle_addCheat($condition, $admin_id, $reason);
                }
            }
        }
    }

    private function handle_delete($condition, $admin_id)
    {
        $msgs = MsgService::search($condition);

        foreach ($msgs as $msg) {
            AssistService::saveRedisData4TG([
                "type_ops" => "delete",
                "group_tg_id" => $msg["group_tg_id"],
                "user_tg_id" => $msg["user_tg_id"],
                "message_tg_id" => $msg["message_tg_id"],
                "admin_id" => $admin_id,
            ]);
        }
    }

    private function handle_ban($condition, $admin_id)
    {
        $msgs = MsgService::search($condition);
        foreach ($msgs as $msg) {
            AssistService::saveRedisData4TG([
                "type_ops" => "ban",
                "group_tg_id" => $msg["group_tg_id"],
                "user_tg_id" => $msg["user_tg_id"],
                "admin_id" => $admin_id,
            ]);
        }
    }

    private function handle_restrict($condition, $day, $admin_id)
    {
        $msgs = MsgService::search($condition);
        foreach ($msgs as $msg) {
            AssistService::saveRedisData4TG([
                "type_ops" => "restrict",
                "group_tg_id" => $msg["group_tg_id"],
                "user_tg_id" => $msg["user_tg_id"],
                "admin_id" => $admin_id,
                "until_date" => $day,
                // "until_date" => intval(time() + 86400 * $day),
            ]);
        }
    }
    
    private function handle_addCheat($condition, $admin_id, $reason)
    {
        $msgs = MsgService::search($condition);
        
        foreach ($msgs as $msg) {
            CheatService::create($msg["user_tg_id"], $admin_id, $reason);
        }
    }
}
