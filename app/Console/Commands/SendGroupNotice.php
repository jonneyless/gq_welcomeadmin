<?php

namespace App\Console\Commands;

use App\Service\GroupNoticeService;
use App\Service\GroupService;
use Illuminate\Console\Command;

class SendGroupNotice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendGroupNotice';

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
        $notices = GroupNoticeService::get();

        foreach ($notices as $notice) {
            $group = GroupService::get([
                "chat_id" => $notice["chat_id"],
                "is_one_obj" => true,
            ]);
            if (!$group) {
                $notice->delete();
                continue;
            }

            if ($notice["last_noticed"]) {
                $last_noticed = strtotime($notice["last_noticed"]);
                if ((time() - $last_noticed) >= $notice["space"] * 60) {
                    $result = sendMessage($notice["chat_id"], $notice["msg"]);

                    if ($notice["flag"] == 1 && $notice["pinned"] == 2) {
                        if ($result && is_right_data($result, "result") && is_right_data($result["result"], "message_id")) {
                            $message_id = $result["result"]["message_id"];
                            pinChatMessage($notice["chat_id"], $message_id);
                            $notice->pinned = 1;
                        }
                    }

                    $notice->last_noticed = date("Y-m-d H:i:s");
                    $notice->save();
                }
            } else {
                $result = sendMessage($notice["chat_id"], $notice["msg"]);

                if ($notice["flag"] == 1 && $notice["pinned"] == 2) {
                    if ($result && is_right_data($result, "result") && is_right_data($result["result"], "message_id")) {
                        $message_id = $result["result"]["message_id"];

                        pinChatMessage($notice["chat_id"], $message_id);
                        $notice->pinned = 1;
                    }
                }

                $notice->last_noticed = date("Y-m-d H:i:s");
                $notice->save();
            }
        }
    }
}
