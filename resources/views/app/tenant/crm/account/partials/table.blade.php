<div class="table-responsive">

    <table class="table table-hovered table-striped">
    
        <thead>
            <tr>
                <th>S/N</th>
                <th> <i class="fa fa-user"></i> Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Category</th>
                <th>Source</th>
                <th>Manager</th>
                <th>Sector</th>
                <th>Action</th>

            </tr>
        </thead>

        <tbody>
            @foreach($accounts as $index => $account)
                <tr>
                    <td>{{$index+1}}</td>
                    <td> <a href="{{ route('tenant.crm.account.show', [$tenant->domain, $account->name]) }}"> {{$account->name}} </a> </td>
                    <td> {{ $account->email }} </td>
                    <td> {{ $account->phone }} </td>
                    <td> {{ $account->category }} </td>
                    <td> {{ $account->source }} </td>
                    <td> {{ $account->manager->name }} </td>
                    <td> {{ $account->industry->label }} </td>
                    <td><button type="button" class="btn btn-warning btn-xs extension_delete" data-account="{{$account->id}}" > DELETE </button></td>
                </tr>
            @endforeach
        </tbody>
    
    </table>

</div>