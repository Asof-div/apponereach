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
                @foreach($rates as $index => $rate)

                    <tr>
                        <td>{{$index+1}}</td>
                        <td>{{ $rate->phonecode }}</td>
                        <td>{{ $rate->country ? $rate->country->iso3 : '' }}</td>
                        <td>{{ $rate->rate }}</td>
                        <td>
                            @if(Gate::check('user.delete'))
                                <a href="{{ route('operator.call.rate.edit', [$rate->id]) }}" class="btn btn-info btn-xs" >Edit</a>
                                <button class="btn btn-danger delete-pilot-button btn-xs" data-toggle="modal" data-target="#delete_call_rate_modal" data-backdrop="static" data-id="{{ $rate->id }}" >Delete</button>
                            @endif
                        </td>
                    </tr>

                @endforeach
            </tbody>
        </table>

        {!! $rates->links() !!}
    </div>

</div>