@extends('mails.template.mail_template')

@section('greetings')
  Hello, {{ $tenant->info->firstname .' '.$tenant->info->lastname }}
@endsection

@section('title')
  CloudPBX Renewal Reminder
@endsection

@section('body')

  <div align="" class="center">
   
    <div style="text-align: justify; word-wrap: break-word; clear: both; margin: 15px;" >
      This is to inform you that your subscription for {{ strtoupper($package->name) }} package, will expire in {{ $days }} days  <br/>
    </div>

    <div  style="text-align: center; clear: both;">
      <p>If you wish to upgrade or update package info please click the update button below.</p>
    </div>
    <div class="m-15">
        <div style="font-size: 20px;"> <strong> Subscription Details </strong> </div>
    </div>
    <div class="table-responsive m-15">
      <table class="table">
        <tbody>
          <tr>
            <td><strong> Plan </strong> </td>
            <td colspan="">{{ strtoupper($subscription->package->name) }}</td>
            <td>
                <a class="btn" target="blank" href="{{ route('tenant.billing.subscription.expiring', [$tenant->domain]) }}">
                  <span> UPDATE </span>
                </a>
            </td>
          </tr>
          <tr>
            <td><strong> Amount </strong></td>
            <td colspan="2"> {{ $subscription->currency }}{{ number_format($subscription->total, 2) }}</td>
          </tr>
          <tr>
            <td><strong>Domain</strong></td>
            <td colspan="2"><a class="btn-link" target="blank" href="{{ route('tenant.dashboard', [$tenant->domain]) }}">{{ route('tenant.index', [$tenant->domain]) }}</a></td>
          </tr>
          <tr>
            <td><strong>Add-Ons</strong></td>
            <td><div> Extra Number ({{ json_decode($subscription->extra_msisdn, true)['items'] }}) </div></td>
            <td><div> {{ $subscription->currency }}{{ number_format( json_decode($subscription->extra_msisdn, true)['price'], 2) }} </div></td>
          </tr>
          <tr>
            <td><strong>Auto Rebill</strong></td>
            <td colspan="2">{{ $tenant->auto_rebill ? 'YES' : 'NO' }}</td>
          </tr>
          <tr>
            <td><strong>Expiry Date</strong></td>
            <td colspan="2">{{ $subscription->end_time->format('M d, Y') }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div  style="text-align: center; clear: both; margin-top: 25px;">
      <p>If you wish to deactive please press the link below</p>
    </div>
    <div align="center" class="button-container center" style="clear: both; margin-top: 15px;">
      
      <a class="btn" target="blank" href="{{ route('tenant.deactivate', [$tenant->domain]) }}">
        <span> DEACTIVATE MY ACCOUNT </span>
      </a>

    </div>
  </div>

@endsection

