@extends('layouts.tenant_sidebar')

@section('title')

    Dashboard

@endsection

@section('breadcrumb')

    <li class="active"> Dashboard </li>

@endsection

@section('content')


    <div class="row">

        <div class="col-md-3 col-sm-6">
            <div class="widget widget-stats bg-green">
                <div class="stats-icon stats-icon-lg"><i class="fa fa-globe fa-fw"></i></div>
                <div class="stats-title text-white">TODAY'S TASK</div>
                <div class="stats-number">0</div>
                <div class="stats-progress progress">
                    <div class="progress-bar" style="width: 70.1%;"></div>
                </div>
                <div class="stats-desc"> Your Tasks  </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="widget widget-stats bg-blue">
                <div class="stats-icon stats-icon-lg"><i class="fa fa-tags fa-fw"></i></div>
                <div class="stats-title">INBOUND CALLS </div>
                <div class="stats-number">0</div>
                <div class="stats-progress progress">
                    <div class="progress-bar" style="width: 40.5%;"></div>
                </div>
                <div class="stats-desc">No. Of calls You Received ()</div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="widget widget-stats bg-purple">
                <div class="stats-icon stats-icon-lg"><i class="fa fa-shopping-cart fa-fw"></i></div>
                <div class="stats-title">GENERATED QUOTE/INVOICE</div>
                <div class="stats-number">0</div>
                <div class="stats-progress progress">
                    <div class="progress-bar" style="width: 76.3%;"></div>
                </div>
                <div class="stats-desc">Quote (76.3%) </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="widget widget-stats bg-black">
                <div class="stats-icon stats-icon-lg"><i class="fa fa-comments fa-fw"></i></div>
                <div class="stats-title">OUTBOUND CALLS </div>
                <div class="stats-number">0</div>
                <div class="stats-progress progress">
                    <div class="progress-bar" style="width: 54.9%;"></div>
                </div>
                <div class="stats-desc">Used (0%) this week</div>
            </div>
        </div>
        <!-- end col-3 -->
    </div>

    <div class="fluid_container">
        <div class="row">
            <div class="col-md-12 bg-white clearfix p-t-10">

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Start Time </label>
                        <input type="text" name="start_time" class="datepicker" value="">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>End Time </label>
                        <input type="text" name="start_time" class="datepicker" value="">
                    </div>
                </div>

            </div>
        </div>
    </div>
    
@endsection
