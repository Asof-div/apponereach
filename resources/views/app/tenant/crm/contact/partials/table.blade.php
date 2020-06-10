<div class="table-responsive">

    <table class="table table-hovered table-striped">
    
        <thead>
            <tr>
                <th>S/N</th>
                <th>Name</th>
                <th>Account</th>
                <th>Job Title</th>
                <th>Number</th>
                <th>Email</th>
                <th></th>

            </tr>
        </thead>

        <tbody>
            @foreach($contacts as $index => $contact)
                <tr>
                    <td>{{$index+1}}</td>
                    <td> <a href="{{ route('tenant.crm.contact.show', [$tenant->domain, $contact->id]) }}"> {{$contact->name}} </a> </td>
                    <td> {{ $contact->account->name }} </td>
                    <td> {{ $contact->title }} </td>
                    <td> {{ $contact->number }} </td>
                    <td> {{ $contact->email }} </td>
                    <td><button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target=".delete-contact-modal" data-contact_id="{{$contact->id}}" > DELETE </button></td>
                </tr>
            @endforeach
        </tbody>
    
    </table>

    {{ $contacts->links() }}
</div>