<div class="table-responsive bg-white p-10">
    <table class="table table-condensed table-hover table-striped">
        <thead>
            <tr>

                <th>S/N</th>
                <th>LastName</th>
                <th>FirstName</th>
                <th>Email</th>
                <th>Role</th>
                <th>Last Login</th>
                <th>Action</th>

            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)

                <tr>
                    <td>{{$index+1}}</td>
                    <td>{{$user->lastname}}</td>
                    <td>{{$user->firstname}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->profile->role }}</td>
                    <td>{{$user->last_login_at ? $user->last_login_at : "N/A" }}</td>
                    <td>
                        <a class="btn btn-sm btn-primary btn-xs" href="{{ route('tenant.account.user.edit', [$tenant->domain, $user->id]) }}" > <i class="fa fa-edit"></i> </a>

                    @if(!$user->manager)
                        <button class="btn btn-warning delete-user-button btn-xs" data-user-id="{{$user->id}}" >Delete</button>
                    @endif
                    </td>
                </tr>

            @endforeach
        </tbody>
    </table>
</div>