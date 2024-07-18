<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chat_id;
    protected $message;

    public function __construct($chat_id, $message)
    {
        $this->chat_id = $chat_id;
        $this->message = $message;
    }

    public function handle()
    {
        $result = sendMessage($this->chat_id, $this->message);
        Log::info("SendMessage result".json_encode($result));
    }
}
