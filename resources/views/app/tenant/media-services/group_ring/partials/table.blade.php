<div class="table-responsive">

    <table class="table table-hover table-striped " id="default_attempt_table">
    
        <thead>
            <tr>
                <th> S/N </th>
                <th>Name</th>
                <th>Exten</th>
                <th>Method</th>
                <th>Members</th>
            </tr>
        </thead>
        <tbody>
            @foreach($group_rings as $index => $group)
                <tr>
                    <td>#{{$index+1}}</td>
                    <td> <a href="{{ route('tenant.media-service.group-ring.show', [$tenant->domain, $group->number] ) }}" >{{ $group->name}} </a> </td>
                    <td>{{ $group->number }}</td>
                    <td>{{ $group->method }}</td>
                    <td> <span class="f-s-14"> {{ $group->numbers }} </span> </td>
                </tr>
            @endforeach
        </tbody>
        

    </table>

</div>    