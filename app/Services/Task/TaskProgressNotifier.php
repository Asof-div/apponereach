<?php
namespace App\Services\Task;

use App\Models\Task;
use App\Models\Report;
use Mail;
use App\Mail\Task\TaskProgressNotifier as NotifierMail;

class TaskProgressNotifier
{

	public function notify(Task $task)
	{
        if ($task->completion < 50 || $task->assigner == $task->assignee) return false;

        if ($this->hasOneReport($task)) {
            $this->notifyTaskAssigner($task);
            return true;
        }

        $previous_report = $this->getPreviousReport($task);

        if ($this->shouldSendNotification($task, $previous_report)) {
            $this->notifyTaskAssigner($task);
            return true;
        }

        return false;
	}

    private function getPreviousReport($task): Report
    {
        $task_reports = $task->reports->sortByDesc(function($report) {
            return $report->completion;
        })->values();

        return $task_reports->get(1);
    }

    private function shouldSendNotification($task, $previous_report): bool
    {
        if ($task->completion < 100 && $previous_report->score >= 50) {
            return false;
        }

        return true;
    }

    private function hasOneReport($task): bool
    {
        return $task->reports->count() == 1;
    }

    private function notifyTaskAssigner($task)
    {
        Mail::to($task->assign_by)->queue(new NotifierMail($task));
    }

}