@extends('layouts.operator_sidebar')

@section('title')
    
    TICKET MANAGEMENT ({{ $tickets->total() }})

@endsection

@section('breadcrumb')

    <li ><a href="{{ route('operator.dashboard') }}"> Dashboard </a></li>
    <li ><a href="{{ route('operator.ticket.index') }}"> Tickets </a></li>
    <li class="active"> Unassigned </li>

@endsection

@section('content')


    <div class="col-md-12 col-sm-12 col-xs-12 no-p">
        <div class="panel ">
            <div class="panel-heading"> 
                <div class="panel-title pt-2">
                    <span class="h3"> &nbsp;  Unassigned Tickets ({{ $tickets->count() }}) 
                    </span>
                </div> 
                <span class="pull-right mr-2">
                    <a href="{{ route('operator.ticket.create') }}" class="btn btn-success"> <i class="fa fa-plus-circle"></i> Add Ticket &nbsp; <span class="text-primary"> </span> </a>
                </span>

            </div>

            <div class="panel-body" style="min-height: 400px;">
                <hr class="horizonal-line-thick">
                <div class="col-md-12 no-p m-b-10">
                    <form action="{{ route('operator.ticket.unassigned') }}" method="get">
                        <div class="col-md-4 clearfix">
                            <div class="input-group m-b-5">
                                <span class="input-group-addon" id="client-name">Customer</span>
                                <input type="text" name="name" value="{{ request()->name }}" class="form-control" aria-describedby="client-name">
                            </div>
                        </div>
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
                        <div class="col-md-3 clearfix">
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

                        <div class="col-md-2 clearfix">
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

                        <div class="col-md-2 clearfix">
                            <div class="form-group m-b-5">
                                <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Search </button>                                
                            </div>
                        </div>
                        


                    </form>
                </div>
                

                <hr class="horizonal-line-thick" />

                <div class="col-md-12 col-sm-12 col-xs-12 p-t-15 p-b-15 bg-silver">

                    <div class="table-responsive bg-white p-10">
                        <table class="table table-condensed table-hover table-striped">
                            <thead>
                                <tr>

                                    <th>S/N</th>
                                    <th>Title</th>
                                    <th>Subject</th>
                                    <th>Priority</th>
                                    <th>Severity</th>
                                    <th>Status</th>
                                    <th>Start Time</th>
                                    <th>Due Time</th>
                                    <th>Raised By</th>
                                    <th>Creator</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $index => $ticket)

                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ $ticket->title }}</td>
                                        <td>{{ $ticket->subject }}</td>
                                        <td>{{ $ticket->priority }}</td>
                                        <td>{{ $ticket->severity }}</td>
                                        <td>{{ $ticket->status }}</td>
                                        <td>{{ $ticket->start_date }}</td>
                                        <td>{{ $ticket->due_date }}</td>
                                        <td>{{ $ticket->account }}</td>
                                        <td>{{ $ticket->creator ? $ticket->creator->name : 'N/A' }}</td>
                                        <td>
                                            @if(Gate::check('ticket.read'))
                                                <a class="btn btn-sm btn-info btn-xs" href="javascript:;" data-toggle="modal" data-target="#assign_ticket_modal" data-ticket_id="{{ $ticket->id }}" > <i class="fa fa-hand-o-right"></i> Assign </a>
                                            @endif
                                        </td>
                                    </tr>

                                @endforeach
                            </tbody>
                        </table>
                        {!! $tickets->appends(request()->query())->links() !!}

                    </div>

                </div>

            </div>


            <div class="modal fade" id="assign_ticket_modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('operator.ticket.assign') }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}

                            <input type="hidden" name="ticket_id">
                            <input type="hidden" name="page" value="unassigned">

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



        </div>
    </div>


@endsection


@section('extra-script')    

    <script>
       
        $mn_list = $('.page-sidebar-inner ul.sidebar-nav > li.ticket');
        $mn_list.addClass('active open');
        $mn_list.find('.sub-menu > .ticket-unassigned').addClass('active');

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