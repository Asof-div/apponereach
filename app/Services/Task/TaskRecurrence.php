<?php
namespace App\Services\Task;

use App\Models\User;
use App\Models\Notification;
use App\Services\Ticket\SystemTaskTicketGenerator;
use Facades\App\Repos\TaskRepository;

use App\Models\Todo as Task;

use When\When;

class TaskRecurrence
{

	public function process()
    {
        $date = (new \DateTime);
        $current_time = $date->format('Y-m-d H:i:s');

        $recurrent_tasks = Task::where('repeat_task', 1)->where('start_date', '<=', $current_time)->where(function($query) use ($current_time) {
            return $query->whereNull('repeat_end_date')
                         ->orWhere('repeat_end_date', '>', $current_time);
        })->get();

        foreach ($recurrent_tasks as $task) {
            $this->processTask($task);
        }
    }

    public function processTask($task)
    {
        $occurrences = $this->getTaskOcurrences($task);
        $filtered_occurrences = $this->filterOccurrences($occurrences, $task->last_repeated_at);

        foreach ($filtered_occurrences as $occurrence) {
            $task_hours = $task->start_date->diffInHours($task->end_date);
            $start_date = $occurrence->copy();
            $end_date = $start_date->copy()->modify("+ {$task_hours} hours");

            (new TaskService)->createNewTaskFromTask($task, $start_date, $end_date);

            $task->update([
                'last_repeated_at' => $occurrence->copy()->format('Y-m-d H:i:s')
            ]);
        }
    }

    private function filterOccurrences($occurrences, $last_repeated_at)
    {
        $current_time = (new \DateTime)->format('Y-m-d H:i:s');
        return collect($occurrences)->filter(function ($occurrence) use ($current_time, $last_repeated_at) {
            // we only need tasks that are supposed to start between the last repeated at and the current time
            $occurrence_date = $occurrence->format('Y-m-d H:i:s');
            return ($occurrence_date > $last_repeated_at) && ($occurrence_date <= $current_time);
        });
    }

    private function getTaskOcurrences($task)
    {
        $r = new When();
        $r->RFC5545_COMPLIANT = When::IGNORE;
        $dates = $r->startDate($task->start_date);
        $r->freq($task->repeat_interval);
        $r->interval($this->getInterval($task->repeat_interval, $task->repeat_interval_opts));

        if (strtolower($task->repeat_interval) == 'weekly') {
            $r->byday($this->getWeekDays($task->repeat_interval_opts));
        }

        if (strtolower($task->repeat_interval) == 'monthly') {
            $monthly_repeat_type = $task->repeat_interval_opts['monthly_repeat_type'];

            if ($monthly_repeat_type == 'day_num') {
                $month_day = (int) $task->repeat_interval_opts['monthly_day_num'];
                $r->bymonthday($month_day);
            } else { // day_pos
                $day_pos = (int) $task->repeat_interval_opts['monthly_day_pos'];
                $r->bysetpos($day_pos);
                $r->byday($task->repeat_interval_opts['monthly_day_name']);
            }
        }

        if ($task->repeat_end_date) {
            $repeat_end_date = $task->repeat_end_date->setTime(23,59,59);
            $r->until($repeat_end_date);
        }
        
        $r->generateOccurrences();

        return $r->occurrences;
    }

    private function getInterval(string $repeat_interval, array $repeat_interval_opts):int
    {
        $repeat_interval_key = strtolower($repeat_interval) . '_repeat_freq';
        $interval = array_key_exists($repeat_interval_key, $repeat_interval_opts) ? (int) $repeat_interval_opts[$repeat_interval_key] : 1;

        return $interval;
    }

    private function getWeekDays(array $repeat_interval_opts):string
    {
        $days = array_key_exists('weekly_repeat_days', $repeat_interval_opts) && is_array($repeat_interval_opts['weekly_repeat_days']) ? implode(',', $repeat_interval_opts['weekly_repeat_days']) : '';

        return $days;
    }

}