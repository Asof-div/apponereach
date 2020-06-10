@extends('mails.template.mail_template')

@section('greetings')
  Hello {{ $ticket->operator->lastname }},
@endsection

@section('title')
  Subject: {{ $ticket->title }} 
@endsection

@section('body')

  <div align="" class="center">
    <div class="table-responsive m-15" style="text-align: justify;">

      You have been assigned a ticket by {{ $operator }} <br/>

      <hr class="horizonal-line" />
    
      {!! nl2br($ticket->body) !!} <br/>
    
    </div>

    <div>
        <div class="m-15">
        <div style="font-size: 20px;"> <strong> Other Ticket Details </strong> </div>
    </div>
    <div class="table-responsive m-15">
      <table class="table">
        <tbody>
          @if($ticket->customer)
          <tr>
            <td><strong> Customer </strong> </td>
            <td colspan="">{{ $ticket->customer->name }}</td>
          </tr>
          @endif
          <tr>
            <td><strong> Incident Type </strong> </td>
            <td colspan="">{{ $ticket->incident->name }}</td>
          </tr>
          <tr>
            <td><strong> Status </strong> </td>
            <td colspan="">{{ strtoupper($ticket->status) }}</td>
          </tr>
          <tr>
            <td><strong> Prority </strong> </td>
            <td colspan="">{{ strtoupper($ticket->priority) }}</td>
          </tr>
          <tr>
            <td><strong> Severity </strong> </td>
            <td colspan="">{{ $ticket->severity }}</td>
          </tr>
          <tr>
            <td><strong> Created Time </strong> </td>
            <td colspan="">{{ $ticket->created_at->format('Y-m-d H:i:s') }}</td>
          </tr>
          <tr>
            <td><strong> Initial Response Time </strong> </td>
            <td colspan="">{{ $ticket->initial_response_time }}</td>
          </tr>
          <tr>
            <td><strong> Expected Resolution Time </strong> </td>
            <td colspan="">{{ $ticket->expected_resolution_time }}</td>
          </tr>
          <tr>
            <td><strong> Escalation Interval Time </strong> </td>
            <td colspan="">{{ $ticket->escalation_interval_time }}</td>
          </tr>


        </tbody>
      </table>
    </div>


    <div align="center" class="button-container center" style="clear: both; margin-top: 15px;">
      
      <a class="btn" target="blank" href="{{ route('operator.ticket.show', [$ticket->id]) }}">
        <span>Click Here To View Ticket </span>
      </a>

    </div>
  </div>

@endsection

