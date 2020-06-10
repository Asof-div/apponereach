@extends('layouts.tenant')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            
            <h2>Welcome To {{$tenant->name}}</h2>

            <div class="col-md-12 col-sm-12 col-xs-12">

                <div class="col-md-6">
                    <div class="panel">
                        <div class="panel-heading panel-default">
                            Contact Detail
                        </div>
                        <div class="panel-body">
                            <div> <label> Email  -  </label> {{ $tenant->info->email ? $tenant->info->email : 'N/A'  }} </div>
                            <div> <label> Telphone  -  </label> {{ $tenant->info->telephone ? $tenant->info->telephone : 'N/A'  }} </div>
                            
                            
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel">
                        <div class="panel-heading panel-default">
                            Address
                        </div>
                        <div class="panel-body">
                            
                            {!!  $tenant->info->address? nl2br($tenant->info->address) : 'N/A' !!}
                            
                        </div>
                    </div>
                </div>

            
        </div>
    </div>
</div>
@endsection
