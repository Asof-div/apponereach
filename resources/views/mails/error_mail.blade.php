@extends('mails.template.mail_template')

@section('greetings')
  Hello, 
@endsection

@section('title')

@endsection

@section('body')

  <div align="" class="center">
   

    <div class="table-responsive m-15">
      <table class="table">
        <tbody>
          <tr>
            <td>{{ $exception }}</td>

          </tr>

        </tbody>
      </table>
    </div>

  </div>

@endsection

