<?php

namespace App\Http\Controllers\App\Tenant\Media;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CDR;

class MediaServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['tenant', 'auth:web']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $domain)
    {
        
        $cdrs = (new CDR)->newQuery()->company();
        $start_date = $request->start_date ? (new \DateTime($request->start_date))->format('Y-m-d') : (new \DateTime)->modify('-3 month')->format('Y-m-d');
        $end_date = $request->end_date ? (new \DateTime($request->end_date))->format('Y-m-d') : (new \DateTime)->format('Y-m-d');
        
        if ( $request->has('direction') && $request->direction != 'All' ) {
            $direction = trim( strtolower($request->direction) );
            $cdrs = $cdrs->where('direction', $direction );
        }

        if ( $request->has('status') && $request->status != 'All' ) {
            $status = trim( strtolower($request->status) );
            $cdrs = $cdrs->where('status', $status );
        }

        $cdrs = $cdrs->whereDate('start_timestamp', '>=', $start_date)->whereDate('start_timestamp', '<=', $end_date); 
   




        $cdrs = $cdrs->orderBy('start_timestamp')->paginate(50);
        return view('app.tenant.media-services.index', compact('cdrs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($domain, Request $request, $id)
    {
        $cdr = CDR::company()->where('id', $id)->get()->first() ?? abort(404);
     
        $filesize = $this->getFileSize(asset('storage/'.$cdr->call_recording));
        return view('app.tenant.media-services.show', compact('cdr', 'filesize'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    function getFileSize($file) {
        ob_start();
        $ch = curl_init($file);
        curl_setopt($ch, CURLOPT_HEADER, 1);

        $ok = curl_exec($ch);
        curl_close($ch);
        $head = ob_get_contents();
        ob_end_clean();

        $regex = '/Content-Length:\s([0-9].+?)\s/';
        $count = preg_match($regex, $head, $matches);

        return isset($matches[1]) ? $matches[1] : null;
    }
}
