
<div class="modal fade" id="change_status_ticket_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('tenant.ticket.status', [$tenant->domain]) }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('PUT') }}

                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                <input type="hidden" name="status" value="">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"> Ticket Status </h4>
                </div>
                <div class="modal-body">
                    <div class="f-s-15">
                        Kindly, confirm you want to change ticket status to <span class="status"></span> from {!! $ticket->status() !!}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"> <i class="fa fa-exchange"></i> Yes </button>
                </div>

            </form>
        </div>
    </div>
</div>




<div class="modal fade" id="edit_ticket_modal" tabindex="-1" ticket="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog " ticket="document">
        <div class="modal-content">
            <form class="form" action="{{ route('tenant.ticket.update', [$tenant->domain, $ticket->id])}}" method="post" accept-charset="utf-8">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Ticket Update </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{csrf_field()}}
                        {{ method_field('PUT') }}

                        <div class="form-group col-md-12 col-sm-12 ">
                            <label class="form-label">Body <i class="fa fa-asterisk text-danger"></i></label>
                            <textarea name="body" class="form-control">{{ $ticket->body }}</textarea>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Submit Update</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade" id="delete_ticket_modal" tabindex="-1" ticket="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog " ticket="document">
        <div class="modal-content">
            <form class="form" action="{{ route('tenant.ticket.delete', [$tenant->domain, $ticket->id]) }}" method="post" accept-charset="utf-8">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Ticket Delete</h4>
                </div>
                <div class="modal-body">
                    {{ method_field('DELETE') }}
                    {{csrf_field()}}
                    <p class="f-s-15"> Are you sure you want to do this ? </p>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger"> <i class="fa fa-trash"></i> Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>


