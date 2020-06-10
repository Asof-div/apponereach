<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Services\Task\TaskRecurrence;

class ProcessRecurrentTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recurrent-tasks:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process Recurrent Tasks';

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
    public function handle(TaskRecurrence $taskRecurrence)
    {
        $taskRecurrence->process();
    }
}
