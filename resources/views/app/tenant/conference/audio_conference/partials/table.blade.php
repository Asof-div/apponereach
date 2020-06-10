<div class="table-responsive">

    <table class="table bg-success table-hovered table-striped">
    
        <thead class="">
            <tr>
                <th>S/N</th>
                <th>Name</th>
                <th>Number</th>
                <th>Record</th>
                <th>Wait For Admin</th>
                <th>Announce Join and Leave </th>

            </tr>
        </thead>

        <tbody>
            @foreach($conferences as $index => $conference)
                <tr>
                    <td>{{$index+1}}</td>
                    <td> <a href="{{ route('tenant.conference.audio.show', [$tenant->domain, $conference->number]) }}"> {{$conference->bridge_name}} </a> </td>
                    <td> {{ $conference->number }} </td>
                    <td> {{ $conference->record ? 'YES' : 'NO' }} </td>
                    <td> {{ $conference->wait_for_admin ? 'YES' : 'NO' }} </td>
                    <td> {{ $conference->annouce_join_leave ? 'YES' : 'NO' }} </td>

                </tr>
            @endforeach
        </tbody>
    
    </table>

</div>