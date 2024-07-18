<?php

namespace App\Console\Commands;

use App\Service\LogApproveService;
use Illuminate\Console\Command;

class ExpireApprove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expireApprove';

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
        $created_at_lt = date("Y-m-d H:i:s", time() - 86400 * 3);
        
        $approves = LogApproveService::get([
            "status" => 2,
            "created_at_lt" => $created_at_lt,
        ]);
        
        foreach ($approves as $approve) {
            LogApproveService::update($approve, 3, 9);
        }
    }
}
