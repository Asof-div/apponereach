@extends('layouts.tenant_sidebar')

@section('title')
    
    CALL SUMMARY 

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.media-service.index', [$tenant->domain]) }}"> Call Summary </a></li>
    <li class="active"> Call Detail </li>

@endsection

@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel panel-default">
            
            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table f-s-15">
                            <tbody>
                                <tr>
                                    <th>Call Status </th>
                                    <td>{!! $cdr->status() !!}</td>
                                </tr>
                                <tr>
                                    <th>Direction </th>
                                    <td>{!! $cdr->direction() !!}</td>
                                </tr>
                                <tr>
                                    <th>Caller Number </th>
                                    <td>{{ $cdr->caller_id_num }}</td>
                                </tr>
                                <tr>
                                    <th>Receiver </th>
                                    <td>{{ $cdr->callee_id_num }}</td>
                                </tr>
                                <tr>
                                    <th>Source Channel</th>
                                    <td>{{ $cdr->source }}</td>
                                </tr>
                                <tr>
                                    <th>Duration </th>
                                    <td>{{ $cdr->duration() }}</td>
                                </tr>
                                <tr>
                                    <th>Music On Hold </th>
                                    <td>{{ $cdr->play_media_name }}</td>
                                </tr>
                                <tr>
                                    <th>Destination Type</th>
                                    <td>{{ $cdr->destination_type }}</td>
                                </tr>
                                <tr>
                                    <th>Destination </th>
                                    <td>{{ $cdr->destination }}</td>
                                </tr>
                                <tr>
                                    <th>Start Time </th>
                                    <td>{{ $cdr->start_timestamp }}</td>
                                </tr>
                                <tr>
                                    <th>End Time </th>
                                    <td>{{ $cdr->end_timestamp }}</td>
                                </tr>

                                @if (!is_null($filesize) && $filesize > 43)
                                    <tr>
                                        <th> Call Record </th>
                                        <td>  <audio controls>  <source src="{{asset('storage//'.$cdr->call_recording)}}" type="audio/wav"> Your browser does not support the audio tag.</audio></td>
                                    </tr>
                                @endif

                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection


@section('extra-script')

    <script type="text/javascript">

        $mn_list = $('.sidebar ul.nav > li.nav-media-services');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-summary').addClass('active');


        var _token = "{{csrf_token()}}";

    </script>


@endsection

@section('extra-css')

    <style>
        
   
        

    </style>

@endsection