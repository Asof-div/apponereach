<?php

namespace App\Http\Resources\Reports;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Services\Calendar;
use Carbon\Carbon;

class OrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $calendar = new Calendar;
        $weeks = $calendar->calculateWeeks(Carbon::now());

        $grouped = $this->collection->groupBy('tenant_id');
        return 
            
            $grouped->map(function ($items) use ($request, $weeks){
                $group_week = array();
                foreach ($weeks as $key => $week) {
                    $filtered = $items->filter(function($item) use ($week){
                        if($item->ordered_date >= Carbon::parse($week['sdate']) && $item->ordered_date <= Carbon::parse($week['edate'])) { return true; }
                        return false;
                    });

                     if($request->filter_type == 'value'){

                         $group_week['week'.($key+1)] = ['completed' => $filtered->where('payment_status', true)->pluck('charged')->sum(), 'uncompleted' => $filtered->where('payment_status', false)->pluck('charged')->sum() ];
                     }else{

                         $group_week['week'.($key+1)] = ['completed' => $filtered->where('payment_status', true)->pluck('charged')->count(), 'uncompleted' => $filtered->where('payment_status', false)->pluck('charged')->count() ];
                     }
                }

                if($request->filter_type == 'value'){

                        $group_week['month'] = ['completed' => $items->where('payment_status', true)->pluck('charged')->sum(), 'uncompleted' => $items->where('payment_status', false)->pluck('charged')->sum() ];

                    }else{

                        $group_week['month'] = ['completed' => $items->where('payment_status', true)->pluck('charged')->count(), 'uncompleted' => $items->where('payment_status', false)->pluck('charged')->count() ];

                    }
                return 
                    
                    array_merge($group_week, [
                    'currency' => $items->first()->currency,
                    'name' => $items->first()->tenant->name,
                    
                    

                ]);
            })
            
        ;

        // return [

        //     $this->collection->map(function ($item) use ($request){
        //         return (new OrderResource($item))->toArray($request);
        //     })
        // ];   
    }




}
