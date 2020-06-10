<?php

namespace App\Repos;

use App\Models\Todo as Task;
use App\Models\User;
use App\Models\Report;

class TaskRepository
{
    public function create($details): Task
    {
        return Task::create([
            'title' => $details['title'],
            'tenant_id' => $datails['tenant_id'],
            'assigner_id' => $details['assigner'],
            'assignee_id' => $details['assignee'],
            'priority' => $details['priority'],
            'description' => substr($details['description'], 0, 65000),
            'type' => $details['type'],
            'start_date' => $details['start_date'],
            'start_time' => $details['start_time'],
            'end_date' => $details['end_date'],
            'end_time' => $details['end_time'],
            'duration' => $details['duration'],
            'duration_unit' => $details['duration_unit'],

        ]);
    }


    public function getTaskById($task_id)
    {
        return Task::where('id', $task_id)->first();
    }

    public function getMailRecipientsCopy(Task $task)
    {
        $cc = [];

        if ($task->user->id != $task->user->department->supervisor && $task->assign_by->id != $task->user->department->supervisor) {
            $supervisor_email = $task->user->department->superviser->email;
            if (!empty($supervisor_email)) {
                $cc[] = $supervisor_email;
            }
        }

        if ($task->assignee != $task->assigner){
            $cc[] = $task->assign_by->email;
        }

        return $cc;
    }

    public function getUsersOngoingTasks(User $user)
    {
        $today = (new \DateTime)->format('Y-m-d');
        return Task::where('assignee', $user->id)->notRejected()->where('paused', 0)->where('isConcluded', 0)->whereDate('start_date', '<=', $today)->get();
    }

    public function updateTaskCompletionStatus($task, $completion, $completion_speed)
    {
        if ($completion == 100) {
            $task->update([
                'status' => 2,
                'isConcluded' => 1,
                'concluded_at' => (new \DateTime)->format('Y-m-d H:i:s'),
                'completion_speed' => $completion_speed,
            ]);
        } else {
            $task->update([
                'status' => 1,
                'isConcluded' => 0,
                'concluded_at' => null,
                'completion_speed' => 0
            ]);
        }
    }

    public function userHasReportOnDate(User $user, Task $task, \DateTime $date = null): bool
    {
        $report_date = is_null($date) ? (new \DateTime)->format('Y-m-d') : $date->format('Y-m-d');

        return $task->reports()->whereDate('created_at', $report_date)->count() > 0;
    }

    public function getUserReportOnDate(User $user, Task $task, \DateTime $date = null): Report
    {
        $report_date = is_null($date) ? (new \DateTime)->format('Y-m-d') : $date->format('Y-m-d');

        return $task->reports()->whereDate('created_at', $report_date)->first();
    }

}