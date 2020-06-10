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
            @foreach($operators as $index => $operator)

                <tr>
                    <td>{{$index+1}}</td>
                    <td>{{$operator->lastname}}</td>
                    <td>{{$operator->firstname}}</td>
                    <td>{{$operator->email}}</td>
                    <td>{{$operator->job_title }}</td>
                    <td>{{$operator->last_login_at ? $operator->last_login_at : "N/A" }}</td>
                    <td>
                        @if(Gate::check('user.update'))
                            <a href="{{ route('operator.user.edit', [$operator->id]) }}"> <i class="fa fa-edit"></i> </a>
                        @endif
                        @if(Gate::check('user.delete'))
                            <button class="btn btn-danger delete-user-button btn-xs" data-toggle="modal" data-target="#delete_user_modal" data-backdrop="static" data-id="{{ $operator->id }}" >Delete</button>
                        @endif
                    </td>
                </tr>

            @endforeach
        </tbody>
    </table>
</div>