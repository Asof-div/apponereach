<?php

namespace App\Services;

use Carbon\Carbon;

class Calendar {


	function getWeekDates($date, $start_date, $end_date)
	{

	    $week =  date('W', strtotime($date));
	    $year =  date('Y', strtotime($date));
	    $from = date("Y-m-d", strtotime("{$year}-W{$week}+1"));
	    if($from < $start_date) $from = $start_date->format('Y-m-d');

	    $to = date("Y-m-d", strtotime("{$year}-W{$week}-7")); 
	    if($to > $end_date) $to = $end_date->format('Y-m-d');

		$array1 = array(
		        "sdate" => $from,
		        "edate" => $to,
		);

		return $array1;

	   // echo "Start Date-->".$from."End Date -->".$to;
	}


	public function calculateWeeks($date){

		$start_date = $date->copy()->firstOfMonth();
		$end_date = $date->copy()->endOfMonth();
		$end_date1 = $date->copy()->endOfMonth()->addDays(6);
		$count_week=0;
		$week_array = array();

		for($date = $start_date; $date <= $end_date1; $date = date('Y-m-d', strtotime($date. ' + 7 days')))
		{
		    $getarray = $this->getWeekDates($date, $start_date, $end_date);
			
			$week_array[]=$getarray;
			$count_week++;

		}

		return $week_array;

	}


}