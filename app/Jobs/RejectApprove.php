<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Service\LogApproveService;

class RejectApprove implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $approve;

    public function __construct($approve)
    {
        $this->approve = $approve;
    }

    public function handle()
    {
        $approve = $this->approve;
        
        LogApproveService::update($approve, 3, 8);
    }
}
