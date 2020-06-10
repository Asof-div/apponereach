<?php 

namespace App\Services\Tenant;

use App\Models\Todo;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\MessageBag;


use App\Scopes\Facade\TenantManagerFacade as TenantManager;

class TaskService
{

	public function __construct(){

        
	}

	public function store($data){

        try{
            $tenant = TenantManager::get();
            $task = Todo::find($data['task_id']);
            $start_time = $this->trimTimeFormat($data['start_time']);
            $end = $this->calculateDuration($data['start_date'], $start_time, $data['duration'], $data['duration_unit']);

            $daily_repeat_freq = isset( $data['daily_repeat_freq'] ) ? $data['daily_repeat_freq'] : null;
            $weekly_repeat_freq = isset( $data['weekly_repeat_freq'] ) ? $data['weekly_repeat_freq'] : null;
            $monthly_repeat_freq = isset( $data['monthly_repeat_freq'] ) ? $data['monthly_repeat_freq'] : null;
            $weekly_repeat_days = isset( $data['weekly_repeat_days'] ) ? $data['weekly_repeat_days'] : null;
            $monthly_repeat_type = isset( $data['monthly_repeat_type'] ) ? $data['monthly_repeat_type'] : null;
            $monthly_day_num = isset( $data['monthly_day_num'] ) ? $data['monthly_day_num'] : null;
            $monthly_day_pos = isset( $data['monthly_day_pos'] ) ? $data['monthly_day_pos'] : null;
            $monthly_day_name = isset( $data['monthly_day_name'] ) ? $data['monthly_day_name'] : null;
            $repeat_end_type = isset( $data['repeat_end_type'] ) ? $data['repeat_end_type'] : null;
            $repeat_interval = isset( $data['repeat_interval'] ) ? $data['repeat_interval'] : null;
            $repeat_end_date = isset( $data['repeat_end_date'] ) ? $data['repeat_end_date'] : null;
            $repeat_task = isset( $data['repeat_task'] ) ? $data['repeat_task'] : null;

            $repeat_interval_opts = ['daily_repeat_freq' => $daily_repeat_freq, 'weekly_repeat_freq' => $weekly_repeat_freq, 'monthly_repeat_freq' => $monthly_repeat_freq, 'weekly_repeat_days' => $weekly_repeat_days, 'monthly_repeat_type' => $monthly_repeat_type, 'monthly_day_num' => $monthly_day_num, 'monthly_day_pos' => $monthly_day_pos, 'monthly_day_name' => $monthly_day_name];

            if ($repeat_task && $repeat_interval == 'Weekly') {
                $day = date('D', strtotime($this->getStartDate($data['start_date'], $data['start_time'])));
                $day = strtoupper(substr($day, 0, 2));

                if (!in_array($day, $weekly_repeat_days)) {
                    $errors = new MessageBag();
                    $errors->add('invalid days selection', 'Invalid days selection. Your selected weekly days must include your task start day');
                    return ['response' => [ 'error' => $errors->all() ], 'code' => 422 ];        
                }
            }

            if(!$task){
                $task = Todo::create([
                        'title' => $data['title'],
                        'tenant_id' => $tenant->id,
                        'assigner_id' => Auth::id(),
                        'assignee_id' => isset($data['myself']) ? $data['myself'] : $data['assignee'],
                        'description' => $data['description'],
                        'status' => isset($data['status']) ? $data['status'] : 'open',
                        'priority' => $data['priority'],
                        'type' => $data['task_type'],
                        'start_date' => $data['start_date'],
                        'start_time' => $start_time,
                        'duration' => $data['duration'],
                        'duration_unit' => $data['duration_unit'],
                        'end_date' => $end->format('Y-m-d'),
                        'end_time' => $end->format('H:i:s'),
                        'repeat_task' => isset($data['repeat_task']) ? 1 : 0,
                        'repeat_interval_opts' => isset($data['repeat_task']) ? $repeat_interval_opts : null,
                        'repeat_interval' => isset($data['repeat_task']) ? $repeat_interval : null,
                        'repeat_end_type' => isset($data['repeat_task']) ? $repeat_end_type : null,
                        'repeat_end_date' => isset($data['repeat_task']) && $repeat_end_type == 'Date' ? (new \DateTime($repeat_end_date))->format('Y-m-d H:i:s') : null,
                        'last_repeated_at' => $data['repeat_task'] ? $this->getStartDate($data['start_date'], $data['start_time']) : null,

                    ]);

            }else{
                $task->update([
                    'title' => $data['title'],
                    'tenant_id' => $tenant->id,
                    'assigner_id' => Auth::id(),
                    'assignee_id' => isset($data['myself']) ? $data['myself'] : $data['assignee'],
                    'description' => $data['description'],
                    'status' => isset($data['status']) ? $data['status'] : 'open',
                    'priority' => $data['priority'],
                    'type' => $data['task_type'],
                    'start_date' => $data['start_date'],
                    'start_time' => $start_time,
                    'duration' => $data['duration'],
                    'duration_unit' => $data['duration_unit'],
                    'end_date' => $end->format('Y-m-d'),
                    'end_time' => $end->format('H:i:s'),
                    'repeat_task' => isset($data['repeat_task']) ? 1 : 0,
                    'repeat_interval_opts' => isset($data['repeat_task']) ? $repeat_interval_opts : null,
                    'repeat_interval' => isset($data['repeat_task']) ? $repeat_interval : null,
                    'repeat_end_type' => isset($data['repeat_task']) ? $repeat_end_type : null,
                    'repeat_end_date' => isset($data['repeat_task']) && $repeat_end_type == 'Date' ? (new \DateTime($repeat_end_date))->format('Y-m-d H:i:s') : null,
                    'last_repeated_at' => $data['repeat_task'] ? $this->getStartDate($data['start_date'], $data['start_time']) : null,

                ]);
            }


    


            return ['response' => [ 'success' => 'Task Successfully Saved' ], 'code' => 200 ];
            

        }catch(\Expection $e){
            return ['response' => [ 'error' => ['Unable to save task'] ], 'code' => 422 ];        

        }        
	}


