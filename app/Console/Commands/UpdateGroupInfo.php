<?php

namespace App\Console\Commands;

use App\Service\GroupService;
use Illuminate\Console\Command;

class UpdateGroupInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateGroupInfo';

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
            if ($group["type"] == "private") {
                $group->delete();
                continue;
            }

            $result = getChatMembersCount($chat_id);
            if ($result && is_right_data($result, "ok")) {
                if (!$result["ok"]) {
                    if (is_right_data($result, "description") &&
                        (
                            $result["description"] == "Forbidden: bot was kicked from the group chat" ||
                            $result["description"] == "Forbidden: bot was kicked from the supergroup chat" ||
                            $result["description"] == "Bad Request: chat not found" ||
                            $result["description"] == "Forbidden: the group chat was deleted" ||
                            $result["description"] == "Bad Request: group chat was upgraded to a supergroup chat"
                        )
                    ) {
                        // $group->delete();
                        continue;
                    }
                } else {
                    if (is_right_data($result, "result")) {
                        $group->num = $result["result"];
                        $group->save();
                    }
                }
            }

            $result = getChat($chat_id);
            if ($result && is_right_data($result, "ok") && $result["ok"]) {
                if (is_right_data($result, "result")) {
                    $info = $result["result"];

                    if (is_right_data($info, "title")) {
                        $group->title = $info["title"];
                    }

                    if (is_right_data($info, "invite_link")) {
                        $group->url = $info["invite_link"];
                    }

                    $group->save();
                }
            }
        }
    }
}
