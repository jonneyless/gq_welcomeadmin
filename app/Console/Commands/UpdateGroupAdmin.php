<?php

namespace App\Console\Commands;

use App\Service\AssistService;
use App\Service\GroupAdminService;
use App\Service\GroupService;
use Illuminate\Console\Command;

class UpdateGroupAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateGroupAdmin';

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
        $groups = GroupService::get();
        foreach ($groups as $group) {
            $chat_id = $group["chat_id"];
            $result = getChatAdministrators($chat_id);
            if ($result && is_right_data($result, "ok") && $result["ok"]) {
                if (is_right_data($result, "result")) {
                    $admins = $result["result"];

                    $ids = [];
                    foreach ($admins as $admin) {
                        $user = $admin["user"];
                        $user = AssistService::handleFrom($user);

                        array_push($ids, $user["id"]);

                        $userData = $user;
                        $userData["chat_id"] = $chat_id;
                        $userData["status"] = $admin["status"];

                        GroupAdminService::createAndUpdate($userData);
                    }

                    GroupAdminService::clear($chat_id, $ids);
                }
            }
        }
    }
}
