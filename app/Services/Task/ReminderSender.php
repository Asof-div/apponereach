<?php
namespace App\Services\Task;

use PhpImap\Mailbox as ImapMailbox;

use App\Models\User;
use App\Models\Task;
use App\Models\TaskEmail;

use Mail;
use App\Mail\TaskReminder;
use Carbon\Carbon;

class ReminderSender
{
	public function __construct()
	{
		
	}

	public function send()
	{
        // $emails = TaskEmail::all();

        // foreach ($emails as $email) {
        //     if ($email->send_reminder_email == false) continue;
        //     $this->sendTaskReminder($email);
        // }

        // return;

        $this->sendTaskReminder();
	}

    private function sendTaskReminder()
    {
        $an_hour_ago = Carbon::now()->subHour(1)->format('Y-m-d H:i:s');

        $users = User::whereHas('groups', function ($group) {
            $group->where('name', 'admin')->orWhere('name', 'agent');  
        })->get();

        foreach ($users as $user) {
            $open_tasks = [];
            $overdue_tasks = [];

            $unconcluded_tasks = $user->tasks()->notRejected()->where('paused', 0)->where('start_date', '<=', $an_hour_ago)->where('status', '<', 2)->where('isConcluded', 0)->get();

            foreach ($unconcluded_tasks as $task) {
                
                if (Carbon::now()->diffInMinutes($task->end_date, false) < 1) {
                    // task is overdue
                    $overdue_tasks[] = $task;
                    $task->isOverdue = 1;
                    $task->update();
                } else {
                    $open_tasks[] = $task;
                }
            }

            if (count($open_tasks) == 0 && count($overdue_tasks) == 0) continue;

            Mail::to($user->email)->queue((new TaskReminder($user, $open_tasks, $overdue_tasks)));
            
            sleep(5);
        }


        // $next_reminder_date = (new \DateTime)->modify('+' . $email->getReminderFrequency());

        // $tasks = Task::where('isConcluded', 0)->where('email_id', $email->id)->where(function($query) use ($next_reminder_date) {
        //     $query->whereNull('last_reminder_sent_at')
        //           ->orWhere('last_reminder_sent_at', '>=', $next_reminder_date);
        // })->get();

        // foreach ($tasks as $task) {
        //     // Mail::to();

        //     $time_remaining = (Carbon::now())->diffInHours($task->end_date);

        //     dd($time_remaining);

        //     $task->update([
        //         'last_reminder_sent_at' => (new \DateTime)->format('Y-m-d H:i:s')
        //     ]);
        // }
    }

    // private function getTaskDueSummaryInHours($hours)
    // {
    //     if ($hours > 1) {
    //         return "You have a pending task that will be due in {$hours} hours";
    //     } else {
    //         return "Your pending task was due {$hours} hours ago";
    //     }
    // }

    // private function getTaskDueSummaryInMinutes($minutes)
    // {
    //     if ($minutes > 1) {
    //         return "You have a pending task that will be due in {$minutes} minutes";
    //     } else {
    //         return "Your pending task was due {$minutes} minutes ago";
    //     }
    // }

}