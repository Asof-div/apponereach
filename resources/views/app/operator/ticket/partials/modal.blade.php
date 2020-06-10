
<div class="modal fade" id="assign_ticket_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('operator.ticket.assign') }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('PUT') }}

                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"> Ticket Assign </h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>User</label>
                        <select class="form-control" name="user_id">
                            <option value="">Select User </option>
                            @foreach($operators as $operator)
                                <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"> <i class="fa fa-hand-o-right"></i> Assign Ticket</button>
                </div>

            </form>
        </div>
    </div>
</div>



<div class="modal fade" id="change_status_ticket_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('operator.ticket.status') }}" method="POST">
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


<div class="modal fade" id="add_comment_ticket_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('operator.ticket.comment', [$ticket->id])}}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                {{ csrf_field() }}

                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"> Share Internal Comemnt With Administrator </h4>
                </div>
                <div class="modal-body clearfix">
                    @if ($ticket->status != 'Closed' || $ticket->status != 'Resolved')
    
                        <div class="col-md-12 form-group clearfix">
                            <div class="col-md-2">
                                <label>Comment</label>
                            </div>
                            <div class="col-md-10">
                                <textarea name="comment" class="summernote_editor form-control" rows="5"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12 form-group clearfix">
                            <div class="col-md-2">
                                <label>Attachments</label>
                            </div>
                            <div class="col-md-10 clearfix">
                                <button class="btn btn-success btn-transparent" onclick="event.preventDefault(); addresource('resource-container-modal');"> <i class="fa fa-plus"></i> Add Resources </button> (Size 3MB) (Max 5)
                                
                                <div id="resource-container-modal"></div>
                            </div>
                        </div>

                    @endif

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"> <i class="fa fa-comment"></i> Send Comment </button>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="reply_comment_ticket_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('operator.ticket.comment', [$ticket->id])}}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                {{ csrf_field() }}

                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                <input type="hidden" name="sub_set" value="">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"> Reply on a comment </h4>
                </div>
                <div class="modal-body clearfix">
                    @if ($ticket->status != 'Closed' || $ticket->status != 'Resolved')
    
                        <div class="col-md-12 form-group clearfix">
                            <div class="col-md-2">
                                <label>Comment</label>
                            </div>
                            <div class="col-md-10">
                                <textarea name="comment" class="summernote_editor form-control" rows="5"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12 form-group clearfix">
                            <div class="col-md-2">
                                <label>Attachments</label>
                            </div>
                            <div class="col-md-10 clearfix">
                                <button class="btn btn-success btn-transparent" onclick="event.preventDefault(); addresource('resource-container-modal');"> <i class="fa fa-plus"></i> Add Resources </button> (Size 3MB) (Max 5)
                                
                                <div id="resource-container-modal"></div>
                            </div>
                        </div>

                    @endif

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"> <i class="fa fa-comment"></i> Send Comment </button>
                </div>

            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="escalate_ticket_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('operator.ticket.escalate', [$ticket->id]) }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('PUT') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"> Ticket Escalation </h4>
                </div>
                <div class="modal-body">
                    <div class="f-s-15">
                        Kindly, confirm you want to escalate ticket an ADMINISTRATOR.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"> <i class="fa fa-connectdevelop"></i> Yes </button>
                </div>

            </form>
        </div>
    </div>
</div>



<div class="modal fade" id="edit_ticket_modal" tabindex="-1" ticket="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog " ticket="document">
        <div class="modal-content">
            <form class="form" action="{{ route('operator.ticket.update', [$ticket->id])}}" method="post" accept-charset="utf-8">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Ticket Update </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{csrf_field()}}
                        {{ method_field('PUT') }}

                        <div class="form-group col-md-12 col-sm-12 ">
                            <label class="form-label">Incident Type <i class="fa fa-asterisk text-danger"></i></label>
                            <select class="form-control" name="incident" >
                                @foreach($incidents as $incident)  
                                    <option {{$ticket->incident_id== $incident->id ? 'selected':'' }} value="{{ $incident->id }}"> {{ $incident->label }} </option>
                                @endforeach
                            </select>

                        </div>


                        {{-- <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class='input-group date datepicker'>
                                    <input type='text' name="due_date" class="form-control" value="{{(new \Datetime($ticket->due_date))->format('Y-m-d h:i')}}" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                         --}}
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
            <form class="form" action="{{ route('operator.ticket.delete', [$ticket->id]) }}" method="post" accept-charset="utf-8">
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


