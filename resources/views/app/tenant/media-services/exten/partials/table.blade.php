<div class="table-responsive">

    <table class="table table-hovered table-striped">
    
        <thead>
            <tr>
                <th>S/N</th>
                <th> <i class="fa fa-user"></i> User</th>
                <th>Caller ID</th>
                <th>Number</th>
                <th>SIP Username</th>
                <th>Voicemail </th>
                <th>Device</th>
                {{-- <th>Action</th> --}}

            </tr>
        </thead>

        <tbody>
            @foreach($extens as $index => $exten)
                <tr>
                    <td>{{$index+1}}</td>
                    <td>{{$exten->user->name}}</td>
                    <td>{{$exten->name}}</td>
                    <td> <a href="{{ route('tenant.media-service.exten.show', [$tenant->domain, $exten->number]) }}"> {{$exten->number}} </a> </td>
                    <td>{{$exten->exten_reg }}</td>
                    <td>{{$exten->voicemail?'Yes':'NO'}}</td>
                    <td>{{$exten->device? 'Cisco':'Unknown'}}</td>
                    {{-- <td><button type="button" class="btn btn-warning btn-xs extension_delete" data-exten="{{$exten->id}}" > DELETE </button></td> --}}
                </tr>
            @endforeach
        </tbody>
    
    </table>

</div>