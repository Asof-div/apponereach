@extends('layouts.tenant_sidebar')

@section('title')
    
    CALL SUMMARY 

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li class="active"> Call Summary </li>

@endsection

@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel panel-default">
            
            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 bg-white clearfix p-t-10 p-0">

                    <form method="get" action="{{ route('tenant.media-service.index', [$tenant->domain]) }}">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="f-s-15">Direction </label>
                                <select name="direction" class="form-control">
                                    <option {{ request()->direction == 'All' ? 'selected' : '' }} value="All">All</option>
                                    <option {{ request()->direction == 'Inbound' ? 'selected' : '' }} value="Inbound">Inbound</option>
                                    <option {{ request()->direction == 'Outbound' ? 'selected' : '' }} value="Outbound">Outbound</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="f-s-15">Status </label>
                                <select name="status" class="form-control">
                                    <option {{ request()->status == 'All' ? 'selected' : '' }} value="All">All</option>
                                    <option {{ request()->status == 'Connected' ? 'selected' : '' }} value="Connected">Connected</option>
                                    <option {{ request()->status == 'Failed' ? 'selected' : '' }} value="Failed">Failed</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="f-s-15">Start Date </label>
                                <input type="text" name="start_date" class="datepicker form-control" value="{{ request()->start_date ? (new \DateTime(request()->start_date))->format('Y-m-d') : (new \DateTime)->modify('-3 month')->format('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="f-s-15">End Date </label>
                                <input type="text" name="end_date" class="datepicker form-control" value="{{ request()->end_date ? (new \DateTime(request()->end_date))->format('Y-m-d') : (new \DateTime)->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="col-md-12"></div>
                                <button class="btn btn-success" type="submit"> <i class="fa fa-search"></i> Query</button>
                            </div>
                        </div>
                    </form>


                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Caller </th>
                                    <th>Receiver </th>
                                    <th>Source </th>
                                    <th>Direction </th>
                                    <th>Duration </th>
                                    <th>Status </th>
                                    <th>Dest Type</th>
                                    <th>Dest</th>
                                    <th>Start Date </th>
                                    <th>End Date </th>
                                    <th>Recording State</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($cdrs as $index => $cdr)

                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $cdr->caller_id_num }}</td>
                                    <td>{{ $cdr->callee_id_num }}</td>
                                    <td>{{ $cdr->source }}</td>
                                    <td>{!! $cdr->direction() !!}</td>
                                    <td>{{ $cdr->duration() }}</td>
                                    <td>{!! $cdr->status() !!}</td>
                                    <td>{{ $cdr->destination_type }}</td>
                                    <td>{{ $cdr->destination }}</td>
                                    <td>{{ $cdr->start_timestamp }}</td>
                                    <td>{{ $cdr->end_timestamp }}</td>
                                    <td>{{ $cdr->recorded ? 'Yes' : 'No' }}</td>
                                    <td><a href="{{ route('tenant.media-service.show', [$tenant->domain, $cdr->id]) }}" class="btn btn-info btn-xs">Details</a></td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-right clearfix"> 
                        {!! $cdrs->appends(request()->query())->links() !!}
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection


@section('extra-script')

    <script type="text/javascript">

        $mn_list = $('.sidebar ul.nav > li.nav-media-services');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-summary').addClass('active');


        var _token = "{{csrf_token()}}";

    </script>


@endsection

@section('extra-css')

    <style>
        
   
        

    </style>

@endsection