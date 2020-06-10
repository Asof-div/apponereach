<?php
namespace App\Services\Task;

use App\Models\Task;
use Carbon\Carbon;

class CompletionSpeedCalculator
{
    private $task;

	public function __construct(Task $task)
	{
	   $this->task = $task;	
	}

	public function calculate()
	{
        if ($this->task->completion < 100) {
            return 0;
        }

        return $this->calculateSpeed();
	}

    private function calculateSpeed()
    {
        $func = (new TaskService)->getTaskDateDiffFunction($this->task);
        $now = Carbon::now();
        
        $total_task_duration = $this->task->start_date->{$func}($this->task->end_date);
        $task_completion_duration = $this->task->start_date->{$func}($now);

        if ($total_task_duration > $task_completion_duration) {
            return (($total_task_duration - $task_completion_duration) / $total_task_duration) * 100;
        } else {
            return 0;
        }
    }

}