@extends('layouts.tenant_sidebar')

@section('title')
    Chat
@endsection


@section('content')

    <div class="col-md-12 p-0">
        <div class="col-md-3 col-sm-4 col-xs-12 p-0">
            <div class="chat-box">
                <div class="chat-heading pane-theme">
                    <span class="pull-right">
                        <a href="javascript:;" data-toggle='modal' data-target=".create-chat-modal"> <i class="fa fa-edit fa-2x text-white"></i> </a>
                    </span>
                </div>
                <div class="chat-body">                 

                    <div class="col-md-12 no-p">
                        <ul class="chat-list">
                            @if ($rooms->count() > 0)
                                @foreach ($rooms as $room)
                                    <li class="clearfix">
                                    <div class="image clearfix">
                                        <a href="javascript:;" class="img-responsive">
                                        <div class="chat-cont">
                                            <img class="img-circle" src="{{ asset($room->avatar) }}" height="40" alt="">
                                        </div>
                                        <div class="chat-cont">
                                            <span class="chat-name"> {{ $room->name }} </span> <br>
                                            <span class="last-chat"> last updaye</span>
                                        </div>
                                        <div class="chat-cont">
                                            <span class="chat-update pull-right"> time </span>
                                        </div>
                                        </a>
                                    </div>
                                    </li>
                                @endforeach
                            @else
                                <li><p class="text-center">You have not created any room</p></li>
                            @endif
                        </ul>
                    </div>

                </div>

            </div>  
        </div>
        <div class="col-md-9 col-sm-8 col-xs-12 p-0">
            @include('app.tenant.chat.partials.chat')
        </div>
    </div>

    <div class="modal create-chat-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    Contact
                </div>
                <div class="modal-body clearfix">
                    <div class="table-responsive">
                        @foreach($users->where('id', '<>', Auth::id()) as $user)

                            <div class="media">
                                <div class="media-left">
                                    <a href="#">
                                        <img class="media-object img-circle" src="{{ asset($user->avatar ) }}" alt="" width="50" height="50">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <div style="border: 1px #ddd solid; padding: 15px; background-color: #f2f2f2;">
                                    
                                            <a class=""> 
                                                <span>{{ $user->name }}</span>
                                            </a>
                                    
                                    </div>
                                </div>
                                
                            </div>


                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extra-script')    

    <script src="{{ asset('js/socket.io.js') }}"></script>
    <script src="{{ asset('js/moment/min/moment.min.js') }}"></script>

    <script>
       
        $mn_list = $('.sidebar ul.nav > li.nav-chat');
        $mn_list.addClass('active');
        
        let room = null;
        let user = {!! json_encode(Auth::user()) !!}
        let notificationSound = new Audio("{{ asset('sound/notification.ogg') }}");
        notificationSound.load();
        let $chatMessages = $('.chat-messages');

        let socket = io.connect('//{{$_SERVER['SERVER_ADDR']}}:3000');

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

    </script>


@endsection

@section('extra-css')

    <link rel="stylesheet" type="text/css" href="{{ asset('css/chat.css') }}">
    <style type="text/css">
        ul.chat-list{
            display: block;
            list-style-type: none;
            padding: 0px;
        }
        ul.chat-list li{
            height: 50px;
            color: black;
        }   
        span.chat-name{
            font-size: 16px;
            color: black;
        }
        span.last-chat{
            color: #ddd;
        }
        span.last-update{
            color: #000;
        }
        div.chat-cont{
            display: inline-block;
        }

    </style>

@endsection