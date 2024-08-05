<?php

namespace App\Console\Commands;

use App\Models\FakeGroups;
use Illuminate\Console\Command;

class FakeGroupNotice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fakeGroupNotice';

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
        $tasks = FakeGroups::query()->where('status', 0)->get();
        foreach ($tasks as $task) {
            $task->status = 1;
            $task->save();

            \App\Jobs\FakeGroupNotice::dispatch($task->group_tg_id, '本群是假群，请马上退群，小心上当受骗。')->onQueue('fake_group_notice');
        }
    }
}
