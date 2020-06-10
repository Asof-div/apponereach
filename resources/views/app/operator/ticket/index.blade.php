@extends('layouts.operator_sidebar')

@section('title')
    
    TICKET MANAGEMENT ({{ $tickets->total() }})

@endsection

@section('breadcrumb')

    <li ><a href="{{ route('operator.dashboard') }}"> Dashboard </a></li>
    <li class="active"> Tickets </li>

@endsection

@section('content')


    <div class="col-md-12 col-sm-12 col-xs-12 no-p">
        <div class="panel ">
            <div class="panel-heading"> 
                <div class="panel-title pt-2">
                    <span class="h3"> &nbsp; Tickets ({{ $tickets->count() }}) 
                    </span>
                </div> 
                <span class="pull-right mr-2">
                    <a href="{{ route('operator.ticket.create') }}" class="btn btn-success"> <i class="fa fa-plus-circle"></i> Add Ticket &nbsp; <span class="text-primary"> </span> </a>
                    <button class="btn btn-default" data-toggle="modal" data-target="#filter_ticket_modal"> <i class="fa fa-filter"></i> Custom Filter </button>
                </span>

            </div>

            <div class="panel-body" style="min-height: 400px;">
                <hr class="horizonal-line-thick">
                <div class="col-md-12 no-p m-b-10">
                    <form action="{{ route('operator.ticket.index') }}" method="get">
                        
                        <div class="col-md-3 clearfix">
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

                        <div class="col-md-2 clearfix">
                            <div class="input-group m-b-5">
                                <span class="input-group-addon" id="status">Status</span>
                                <select name="status" class="form-control" aria-describedby="status">
                                    <option value=""> &dash; &dash; Select Status &dash; &dash; </option>
                                    <option {{ strtolower(request()->status) == 'all' ? 'selected' : '' }} value="All">All</option>
                                    <option {{ strtolower(request()->status) == 'unassigned' ? 'selected' : '' }} value="Unassigned">Unassigned</option>
                                    <option {{ strtolower(request()->status) == 'open' ? 'selected' : '' }} value="Open">Open</option>
                                    <option {{ strtolower(request()->status) == 'pending' ? 'selected' : '' }} value="Pending">Pending</option>
                                    <option {{ strtolower(request()->status) == 'closed' ? 'selected' : '' }} value="Closed">Closed</option>
                                    <option {{ strtolower(request()->status) == 'resolved' ? 'selected' : '' }} value="Resolved">Resolved</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 clearfix">
                            <div class="form-group m-b-5">
                                <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Search </button>                                
                            </div>
                        </div>
                        


                    </form>
                </div>
                

                <hr class="horizonal-line-thick" />

                <div class="col-md-12 col-sm-12 col-xs-12 p-t-15 p-b-15 bg-silver">

                    @include('app.operator.ticket.partials.table')

                </div>

            </div>


            <div class="modal fade" id="assign_ticket_modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('operator.ticket.assign') }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}

                            <input type="hidden" name="ticket_id">
                            <input type="hidden" name="page" value="index">

                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Assign Ticket</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>User</label>
                                    <select class="form-control" name="user_id">
                                        <option value="">Select User </option>
                                        @foreach($operators as $operator)
                                            <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-warning"> <i class="fa fa-hand-o-right"></i> Assign Ticket</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="filter_ticket_modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('operator.ticket.assign') }}" method="GET">

                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Assign Ticket</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">

                                    <div class="form-group clearfix">
                                        <div class="input-group m-b-5">
                                            <span class="input-group-addon" id="client-name">Customer</span>
                                            <input type="text" name="name" value="{{ request()->name }}" class="form-control" aria-describedby="client-name">
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
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
                                    <div class="form-group clearfix">
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

                                    <div class="form-group clearfix">
                                        <div class="input-group m-b-5">
                                            <span class="input-group-addon" id="status">Status</span>
                                            <select name="status" class="form-control" aria-describedby="status">
                                                <option value="">&dash; &dash;  Select Status &dash; &dash; </option>
                                                <option {{ strtolower(request()->status) == 'all' ? 'selected' : '' }} value="All">All</option>
                                                <option {{ strtolower(request()->status) == 'unassigned' ? 'selected' : '' }} value="Unassigned">Unassigned</option>
                                                <option {{ strtolower(request()->status) == 'open' ? 'selected' : '' }} value="Open">Open</option>
                                                <option {{ strtolower(request()->status) == 'pending' ? 'selected' : '' }} value="Pending">Pending</option>
                                                <option {{ strtolower(request()->status) == 'closed' ? 'selected' : '' }} value="Closed">Closed</option>
                                                <option {{ strtolower(request()->status) == 'resolved' ? 'selected' : '' }} value="Resolved">Resolved</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group clearfix">
                                        <div class="input-group m-b-5">
                                            <span class="input-group-addon" id="user"> User</span>
                                            <select name="user" class="form-control" aria-describedby="status">
                                                <option {{ strtolower(request()->user) == 'all' ? 'selected' : '' }} value="All">All</option>
                                                @foreach($operators as $operator)
                                                    <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group clearfix">
                                        <div class="input-group m-b-5">
                                            <span class="input-group-addon" id="start_date">Start Date</span>
                                            <input type="text" name="start_date" value="{{ request()->start_date }}" class="form-control datepicker" aria-describedby="start_date">
                                            
                                        </div>
                                    </div>

                                    <div class="form-group clearfix">
                                        <div class="input-group m-b-5">
                                            <span class="input-group-addon" id="start_date">End Date</span>
                                            <input type="text" name="end_date" value="{{ request()->end_date }}" class="form-control datepicker" aria-describedby="end_date">
                                            
                                        </div>
                                    </div>



                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success"> <i class="fa fa-search"></i> Search Ticket</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            

        </div>
    </div>


@endsection


@section('extra-script')    

    <script>
       
        $mn_list = $('.page-sidebar-inner ul.sidebar-nav > li.ticket');
        $mn_list.addClass('active open');
        $mn_list.find('.sub-menu > .ticket-index').addClass('active');

        $('#assign_ticket_modal').on('show.bs.modal', function(evt) {
            var button = $(evt.relatedTarget);
            $('[name="ticket_id"]').val(button.data('ticket_id'));
        });

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