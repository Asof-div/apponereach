<?php

namespace App\Http\Controllers\App\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Subscription;
use App\Models\Order;
use App\Models\Tenant;
use App\Models\Call;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\Package;

use App\Http\Resources\PackageResource;
use App\Http\Resources\PackageCollection;
use App\Http\Resources\Reports\OrderCollection;
use App\Http\Resources\Reports\CallCollection;

class DashboardController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:operator');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $start_date = $request->start_date ? (new \DateTime($request->start_date))->format('Y-m') : (new \DateTime)->modify('-6 month')->format('Y-m');
        $today = Carbon::today();

        $start = Carbon::parse($start_date );

        $calls = Call::whereYear('start_time', $today->format('Y'))->whereMonth('start_time', $today->format('m'))->get();

        $connected_calls = $calls->filter(function($item, $key){
            if( strtolower($item->status) == 'connected' || strtolower($item->status) == 'success' && $item->start_time->format('Y-m-d') == date('Y-m-d') ){
                return true;
            }
            return false; 
        });


        $open_conversations = Ticket::where('status', 'Unassigned')->orWhere('status', 'Open')->get();

        $end_date = $request->end_date ? (new \DateTime($request->end_date))->format('Y-m') : (new \DateTime)->format('Y-m');

        $end = Carbon::parse($end_date)->endOfMonth();

        $orders =  Order::whereYear('ordered_date', $today->format('Y'))->whereMonth('ordered_date', $today->format('m'))->where('status', 'success')->get();
        $actual_revenue = array_sum($orders->pluck('charged')->toArray());
        $total_revenue = array_sum($orders->pluck('amount')->toArray());
        $customers =  Tenant::get();

        $number_of_active_subscribers = $customers->filter(function($item){ return strtolower($item->status) == 'activated' ? true : false;  })->load(['subscriptions' => function($query) use ($today){
                $query->whereDate('start_time', '<=', $today)->whereDate('end_time', '>=', $today)->where('status', 'ilike','success');
            }]);


        // return $calls;

        // return dd($packages->toArray($request));
        // $orders = Order::whereDate('ordered_date', $today->format('Y-m'))->get();
        $total_order_per_week = (new OrderCollection($orders))->toArray($request);
        $request->filter_type ="value";
        $total_value_order_per_week = (new OrderCollection($orders))->toArray($request);
        $calls_report = (new CallCollection($calls))->toArray($request);
        // return dd($calls_report['date']);

        return view('app.operator.user.dashboard', compact('customers', 'number_of_active_subscribers', 'actual_revenue', 'total_revenue', 'open_conversations', 'connected_calls', 'total_order_per_week', 'total_value_order_per_week', 'calls_report'));
    }

}
