<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Group;
use App\Service\OfficialUserService;

class CheckFalseGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkFalseGroup';

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
        $first = Group::query()->orderBy("id", "asc")->first();
        $last = Group::query()->orderBy("id", "desc")->first();
        
        if ($first && $last) {
            for ($i = $first["id"]; $i <= $last["id"]; $i++) {
                $group = Group::query()->where("id", $i)->first();
                if ($group) {
                    $flag = $group["flag"];
                    if ($flag == 1 or $flag == 3) {
                        $chat_id = $group["chat_id"];
                        $result = getChatAdministrators($chat_id);
                        
                        print($group["title"]);
                        print("\n");
 
                        if ($result && is_right_data($result, "ok") && $result["ok"]) {
                            if (is_right_data($result, "result")) {
                                $admins = $result["result"];
        
                                $official_num = 0;
                                foreach ($admins as $admin) {
                                    $admin_id = $admin["user"]["id"];
                                    
                                    $official_user = OfficialUserService::one([
                                        "tg_id" => $admin_id,
                                    ]);
                                    if ($official_user) {
                                        $official_num++;
                                    }
                                }
                                
                                if (count($admins) > 0 and $official_num == 0) {
                                    leaveChat($chat_id);
                                    $group->delete();
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
