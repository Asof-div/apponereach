<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Package;

class LandingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $basic = Package::where('name', 'Basic')->with(['addons'])->get()->first();
        $standard = Package::where('name', 'Standard')->with(['addons'])->get()->first();
        $executive = Package::where('name', 'Executive')->with(['addons'])->get()->first();
        $addons = Addon::orderBy('binary_index')->get();
        $packages = Package::where('name', '<>', 'Free')->where('price', '<>', 0)->orderBy('price')->with(['addons'])->get();

        return view('app.surfing.welcome', compact('basic', 'standard', 'executive', 'addons', 'packages'));
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

    public function domainNotFound(){

        return view('app.errors.domain_not_found');
    }
}
