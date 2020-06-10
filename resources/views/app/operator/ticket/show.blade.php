@extends('layouts.operator_sidebar')

@section('title')
    
    TICKET MANAGEMENT

@endsection

@section('breadcrumb')

    <li ><a href="{{ route('operator.dashboard') }}"> Dashboard </a></li>
    <li><a href="{{ route('operator.ticket.index') }}"> Tickets </a></li>
    <li class="active"> Details : {{ $ticket->title }}</li>

@endsection

@section('content')


    <section class="">
        <div class="col-md-12 clearfix">
            <div class="m-b-20 pull-left text-left">
                <a class="btn btn-primary" href="{{ route('operator.ticket.index') }}"><i class="fa fa-list"></i> List ticket </a>
                @if(Gate::check('ticket.create'))
                    <a class="btn btn-info" href="{{ route('operator.ticket.create') }}"><i class="fa fa-plus"></i> New Ticket </a>
                @endif
            </div>
        
            <div class="m-b-20 pull-right text-right">
                <a class="btn btn-info show-sidebox-right-btn" href="javascript:;"><i class="fa fa-eye"></i> Show Ticket Info </a>
                @if ($ticket->is_default == false && Gate::check('ticket.update'))
                    <a data-toggle="modal" data-target="#edit_ticket_modal" class="btn btn-default" href=""><i class="fa fa-pencil"></i> Edit </a>
                @endif
                @if ( Gate::check('owns.ticket', $ticket) || Gate::check('admin.access') )
                    <span style="display: inline-block;">
                        <span class="dropdown">
                            <button class="btn btn-success" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-exchange"></i> Change Status
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li class="{{ strtolower($ticket->status) == 'open' ? 'bg-info text-white' : '' }}"><a href="javascript:;" data-toggle="modal" data-target='#change_status_ticket_modal' data-status='open'><i class="fa fa-folder-open-o"></i> Open </a></li>
                                <li class="{{ strtolower($ticket->status) == 'pending' ? 'bg-info text-white' : '' }}"><a href="javascript:;" data-toggle="modal" data-target='#change_status_ticket_modal' data-status='pending'><i class="fa fa-folder-open-o"></i> Pending </a></li>
                                <li class="{{ strtolower($ticket->status) == 'closed' || strtolower($ticket->status) == 'resolved' ? 'bg-success text-white' : '' }}"><a href="javascript:;" data-toggle="modal" data-target='#change_status_ticket_modal' data-status='resolved'><i class="fa fa-check"></i> Close </a></li>
                            </ul>
                        </span>
                    </span>
                @endif
                @if ($ticket->is_default == false && (Gate::check('owns.ticket', $ticket) || Gate::check('owns.incident', $ticket->incident) ) )
                    <a data-toggle="modal" data-target="#assign_ticket_modal" class="btn btn-default" href=""><i class="fa fa-hand-o-right"></i> Reassign </a>
                @endif
                @if ($ticket->is_default == false &&  (Gate::check('owns.ticket', $ticket) || Gate::check('owns.incident', $ticket->incident)) )
                    <a data-toggle="modal" data-target="#escalate_ticket_modal" class="btn btn-danger" href=""><i class="fa fa-connectdevelop"></i> Escalate </a>
                @endif
                @if ($ticket->is_default == false && Gate::check('ticket.delete'))
                    <a data-toggle="modal" data-target="#delete_ticket_modal" class="btn btn-default" }}"><i class="fa fa-trash"></i> Delete </a>
                @endif
            </div>
            
        </div>



        <div class="row">
            @include('app.operator.ticket.partials.details')
            @include('app.operator.ticket.partials.modal')
        </div>

        <div class="row sidebox-container hide">
            <div class="sidebox-content-area">

                <div class="sidebox-header"> <div class="h3 text-black" style="padding: 20px; margin: 0; position: relative; "> Ticket Details: {{ $ticket->ticket_no }}  <span class="pull-right"> <button type="button" class="btn btn-danger btn-transparent hide-sidebox-btn"> <i class="fa fa-times"></i> close </button> </span> </div> </div>
                <div class="sidebox-body clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-t2 clearfix">
                        
                        <div class="table-responsive clearfix">
                            <table class="table">
                                <tbody>

                                    <tr class="highlight">
                                        <th class="field">Title</th>
                                        <td>{{ $ticket->title }}</td>
                                        <th class="field">Customer</th>
                                        <td>{{ $ticket->customer ? $ticket->customer->name : '' }}</td>

                                    </tr>
                                    <tr>
                                        <th>Start Date</th>
                                        <td>{{ $ticket->start_date }}</td>

                                        <th>Closed Date</th>
                                        <td>{{ $ticket->closed_date }}</td>
                                    </tr>
                                    <tr class="highlight">
                                        <th class="field">Due Date</th>
                                        <td>{{ $ticket->due_date }}</td>

                                        <th class="field">ticket Type</th>
                                        <td>{{ $ticket->subject }}</td>
                                    </tr>

                                    <tr class="highlight">
                                        <th class="field">Initial Response Time</th>
                                        <td>{{ $ticket->initial_response_time }}</td>

                                        <th class="field">Expected Resolution Time</th>
                                        <td>{{ $ticket->expected_resolution_time }}</td>
                                    </tr>

                                    <tr class="highlight">
                                        <th class="field">Escalation Interval Time</th>
                                        <td>{{ $ticket->escalation_interval_time }}</td>

                                        <th class="field">Priority</th>
                                        <td>{{ $ticket->priority }}</td>
                                    </tr>

                                    <tr class="highlight">
                                        <th class="field">Severity</th>
                                        <td>{{ $ticket->severity }}</td>

                                        <th class="field">Status</th>
                                        <td>{!! $ticket->status() !!}</td>
                                    </tr>

                                    <tr class="highlight">
                                        <th class="field">Raised By</th>
                                        <td>{{ $ticket->account }}</td>

                                        <th class="field">Creator</th>
                                        <td>{{ $ticket->creator ? $ticket->creator->name : 'N/A' }}</td>
                                    </tr>
                                    <tr class="highlight">
                                        <th class="field">Assignee</th>
                                        <td>{{ $ticket->operator ? $ticket->operator->name : 'Unassigned' }}</td>

                                        <th class="field">Assigned Admin</th>
                                        <td>{{ $ticket->admin ? $ticket->admin->name : 'N/A' }}</td>
                                    </tr>

                                    

                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive">
                            <p class="f-s-15 text-info"> Body: </p>
                            <div class="f-s-15"> {!! nl2br($ticket->body) !!} </div>
                        </div>

                    </div>
                </div>

                <div class="sidebox-footer text-right" style="padding: 15px 30px;">
                    <button type="button" class="btn btn-danger btn-transparent hide-sidebox-btn"> <i class="fa fa-times"></i> close </button>
                </div>
            </div>
            
        </div>


    </section>


