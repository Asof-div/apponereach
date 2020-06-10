@extends('mails.template.mail_template')

@section('greetings')
  You have been invited to a meeting.
@endsection

@section('title')
  Subject: {{ $meeting->subject }}
@endsection

@section('body')

  <div align="" class="center">
    <div>
        <div class="m-15">
          <div style="font-size: 20px;"> <strong> Conference Call </strong> </div>
        </div>
    </div>
    <div class="table-responsive m-15">
      <table class="table">
        <tbody>
          <tr>
            <td><strong> Local Dial-in </strong> </td>
            <td colspan="">{{ $meeting->conference->call_flow->dial_string }}</td>
          </tr>
          <tr>
            <td><strong> Access Code </strong> </td>
            <td colspan="">{{ $meeting->conference->guest_pin }}</td>
          </tr>
          <tr>
            <td><strong> Dial-in </strong> </td>
            <td colspan=""> 07001237001 </td>
          </tr>
          <tr>
            <td><strong> Time </strong> </td>
            <td colspan="">{{ $meeting->start_date .' '. $meeting->start_time }}</td>
          </tr>
          <tr>
            <td><strong> Duration </strong> </td>
            <td colspan="">{{ strtoupper($meeting->duration) }}</td>
          </tr>

        </tbody>
      </table>
    </div>


    <div>
        <div style="font-size: 20px;"> <strong> Video Conference </strong> </div>
    </div>
    <div class="table-responsive m-15">
      <table class="table">
        <tbody>
          <tr>
            <td><strong> Meeting Id </strong> </td>
            <td colspan="">{{ $meeting->meeting_room_id }}</td>
          </tr>
          <tr>
            <td><strong> Meeting Link </strong> </td>
            <td colspan=""> <a href="https://sip.techmadeeazy.com/vc"> https://sip.techmadeeazy.com/vc </a> </td>
          </tr>

        </tbody>
      </table>
    </div>

    <div class="table-responsive m-15" style="text-align: justify;">

      {!! nl2br($meeting->description) !!} <br/>

    </div>

    <div class="m-15">
        <strong> Instructions: </strong>

        At the scheduled date and time, dial in to the conference. When prompted, enter the access code followed by pound or hash (#).

        To join the video and screen sharing session, click the online meeting link.

    </div>

  </div>

@endsection

