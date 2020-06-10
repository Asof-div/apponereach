<table>
    <thead>
    <tr>
        <th>MSISDN</th>
        <th>Serial No</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($pilot_numbers as $pilot_number)
        <tr>
            <td>{{ $pilot_number->number }}</td>
            <td>{{ $pilot_number->serial_no }}</td>
            <td>{{ ucfirst($pilot_number->status) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>