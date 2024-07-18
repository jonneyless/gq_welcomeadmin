<?php

namespace App\Console\Commands;

use App\Models\Group;
use Illuminate\Console\Command;

class SendWelcomeMsg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendWelcomeMsg';

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
        $url = "http://127.0.0.1:8611/";
        
        $groups = Group::query()
            ->where("flag", 2)
            ->where("status_in", 1)
            ->where("welcome_status", 1)
            ->select("id", "title", "chat_id", "welcome_info")
            ->get();
        
        foreach ($groups as $group) {
            $title = $group["title"];
            $group_tg_id = $group["chat_id"];
            $welcome_info = $group["welcome_info"];
            
            $info = sprintf("欢迎来到 %s 进行交易，%s", $title, $welcome_info);
            
            if (strlen($welcome_info) > 0) {
                $data = [
                    "type_ops" => "send_delete_last",
                    "group_tg_id" => $group_tg_id,
                    "user_tg_id" => 1,
                    "msg" => $info,
                ];
                
                curlGet($url, $data);
                sleep(1);
            }
        }
    }
}
