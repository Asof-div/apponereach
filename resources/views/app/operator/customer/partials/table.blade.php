<div class="col-md-12 clearfix">
    
</div>

<div class="col-md-12">
    <div class="table-responsive bg-white p-10 clearfix">
        <table class="table table-condensed table-hover table-striped ">
            <thead>
                <tr>

                    <th>S/N</th>
                    <th>Name</th>
                    <th>Customer No.</th>
                    <th>Status</th>
                    <th>Customer Type</th>
                    <th>Package</th>
                    <th>Billing Cycle</th>
                    <th>VALIDITY</th>
                    <th>Action</th>

                </tr>
            </thead>
            <tbody>
                @foreach($customers as $index => $customer)

                    <tr>
                        <td>{{$index+1}}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->tenant_no }}</td>
                        <th> <span>{{ $customer->activation }} </span></th>
                        <td>{{ strtoupper($customer->billing_method) }}</td>
                        <td>{{ $customer->package? $customer->package->name : '' }}</td>
                        <td>{{ $customer->billing_cycle }}</td>
                        <th><span class=" text-success"> {{ $customer->validity  }} </span></th>
                        <td><a href="{{ route('operator.customer.show', [$customer->id]) }}" class="btn-link">Details</a></td>
                    </tr>

                @endforeach
            </tbody>
        </table>
    </div>

</div>