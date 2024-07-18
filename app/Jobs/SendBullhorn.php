<?php

namespace App\Jobs;

use App\Service\LogSendService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBullhorn implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $chat_id;
    public $info;
    public $group;

    public function __construct($chat_id, $info, $group)
    {
        $this->chat_id = $chat_id;
        $this->info = $info;
        $this->group = $group;
    }

    public function handle()
    {
        $chat_id = $this->chat_id;
        $info = $this->info;
        $group = $this->group;

        $logSend = LogSendService::create([
            "chat_id" => $chat_id,
            "info" => $info,
            "title" => $group["title"],
        ]);

        $result = sendMessage($this->chat_id, $this->info);
        if ($result && is_right_data($result, "result")) {
            $message_id = $result["result"]["message_id"];
            LogSendService::update($logSend, [
                "message_id" => $message_id,
                "reason" => "",
                "status" => 2,
            ]);
        } else {
            LogSendService::update($logSend, [
                "message_id" => -1,
                "reason" => json_encode($result),
                "status" => 3,
            ]);
        }
    }
}
