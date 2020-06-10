<?php

namespace App\Http\Controllers\App\Tenant\Conference;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Conference;
use App\Models\PlayMedia;
use App\Models\PrivateMeeting;

class PrivateConferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $conferences = Conference::company()->where('type', 'Private')->get();
        $sounds = PlayMedia::company()->where('application', 'file')->get();
        $privates = PrivateMeeting::company()->get();       

        return view('app.tenant.conference.private_conference.create', compact('conferences', 'privates', 'sounds'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $conferences = Conference::company()->where('type', 'Private')->get();
        $sounds = PlayMedia::company()->where('application', 'file')->get();
        $privates = PrivateMeeting::company()->get();       

        return view('app.tenant.conference.private_conference.create', compact('conferences', 'privates', 'sounds'));  
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
    public function show($id)
    {
        //
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
}
