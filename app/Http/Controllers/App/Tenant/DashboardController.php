<?php

namespace App\Http\Controllers\App\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CallFlow;
use App\Models\CDR;
use App\Models\Tasks;
use App\Models\Quote;
use App\Models\Invoice;

use Carbon\Carbon;

class DashboardController extends Controller
{
    
    public function __construct()
    {
        $this->middleware(['tenant', 'auth:web']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $domain)
    {

        $cdrs = (new CDR)->newQuery()->company();

        $start_date = $request->start_date ? (new \DateTime($request->start_date))->format('Y-m-d') : (new \DateTime)->modify('-3 month')->format('Y-m-d');

        $start = Carbon::parse($start_date );

        $end_date = $request->end_date ? (new \DateTime($request->end_date))->format('Y-m-d') : (new \DateTime)->format('Y-m-d');

        $end = Carbon::parse($end_date)->endOfMonth();

        $cdrs = $cdrs->whereDate('start_timestamp', '>=', $start)->whereDate('start_timestamp', '<=', $end)->get(); 

        $quote = Quote::whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->get();


        
        
        return view('app.tenant.dashboard', compact('quote', 'cdrs'));
    }

    public function mismatch(Request $request, $domain){

        return view('app.tenant.mismatch');
    }
}
