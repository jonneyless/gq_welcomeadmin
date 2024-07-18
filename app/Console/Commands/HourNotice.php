<?php

namespace App\Console\Commands;

use App\Jobs\DeleteMessage;
use App\Service\ConfigService;
use App\Service\GroupService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class HourNotice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hourNotice';

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
        $hour_notice_status = ConfigService::val("hour_notice_status");
        $hour_notice_info = ConfigService::val("hour_notice_info");

        if ($hour_notice_status == 1) {
            $groups = GroupService::get();
            foreach ($groups as $group) {
                if ($group["flag"] == 2 || $group["flag"] == 3) {
                    if ($group["hour_notice_status"] == 1) {
                        $chat_id = $group["chat_id"];

                        if (Cache::has("hour_notice_" . $chat_id)) {
                            $last_message_id = Cache::get("hour_notice_" . $chat_id);
                            DeleteMessage::dispatch($chat_id, $last_message_id);
                            Cache::forget("hour_notice_" . $chat_id);
                        }

                        $hour = date("H");
                        if (strlen($hour) == 2 && $hour[0] == "0") {
                            $hour = $hour[1];
                        }

                        $msg = sprintf("当前北京时间【%s点】整。%s", $hour, $hour_notice_info);
                        $result = sendMessage($chat_id, $msg);
                        if ($result && is_right_data($result, "result") && is_right_data($result["result"], "message_id")) {
                            $message_id = $result["result"]["message_id"];
                            Cache::forever("hour_notice_" . $chat_id, $message_id);
                        }
                    }
                }
            }
        }
    }
}
