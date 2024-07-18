<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DeleteMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chat_id;
    protected $message_id;
    protected $message;

    public function __construct($chat_id, $message_id, $message = false)
    {
        $this->chat_id = $chat_id;
        $this->message_id = $message_id;
        $this->message = $message;
    }

    public function handle()
    {
        deleteMessage($this->chat_id, $this->message_id);
    }
}
