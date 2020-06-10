@extends('mails.template.mail_template')

@section('greetings')
  Hello, {{ $tenant->info->firstname .' '.$tenant->info->lastname }}
@endsection

@section('title')
  {{ $title }}
@endsection

@section('body')

  <div align="" class="center">
   
    <div style="text-align: justify; word-wrap: break-word; clear: both; margin: 15px;" >
      This is to inform you that your subscription for {{ strtoupper($package->name) }}.
    </div>


    <div class="m-15">
        <div style="font-size: 20px;"> <strong> Package Details </strong> </div>
    </div>
    <div class="table-responsive m-15">
      <table class="table">
        <tbody>
          <tr>
            <td><strong> Package </strong> </td>
            <td>{{ strtoupper($package->name) }}</td>
          </tr>
          <tr>
            <td><strong> Billing Cycle </strong></td>
            <td>{{ $tenant->billing_cycle }}</td>
            <td>{{ $subscription->currency }} {{ number_format($package->price, 2) }}</td>
          </tr>
          <tr>
            <td><strong>Domain</strong></td>
            <td colspan="2"><a class="btn-link" target="blank" href="{{ route('tenant.dashboard', [$tenant->domain]) }}">{{ route('tenant.index', [$tenant->domain]) }}</a></td>
          </tr>
          <tr>
            <td><strong>Add-Ons</strong></td>
            <td><div> Extra Number ({{ $subscription->extra_msisdn }}) </div></td>
            <td><div>{{ $subscription->currency }}  {{ number_format(500 * (int)$tenant->extra_number, 2) }} </div></td>
          </tr>
          <tr>
            <td><strong>Auto Rebill</strong></td>
            <td>{{ $tenant->auto_rebill ? 'YES' : 'NO' }}</td>
          </tr>
          <tr>
            <td><strong>Start Date</strong></td>
            <td colspan="2">{{ $subscription->start_time->format('M d, Y') }}</td>
          </tr>
          <tr>
            <td><strong>Expiry Date</strong></td>
            <td colspan="2">{{ $subscription->end_time->format('M d, Y') }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div  style="text-align: center; clear: both; margin-top: 25px;">
      <p> press the link below</p>
    </div>
    <div align="center" class="button-container center" style="clear: both; margin-top: 15px;">
      
      <a class="btn" target="blank" href="{{ route('tenant.dashboard', [$tenant->domain]) }}">
        <span> LOGIN </span>
      </a>

    </div>
  </div>

@endsection

