<?php

namespace App\Service;

use App\Models\LogSend;

class LogSendService
{
    public static function create($data = [])
    {
        $logSend = new LogSend();
        $logSend->chat_id = $data["chat_id"];
        $logSend->info = $data["info"];
        $logSend->status = 1;
        $logSend->created_at = date("Y-m-d H:i:s");
        $logSend->save();

        return $logSend;
    }

    public static function update($logSend, $data = [])
    {
        $logSend->message_id = $data["message_id"];
        $logSend->reason = $data["reason"];
        $logSend->status = $data["status"];
        $logSend->save();
    }
}