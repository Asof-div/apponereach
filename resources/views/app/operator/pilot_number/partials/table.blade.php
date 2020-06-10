<div class="col-md-12 clearfix">
    
</div>

<div class="col-md-12">
    <div class="table-responsive bg-white p-10 clearfix">
        <table class="table table-condensed table-hover table-striped">
            <thead>
                <tr>

                    <th>S/N</th>
                    <th>Number</th>
                    <th>Serial No</th>
                    <th>Type</th>
                    <th>Source</th>
                    <th>Batch</th>
                    <th>Release Time</th>
                    <th>Provisioning</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Action</th>

                </tr>
            </thead>
            <tbody>
                @foreach($pilot_numbers as $index => $number)

                    <tr>
                        <td>{{$index+1}}</td>
                        <td>{{ $number->number }}</td>
                        <td>{{ $number->serial_no }}</td>
                        <td>{{ $number->type }}</td>
                        <td>{{ $number->source }}</td>
                        <td>{{ $number->batch }}</td>
                        <td>{{ $number->release_time }}</td>
                        <td>{{ $number->provisioning }}</td>
                        <td>
                            {{-- @if($number->billing)
                                
                                <a href="{{ route('operator.customer.show', [$number->billing->tenant_id]) }}">
                                    {{ $number->billing->tenant ? $number->billing->tenant->name : '' }}
                                </a>
                            @endif --}}
                        </td>
                        <th>{{ ucwords($number->status) }}</th>
                        <td>
                            @if(Gate::check('user.delete'))
                                <button class="btn btn-danger delete-pilot-button btn-xs" data-toggle="modal" data-target="#delete_pilot_number_modal" data-backdrop="static" data-id="{{ $number->id }}" >Delete</button>
                            @endif
                        </td>
                    </tr>

                @endforeach
            </tbody>
        </table>

        {!! $pilot_numbers->links() !!}
    </div>

</div>