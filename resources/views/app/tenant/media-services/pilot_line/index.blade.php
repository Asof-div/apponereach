@extends('layouts.tenant_sidebar')

@section('title')
    
    PILOT NUMBER ({{ $pilot_lines->count() }})

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.media-service.index', [$tenant->domain]) }}"> Media Services </a></li>
    <li class="active"> Pilot Numbers </li>

@endsection

@section('content')
    
    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel panel-default">
            <div class="panel-body" style="min-height: 400px;">

                <hr class="horizonal-line-thick">

                <div class="col-md-12 col-sm-12 col-xs-12">

                    <div class="table-responsive">
                        
                        <table class="table  ">
                            
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Number</th>
                                    <th>Line Status</th>
                                    <th>Call Recording</th>
                                    <th>Voicemail</th>
                                    <th>Auto Attendant Status</th>
                                    <th>Caller ID Name </th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($pilot_lines as $index => $pilot_line)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td>{{$pilot_line->number}}</td>
                                        <td>{{$pilot_line->status ? "ON" : "OFF" }}</td>
                                        <td>{{$pilot_line->record ? "YES" : "NO"}}</td>
                                        <td>{{$pilot_line->voicemail ? "YES" : "NO"}}</td>
                                        <td>{{$pilot_line->configured ? "YES" : "NO"}}</td>
                                        <td>{{$pilot_line->caller_id_name }}</td>
                                        <td>
                                            <a href="{{ route('tenant.media-service.pilot-line.edit', [$tenant->domain, $pilot_line->number] )}}" class="btn btn-xs btn-primary"> <i class="fa fa-edit"></i> EDIT </a> 
                                            <a href="{{ route('tenant.media-service.pilot-line.config', [$tenant->domain, $pilot_line->number] )}}" class="btn btn-xs btn-primary"> <i class="fa fa-sitemap"></i>  Routing </a>  
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                         
                        </table>

                    </div>


                </div>

            </div>
        </div>
    </div>
        
@endsection


@section('extra-script')
    <script type="text/javascript" src="{{asset('js/domain_setting.js')}}"></script>

    <script type="text/javascript">
        $mn_list = $('.sidebar ul.nav > li.nav-media-services');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-pilot-line').addClass('active');

    </script>


@endsection


@section('extra-css')


@endsection