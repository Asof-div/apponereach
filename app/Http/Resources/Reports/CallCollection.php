<?php

namespace App\Http\Resources\Reports;

use App\Services\Calendar;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CallCollection extends ResourceCollection {
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request) {
		$calendar = new Calendar;
		$weeks    = $calendar->calculateWeeks(Carbon::now());

		$items = $this->collection;

		$group_week = array();
		foreach ($weeks as $key => $week) {

			$start_date = Carbon::parse($week['sdate']);
			$end_date   = Carbon::parse($week['edate']);
			for ($date = $start_date; $date <= $end_date; $date = $date->copy()->addDay()) {

				$filtered = $items->filter(function ($item) use ($date) {
						if ($item->start_time->format('Y-m-d') == $date->format('Y-m-d')) {return true;}
						return false;
					});

				$group_week['date'][$date->format('m-d')] = [
					'success' => $filtered->whereIn('status', ['SUCCESS', 'CONNECTED'])->count(),
					'failed'  => $filtered->whereNotIn('status', ['SUCCESS', 'CONNECTED'])->count(),
					'airtime' => $filtered->whereNotIn('status', ['SUCCESS', 'CONNECTED'])->pluck('airtime')->sum()
				];
			}

			$filtered = $items->filter(function ($item) use ($week) {
					if ($item->start_time >= Carbon::parse($week['sdate']) && $item->start_time <= Carbon::parse($week['edate'])) {return true;}
					return false;
				});

			$group_week['week']['week'.($key+1)] = [
				'success' => $filtered->whereIn('status', ['SUCCESS', 'CONNECTED'])->count(),
				'failed'  => $filtered->whereNotIn('status', ['SUCCESS', 'CONNECTED'])->count(),
				'airtime' => $filtered->whereNotIn('status', ['SUCCESS', 'CONNECTED'])->pluck('airtime')->sum()
			];

		}

		return

		array_merge($group_week, [
				'currency' => $items->first()?$items->first()->currency:'',
				'name'     => $items->first()?$items->first()->tenant->name:'',
				'month'    => [
					'success' => $items->whereIn('status', ['SUCCESS', 'CONNECTED'])->count(),
					'failed'  => $items->whereNotIn('status', ['SUCCESS', 'CONNECTED'])->count(),
					'airtime' => $items->whereNotIn('status', ['SUCCESS', 'CONNECTED'])->pluck('airtime')->sum()
				],

			]);

	}

}
