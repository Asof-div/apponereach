<?php
namespace App\Services\Task;

use App\Models\User;
use App\Models\Notification;
use App\Services\Ticket\SystemTaskTicketGenerator;
use App\Services\Work\WorkService;
use App\Services\PWP\DailyPWPCalculator;
use App\Repos\TaskRepository;
use App\Models\Report;

use Mail;
use App\Mail\TaskAssigned;
use Carbon\Carbon;

class TaskService
{

	public function createTaskFromEmail($task_email, $title, $assigner, $assignees, $description)
    {
        foreach ($assignees as $assignee) {
            $user = User::whereHas('groups', function ($group) {
                $group->where('name', 'admin')->orWhere('name', 'agent');
            })->where('email', $assignee)->first();

            if ($user == null) continue;

            $ticket = (new SystemTaskTicketGenerator)->getUsersSystemTaskTicket($user);
            
            $details = array(
                'title' => $title,
                'email_id' => $task_email->id,
                'assigner' => $assigner->id,
                'assignee' => $user->id,
                'priority' => $task_email->priority,
                'department' => $user->department_id,
                'description' => $description,
                'category' => 'Ticket',
                'module_id' => $ticket->id,
                'module_type' => 'App\Models\Ticket',
                'start_date' => (new \DateTime)->format('Y-m-d H:i:s'),
                'end_date' => (new \DateTime)->modify('+' . $task_email->getDeadline())->format('Y-m-d H:i:s')
            );

            $task = (new TaskRepository)->create($details);

            if ($assigner->id != $user->id) {
                $notify = Notification::create(['message' => $assigner->name(). " has assigned a ticket task to you", 'subject' => "Task - ".$task->name, 'link' => 'helpdesk/ticket/tasks/'.$ticket->ticket_no]);
                    
                $user->sendNotification($notify);
            }

            $cc = (new TaskRepository)->getMailRecipientsCopy($task);

            Mail::to($task->user)->cc($cc)->queue(new TaskAssigned($task));
        }
    }

    public function createNewTaskFromTask($task, $start_date, $end_date)
    {
        $details = array(
            'parent_task_id' => $task->id,
            'title' => $task->name,
            'assigner' => $task->assigner,
            'assignee' => $task->assignee,
            'priority' => $task->priority,
            'department' => $task->department_id,
            'description' => $task->description,
            'category' => 'Ticket',
            'module_id' => $task->module_id,
            'module_type' => $task->module_type,
            'start_date' => $start_date->format('Y-m-d H:i:s'),
            'end_date' => $end_date->format('Y-m-d H:i:s')
        );

        $new_task = (new TaskRepository)->create($details);

        if ($new_task->assigner != $new_task->assignee) {
            $notify = Notification::create(['message' => $new_task->assign_by->name(). " has assigned a ticket task to you", 'subject' => "Task - ".$new_task->name, 'link' => 'helpdesk/ticket/tasks/'.$task->module->ticket_no]);
                
            $new_task->user->sendNotification($notify);
        }

        $cc = (new TaskRepository)->getMailRecipientsCopy($new_task);

        Mail::to($task->user)->cc($cc)->queue(new TaskAssigned($new_task));
    }

    public function getTaskDurationUnit($task)
    {
        if ($task->start_date->diffInDays($task->end_date) > 0) {
            return 'days';
        } else {
            return 'hours';
        }
    }

    public function userHasOngoingTasks($user)
    {
        return (new TaskRepository)->getUsersOngoingTasks($user)->count() > 0;
    }

    public function getDaysElapsedSinceTaskStartDate($start_date, $current_date = null)
    {
        $days = 0;

        $holiday_days = (new WorkService)->getHolidayDays();
        $start_date = $start_date->copy();

        $current_date = ($current_date == null) ? Carbon::today() : $current_date;

        for ($date = $start_date; $date->lte($current_date); $date->addDay()) {
            if (!in_array($date->format('m-d'), $holiday_days) && $date->isWeekDay()) {
                $days++;
            }
        }

        if ($days < 2) {
            return 1;
        }
        return $days;
    }

    public function getExpectedTaskCompletionPercentage($task, $date = null)
    {
        $task_duration = $task->durationHour();
        $daily_completion_rate = $task->averagePercent();

        $current_task_duration = $this->getDaysElapsedSinceTaskStartDate($task->start_date, $date);

        if ($task_duration == $current_task_duration) {
            // meaning task is suppose to end today, its expected to be 100 percent complete
            return 100;
        } else if ($task_duration > $current_task_duration) {
            return $daily_completion_rate * $current_task_duration;
        } else {
            // this is an overdue task, we dont expect anything less than 100
            return 100;
        }
    }

    public function getScore($todays_completion, $expected_completion)
    {
        $completion_rate = ($todays_completion / $expected_completion) * 100;

        if ($completion_rate > 100) return 100;

        return $completion_rate;
    }

    public function updateTaskScore($task, $days, $completion)
    {
        $total_report_scores = $task->reports->sum(function($report) {
            return $report->score;
        });

        $score = round($total_report_scores / $days, 2);

        if ($score < 0) {
            $score = 0;
        }

        if ($score > 100) {
            $score = 100;
        }

        $task->update([
            'score' => $score,
            'completion' => $completion
        ]);
    }

    public function recomputeTaskScore($task, $report, $completion)
    {
        // if ($task->completion <= $completion) return;

        if ($completion > 100) return;

        $pwp = $report->pwp;
        $expected_completion_percentage = $this->getExpectedTaskCompletionPercentage($task, $report->created_at);
        $score = $this->getScore($completion, $expected_completion_percentage);
        $days = $this->getDaysElapsedSinceTaskStartDate($task->start_date, $report->created_at);

        $report->update([
            'score' => $score,
            'completion' => $completion,
        ]);

        $this->updateTaskScore($task, $days, $completion);
        (new DailyPWPCalculator)->updateTaskOnPWP($pwp, $task);

        $completion_speed = (new CompletionSpeedCalculator($task))->calculate();
        (new TaskRepository)->updateTaskCompletionStatus($task, $completion, $completion_speed);

        return true;
    }

    public function getSecondToTheLastReport($task): Report
    {
        $task_reports = $task->reports->sortByDesc(function($report) {
            return $report->completion;
        })->values();

        return $task_reports->get(1);
    }

    public function getTaskDateDiffFunction($task)
    {
        if ($this->getTaskDurationUnit($task) == 'days') {
            return 'diffInDays';
        } else {
            return 'diffInHours';
        }
    }

}