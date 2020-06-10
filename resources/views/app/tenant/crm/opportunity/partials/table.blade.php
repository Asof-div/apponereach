<div class="table-responsive">

    <table class="table table-hovered table-striped">
    
        <thead>
            <tr>
                <th>S/N</th>
                <th>Title</th>
                <th>Account</th>
                <th>Stage</th>
                <th>Recurrent</th>
                <th>Attention</th>
                <th>Probability</th>
                <th>Source </th>
                <th>Competitor </th>
                <th>Close Date</th>
                <th>Worth</th>
                <th>Manager</th>
                <th>Action</th>

            </tr>
        </thead>

        <tbody>
            @foreach($opportunities as $index => $opportunity)
                <tr>
                    <td>{{$index+1}}</td>
                    <td> <a href="{{ route('tenant.crm.opportunity.show', [$tenant->domain, $opportunity->id]) }}"> {{$opportunity->title}} </a> </td>
                    <td> <a href="{{ route('tenant.crm.account.show', [$tenant->domain, $opportunity->account->name]) }}"> {{$opportunity->account->name}} </a> </td>
                    <td> {{ $opportunity->stage }} </td>
                    <td> {{ $opportunity->isRecurrent ? 'YES' : 'NO' }} </td>
                    <td> {{ $opportunity->attention }} </td>
                    <td> {{ $opportunity->probability}} % </td>
                    <td> {{ $opportunity->source }} </td>
                    <td> {{ $opportunity->competitor ? $opportunity->competitor->name : 'None' }} </td>
                    <td> {{ $opportunity->close_date ? $opportunity->close_date->format('Y-m-d') : '' }} </td>
                    <td> {{ $opportunity->currency ? $opportunity->currency->symbol ." ". $opportunity->worth : '' }} </td>
                    <td> {{ $opportunity->manager ? $opportunity->manager->name : '' }} </td>
                    <td><button type="button" class="btn btn-warning btn-xs extension_delete" data-opportunity="{{$opportunity->id}}" > DELETE </button></td>
                </tr>
            @endforeach
        </tbody>
    
    </table>

</div>