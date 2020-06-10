@extends('mails.template.mail_template')

@section('greetings')
  Hello {{ $ticket->creator ? $ticket->creator->lastname : '' }},
@endsection

@section('title')
  Subject: {{ $ticket->title }} 
@endsection

@section('body')

  <div align="" class="center">
    <div class="table-responsive m-15" style="text-align: justify;">

      {{ $operator }} have just changed your ticket status to {!! $status['new'] !!} from {!! $status['old'] !!} <br/>

      <hr class="horizonal-line" />
    
      @if( strtolower($ticket->status) != 'resolved')
        {!! nl2br($ticket->body) !!} <br/>
      @else


      <div class="chat-box">
          <div style="font-size: 16px;">
              Chat History
          </div>
          <hr class="horizonal-line" />

          <div class="chat-body" style="font-size: 14px;">
                  @foreach($ticket->chat_room->conversations as $conversation)
                    <div class="message{{ $conversation->sender_type != 'App\Models\User' ? ' others' : '' }}">
                      <header> {{ $conversation->created_at->format('H:i') }}  <span class="name"> {{ $conversation->sender ? $conversation->sender->name :' Unknown'  }} </span> :</header>
                      <article>{{ $conversation->message }}</article>
                    </div>
                  @endforeach
                
                </div>
            </div>
          </div>

      </div>

      @endif
    
    </div>


    <div align="center" class="button-container center" style="clear: both; margin-top: 15px;">
      
      <a class="btn" target="blank" href="{{ route('operator.ticket.show', [$ticket->id]) }}">
        <span>Click Here To View Ticket </span>
      </a>

    </div>
  </div>

@endsection


@section('extra-css')

  <style type="text/css">
    .others header span.name{
      color:#D44444;
    }
    .message header span.name{
      color:#4466B3;
    }


  </style>
@endsection