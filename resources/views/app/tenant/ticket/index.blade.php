@extends('layouts.tenant_sidebar')

@section('title')
    
    TICKET MANAGEMENT

@endsection

@section('breadcrumb')

    <li ><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li class="active"> Tickets </li>

@endsection

@section('content')


    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel ">
            <div class="panel-heading"> 
                <div class="panel-title pt-2">
                    <span class="h3"> &nbsp; Tickets ({{ $tickets->count() }}) 
                    </span>
                    <span class="pull-right mr-2">
                        <a href="{{ route('tenant.ticket.create', [$tenant->domain]) }}" class="btn btn-success"> <i class="fa fa-plus-circle"></i> Add Ticket &nbsp; <span class="text-primary"> </span> </a>
                    </span>
                </div>
            </div>

            <div class="panel-body" style="min-height: 400px;">
                <hr class="horizonal-line-thick">
                <div class="col-md-12 no-p m-b-10">
                    <form action="{{ route('tenant.ticket.index', [$tenant->domain]) }}" method="get">
                        <div class="col-md-4 clearfix">
                            <div class="input-group m-b-5">
                                <span class="input-group-addon" id="incident">Incident</span>
                                <select name="incident" class="form-control" aria-describedby="incident">
                                    <option {{ strtolower(request()->incident) == 'all' ? 'selected' : '' }} value="All">All</option>
                                    @foreach($incidents as $incident)
                                        <option value="{{ $incident->id }}">{{ $incident->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 clearfix">
                            <div class="input-group m-b-5">
                                <span class="input-group-addon" id="priority">Priority </span>
                                <select name="priority" class="form-control" aria-describedby="priority">
                                    <option {{ strtolower(request()->priority) == 'All' ? 'selected' : '' }} value="All">All</option>
                                    <option {{ strtolower(request()->priority) == 'low' ? 'selected' : '' }} value="Low">Low</option>
                                    <option {{ strtolower(request()->priority) == 'medium' ? 'selected' : '' }} value="Medium">Medium</option>
                                    <option {{ strtolower(request()->priority) == 'high' ? 'selected' : '' }} value="High">High</option>
                                    <option {{ strtolower(request()->priority) == 'urgent' ? 'selected' : '' }} value="Urgent">Urgent</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 clearfix">
                            <div class="input-group m-b-5">
                                <span class="input-group-addon" id="status">Status</span>
                                <select name="status" class="form-control" aria-describedby="status">
                                    <option {{ strtolower(request()->status) == 'all' ? 'selected' : '' }} value="All">All</option>
                                    <option {{ strtolower(request()->status) == 'unassigned' ? 'selected' : '' }} value="Unassigned">Unassigned</option>
                                    <option {{ strtolower(request()->status) == 'open' ? 'selected' : '' }} value="Open">Open</option>
                                    <option {{ strtolower(request()->status) == 'pending' ? 'selected' : '' }} value="Pending">Pending</option>
                                    <option {{ strtolower(request()->status) == 'closed' ? 'selected' : '' }} value="Closed">Closed</option>
                                    <option {{ strtolower(request()->status) == 'resolved' ? 'selected' : '' }} value="Resolved">Resolved</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-4 clearfix">
                            <div class="input-group m-b-5">
                                <span class="input-group-addon" id="start_date">Start Date</span>
                                <input type="text" name="start_date" value="{{ request()->start_date }}" class="form-control datepicker" aria-describedby="start_date">
                                
                            </div>
                        </div>

                        <div class="col-md-4 clearfix">
                            <div class="input-group m-b-5">
                                <span class="input-group-addon" id="start_date">End Date</span>
                                <input type="text" name="end_date" value="{{ request()->end_date }}" class="form-control datepicker" aria-describedby="end_date">
                                
                            </div>
                        </div>

                        <div class="col-md-4 clearfix">
                            <div class="form-group m-b-5">
                                <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Search </button>                                
                            </div>
                        </div>
                        


                    </form>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 p-t-15 p-b-15 bg-silver">

                    @include('app.tenant.ticket.partials.table')

                </div>

            </div>



        </div>
    </div>


@endsection


@section('extra-script')    

    <script>
       
        $mn_list = $('.sidebar ul.nav > li.nav-ticket');
        $mn_list.addClass('active');

    </script>


@endsection

@section('extra-css')

    <style>
       
        ul.nav.nav-pills li.active a{
            background: #51BB8D !important;
            color: #fff !important;
            font-weight: bold;
        }

    </style>

@endsection