    function calculateDuration($start_date, $start_time, $duration, $duration_unit){
        $start = Carbon::parse($start_date .' '. $start_time );
        $end = $start->copy();
        switch (strtolower($duration_unit)) {
            case 'hour':
                $end->addHours($duration);
                break;
            case 'day':
                $end->addDays($duration);
                break;
            case 'week':
                $end->addWeeks($duration);
                break;
            case 'month':
                $end->addMonths($duration);
                break;
            default:
                break;
        }
        return $end;
    }

    function trimTimeFormat($start_time){
        $collect_start_time = explode(':', $start_time);
        $hour = isset($collect_start_time[0]) ? trim($collect_start_time[0]) : '01';
        $min = isset($collect_start_time[1]) ? trim($collect_start_time[1]) : '30';
        return sprintf('%s:%s', $hour, $min);
    }

    function getStartDate($start_date, $start_time) : Carbon
    {
        
        $start_time = $this->trimTimeFormat($start_time);
        return Carbon::parse($start_date .' '. $start_time );
    }

    public function status($data){

        try{
            $tenant = TenantManager::get();
            
            $task = Todo::find($data['task_id']);
            
            if($task){

                $task->update([
                    'status' => $data['status'],          
                ]);
                

                return ['response' => [ 'success' => 'Task Status Successfully Updated' ], 'code' => 200 ];
            }else{

                return ['response' => [ 'error' => 'Task not found' ], 'code' => 422 ];        
            }

        }catch(\Expection $e){
            return ['response' => [ 'error' => 'Unable to save task' ], 'code' => 422 ];        

        }        
    }





}