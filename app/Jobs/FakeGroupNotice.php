<?php

namespace App\Jobs;

use App\Models\FakeGroups;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FakeGroupNotice implements ShouldQueue
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
        $data = sendMessage($this->chat_id, $this->message);
        if (isset($data['ok']) && $data['ok']) {
            FakeGroupNotice::dispatch($this->chat_id, $this->message)->onQueue('fake_group_notice')->delay(10);
        } else {
            FakeGroups::query()->where('group_tg_id', $this->chat_id)->delete();
        }
    }
}
