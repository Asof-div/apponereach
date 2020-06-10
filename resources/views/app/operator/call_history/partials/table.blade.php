<div class="col-md-12 clearfix">

</div>

<div class="col-md-12">
    <div class="table-responsive bg-white p-10 clearfix">
        <table class="table table-condensed table-hover table-striped">
            <thead>
                <tr>

                    <th>S/N</th>
                    <th>phonecode</th>
                    <th>Country</th>
                    <th>Rate</th>
                    <th>Action</th>

                </tr>
            </thead>
            <tbody>
                @foreach($calls as $index => $call)

                    <tr>
                        <td>{{$index+1}}</td>
                        <td>{{ $call->phonecode }}</td>
                        <td>{{ $call->country ? $call->country->iso3 : '' }}</td>
                        <td>{{ $call->call }}</td>
                        <td>
                            @if(Gate::check('user.delete'))
                                <button class="btn btn-info btn-xs" data-toggle="modal" data-target="#detail_call_history_modal" data-backdrop="static" data-id="{{ $call->id }}" >Details</button>
                            @endif
                        </td>
                    </tr>

                @endforeach
            </tbody>
        </table>

        {!! $calls->links() !!}
    </div>

</div>