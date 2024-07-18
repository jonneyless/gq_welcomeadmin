<?php

namespace App\Console\Commands;

use App\Jobs\GroupAdminFullName;
use App\Service\GroupService;
use Illuminate\Console\Command;

class GroupAdminFullNameFilter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GroupAdminFullNameFilter';

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

        $this->getGroupAdminUserHandle();
        return true;
    }

    /**
     * group_id 维度发送job处理数据过滤
     * @return void
     */
    private function getGroupAdminUserHandle()
    {
        $groups = GroupService::get();
        $groups = !empty($groups) && count($groups) > 0 ? json_decode(json_encode($groups), true) : [];
        if(!empty($groups))
        {
            foreach ($groups as $group)
            {
                GroupAdminFullName::dispatch($group['id']);
            }
        }
    }
}