<div class="modal fade" id="auto_attendant_modal" tabindex="-1" role="dialog" aria-labelledby="transfer_routeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" name="13" method="post" id="auto_attendant_reorder_form">
            <div class="modal-header">
            <span class="h5 modal-title"> Rearrange List </span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                {{ csrf_field() }}
                <div class="col-md-12 f-s-15">
                    Drag and drop to rearrange in order of priority. 
                </div>
                <div class="row" style="padding: 15px 25px;">

                    <table class="table f-s-15">
                        <thead>
                            <tr>
                                <th>Tag</th>
                                <th>Type</th>
                                <th>Time Active</th>
                                <th>Days Active</th>
                                <th>Date Active</th>
                            </tr>
                        </thead>
                        <tbody class="reorder-list">
                            @foreach($pilot_line->auto_attendants as $attendant)
                                <tr>
                                    <td>{{ $attendant->title }} <input type="hidden" name="order[]" value="{{ $attendant->id }}"></td>
                                    <td>{{ ucfirst($attendant->period) }}</td>
                                    <td>{{ $attendant->start_time .' - '. $attendant->end_time }}</td>
                                    <td>{{ $attendant->days_of_week }}</td>
                                    <td>{{ $attendant->start_mon .' '. $attendant->start_day }} {{ $attendant->end_day ? ' to '. $attendant->end_day :  '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>

            </div>
            <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Auto Attendant</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>




