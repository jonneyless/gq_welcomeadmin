<?php

namespace App\Console\Commands;

use App\Service\ConfigService;
use App\Service\GroupService;
use Illuminate\Console\Command;

class SwitchGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'switchGroup';

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
        $switch_group_status = ConfigService::val("switch_group_status");
        if ($switch_group_status == 1) {
            $groups = GroupService::get();
            $date = date("Y-m-d");
            foreach ($groups as $group) {
                if ($group["switch_group_status"] == 1) {
                    $chat_id = $group["chat_id"];

                    $started_at = $group["started_at"];
                    $ended_at = $group["ended_at"];

                    if ($started_at && $ended_at) {
                        $started_at = $date . " " . $started_at;
                        $ended_at = $date . " " . $ended_at;

                        $started_at = strtotime($started_at);
                        $ended_at = strtotime($ended_at);

                        $next_started_at = $started_at + 86400;

                        $currentTime = time();

                        if ($currentTime >= $started_at && $currentTime < $ended_at) {
                            // 营业时间
                            if ($group["opened"] == 2) {
                                setChatPermissions($chat_id, true);

                                GroupService::open($group);
                            }
                        }

                        if (($currentTime >= $ended_at && $currentTime < $next_started_at) || ($currentTime < $started_at)) {
                            // 非营业时间
                            if ($group["opened"] == 1) {
                                sendMessage($chat_id, "今日停车休息，请各位客官明日再来");
                                setChatPermissions($chat_id, false);

                                GroupService::close($group);
                            }
                        }
                    }
                }
            }
        }
    }
}
