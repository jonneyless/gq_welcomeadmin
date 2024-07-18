<?php

namespace App\Console\Commands;

use App\Service\AssistService;
use App\Service\CheatService;
use App\Service\GroupService;
use App\Service\MsgService;
use App\Service\UserService;
use App\Service\UserGroupService;
use Illuminate\Console\Command;

class HandleUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'handleUser';

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
        $key = "handle_user";

        while (true) {
            $item = $redis->lpop($key);
            if ($item) {
                print($item);
                print("\n");

                $item = json_decode($item, true);
                $type = $item["type"];
                $admin_id = $item["admin_id"];
                
                if (is_wrong_data($item, "search_type")) {
                    continue;
                }
                
                if (is_wrong_data($item, "search_text")) {
                    continue;
                }
                
                $search_text = $item["search_text"];
                if (strlen($search_text) == 0) {
                    continue;
                }
                
                print(strlen($search_text));
                print("\n");
                
                $condition = [
                    "search_type" => $item["search_type"],
                    "search_text" => $item["search_text"],
                    "except_game" => $item["except_game"],
                ];
                
                if (is_right_data($item, "group_tg_id") && $item["group_tg_id"]) {
                    $condition["group_tg_id"] = $item["group_tg_id"];
                }
                if (is_right_data($item, "user_status") && $item["user_status"]) {
                    $condition["user_status"] = $item["user_status"];
                }

                if ($type == "kick") {
                    $condition["user_status"] = 1;
                    
                    $this->handle_kick($condition, $admin_id);
                } elseif ($type == "restrict") {
                    $day = $item["day"];
                    $this->handle_restrict($condition, $day, $admin_id);
                } elseif ($type == "deleteAndRestrict") {
                    $day = $item["day"];
                    
                    $condition["all"] = $item["all"];
                    $condition["users"] = $item["users"];

                    $this->handle_deleteAndRestrict($condition, $day, $admin_id);
                } elseif ($type == "cancel_restrict") {
                    $this->handle_cancel_restrict($condition, $admin_id);
                } elseif ($type == "deleteAndKick") {
                    $condition["all"] = $item["all"];
                    $condition["users"] = $item["users"];

                    $this->handle_deleteAndKick($condition, $admin_id);
                } elseif ($type == "unban") {
                    $this->handle_unban($condition, $admin_id);
                } elseif ($type == "unbanall") {
                    // $this->handle_unbanall($condition, $admin_id);
                    $this->handle_unban($condition, $admin_id);
                } elseif ($type == "addCheat") {
                    $reason = "";
                    if (is_right_data($item, "reason")) {
                        $reason = $item["reason"];
                    }
                    
                    $this->handle_addCheat($condition, $admin_id, $reason);
                }
            } else {
                print("empty...");
                print("\n");
            }
            
            usleep(3000000);
        }
    }

    private function handle_kick($condition, $admin_id)
    {
        $users = UserService::search($condition);
        
        if ($users) {
            foreach ($users as $user) {
                AssistService::saveRedisData4TG([
                    "type_ops" => "ban",
                    "group_tg_id" => $user["group_tg_id"],
                    "user_tg_id" => $user["user_tg_id"],
                    "admin_id" => $admin_id,
                ]);
            }   
        }
    }
    
    private function handle_restrict($condition, $day, $admin_id)
    {
        $users = UserService::search($condition);
        
        if ($users) {
            foreach ($users as $user) {
                AssistService::saveRedisData4TG([
                    "type_ops" => "restrict",
                    "group_tg_id" => $user["group_tg_id"],
                    "user_tg_id" => $user["user_tg_id"],
                    "admin_id" => $admin_id,
                    "until_date" => $day,
                    // "until_date" => intval(time() + 86400 * $day),
                ]);
            }   
        }
    }

    private function handle_deleteAndRestrict($condition, $day, $admin_id)
    {
        $users = [];
        
        $all = $condition["all"];
        if ($all == 1) {
            $users = UserService::search($condition);
        } else {
            $users = $condition["users"];
        }
        
        if ($users) {
            foreach ($users as $user) {
                AssistService::saveRedisData4TG([
                    "type_ops" => "restrict",
                    "group_tg_id" => $user["group_tg_id"],
                    "user_tg_id" => $user["user_tg_id"],
                    "admin_id" => $admin_id,
                    "until_date" => $day,
                ]);
            }
            
            foreach ($users as $user) {
                $msgs = MsgService::search([
                    "flag" => 1,
                    "group_tg_id" => $user["group_tg_id"],
                    "user_tg_id" => $user["user_tg_id"],
                ]);
                
                if ($msgs) {
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
            }
        }
    }

    private function handle_cancel_restrict($condition, $admin_id)
    {
        $users = UserService::search($condition);
        
        if ($users) {
            foreach ($users as $user) {
                AssistService::saveRedisData4TG([
                    "type_ops" => "cancel_restrict",
                    "group_tg_id" => $user["group_tg_id"],
                    "user_tg_id" => $user["user_tg_id"],
                    "admin_id" => $admin_id,
                ]);
            }   
        }
    }

    private function handle_deleteAndKick($condition, $admin_id)
    {
        $users = [];
        
        $all = $condition["all"];
        if ($all == 1) {
            $users = UserService::search($condition);
        } else {
            $users = $condition["users"];
        }
        
        if ($users) {
            foreach ($users as $user) {
                AssistService::saveRedisData4TG([
                    "type_ops" => "ban",
                    "group_tg_id" => $user["group_tg_id"],
                    "user_tg_id" => $user["user_tg_id"],
                    "admin_id" => $admin_id,
                ]);
                
                $msgs = MsgService::search([
                    "flag" => 1,
                    "group_tg_id" => $user["group_tg_id"],
                    "user_tg_id" => $user["user_tg_id"],
                ]);
                
                if ($msgs) {
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
            }
            
            foreach ($users as $user) {
                $msgs = MsgService::search([
                    "flag" => 1,
                    "group_tg_id" => $user["group_tg_id"],
                    "user_tg_id" => $user["user_tg_id"],
                ]);
                
                if ($msgs) {
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
            }   
        }
    }

    private function handle_unban($condition, $admin_id)
    {
        $users = UserService::search($condition);
        
        // $users = $this->get_unique_users($users);

        foreach ($users as $user) {
            AssistService::saveRedisData4TG([
                "type_ops" => "unban",
                "group_tg_id" => $user["group_tg_id"],
                "user_tg_id" => $user["user_tg_id"],
                "admin_id" => $admin_id,
            ]);
        }
    }

    private function handle_unbanall($condition, $admin_id)
    {
        // $users = UserService::search($condition);
        // $users = $this->get_unique_users($users);
        // foreach ($users as $user) {
        //     // AssistService::saveRedisData4TG([
        //     //     "type_ops" => "unban",
        //     //     "group_tg_id" => $user["group_tg_id"],
        //     //     "user_tg_id" => $user["user_tg_id"],
        //     //     "admin_id" => $admin_id,
        //     // ]);
            
        //     $groups = GroupService::cache();
        //     foreach ($groups as $group) {
        //         // AssistService::saveRedisData4TG([
        //         //     "type_ops" => "unban",
        //         //     "group_tg_id" => $group["tg_id"],
        //         //     "user_tg_id" => $user["user_tg_id"],
        //         //     "admin_id" => $admin_id,
        //         // ]);  

        //         $user_group = UserGroupService::one([
        //             "group_tg_id" => $group["tg_id"],
        //             "user_tg_id" => $user["user_tg_id"],
        //         ]);
                
        //         if ($user_group) {
        //             AssistService::saveRedisData4TG([
        //                 "type_ops" => "unban",
        //                 "group_tg_id" => $group["tg_id"],
        //                 "user_tg_id" => $user["user_tg_id"],
        //                 "admin_id" => $admin_id,
        //             ]);   
        //         }
        //     }
        // }
    }

    private function handle_addCheat($condition, $admin_id, $reason)
    {
        $users = UserService::search($condition);
        $users = $this->get_unique_users($users);
        
        foreach ($users as $user) {
            CheatService::create($user["user_tg_id"], $admin_id, $reason);
        }
    }
    
    private function get_unique_users($users)
    {
        $arr = [];
        $arr_users = [];
        foreach ($users as $user) {
            if (!in_array($user["user_tg_id"], $arr)) {
                array_push($arr, $user["user_tg_id"]);
                array_push($arr_users, $user);
            }
        }
        
        return $arr_users;
    }
}