@endsection


@section('extra-script')    

    <script src="{{ asset('js/socket.io.js') }}"></script>
    <script src="{{ asset('js/moment/min/moment.min.js') }}"></script>

    <script>
       
        $mn_list = $('.page-sidebar-inner ul.sidebar-nav > li.ticket');
        $mn_list.addClass('active open');
        $mn_list.find('.sub-menu > .ticket-index').addClass('active');

        let room = {!! json_encode($ticket->chat_room) !!}
        let user = {!! json_encode(Auth::user()) !!}
        let notificationSound = new Audio("{{ asset('sound/notification.ogg') }}");
        notificationSound.load();
        let $chatMessages = $('.chat-messages');
        let socket_url = "{{$_SERVER['SERVER_ADDR']}}";
        if(socket_url == '::1'){
            socket_url = '127.0.0.1';
        }
        let socket = io.connect(`//${socket_url}:3000`);

        socket.on('message', function(message) {
        notificationSound.play();

        $chatMessages.append(`
          <div class="message others">
            <header>${message.username}</header>
            <article>${message.text}</article>
            <span class="time">${message.sent_time}</span>
          </div>
          `);

        scrollMessages();
        });

        function Chat(socket) {
        this.socket = socket;
        };

        Chat.prototype.joinRoom = function() {
        this.socket.emit('join', {
          user: user,
          room: room
        });
        };

        Chat.prototype.sendMessage = function(message) {
        this.socket.emit('message', {
          user: user,
          user_type: 'Operator',
          room: room,
          message: message
        });
        };

        let ChatApp = new Chat(socket);
        ChatApp.joinRoom();
        </script>


        <script type="text/javascript">
        $(function() {

            scrollMessages();

            $('#chat-input').on('keypress', function(e) {
              if (e.keyCode == 13) {
                e.preventDefault();

                let message = $(this).val().trim();
                ChatApp.sendMessage(message);

                $chatMessages.append(`
                  <div class="message">
                    <header>Me</header>
                    <article>${message}</article>
                    <span class="time">${moment(Date.now()).format('HH:mm')}</span>
                  </div>
                  `);
                
                $('#chat-input').val('');

                scrollMessages();
              }
            });

        });

        function scrollMessages() {
            $chatMessages.scrollTop($chatMessages.prop('scrollHeight'));
        }

        $('#change_status_ticket_modal').on('show.bs.modal', function(evt) {
            var button = $(evt.relatedTarget);
            let status;
            $(this).find('[name="status"]').val(button.data('status'));
            if('pending' == button.data('status')){
                status = `<span class='label label-primary'> Pending </label> `;
            }else if('open' == button.data('status')){
                status = `<span class='label label-info'> Open </label> `;
            }else if('closed' == button.data('status')){
                status = `<span class='label label-success'> Close </label> `;
            }

            $(this).find('.status').html(status);
        });

        $('body').on('click', '.preview-btn', function (event) {

            
            var content = $(this).data('content'); // Extract info from data-* attributes
            var title = $(this).data('title');

            $('.preview-title').text(title);
            $('#preview_content').attr('data', content);
            $('.preview-block').addClass('show-content').removeClass('hide');
            $('.resource-list').addClass('resource-list-size');


        });

        $('body').on('click', '.close-content', function (event) {

            $('.preview-block').removeClass('show-content').addClass('hide');
            $('.resource-list').removeClass('resource-list-size');

        });


        $('#reply_comment_ticket_modal').on('show.bs.modal', function(evt) {
            var button = $(evt.relatedTarget);
            $('[name="sub_set"]').val(button.data('sub_set'));
        });


    </script>


@endsection

@section('extra-css')

    <link rel="stylesheet" type="text/css" href="{{ asset('css/chat.css') }}">
    
@endsection