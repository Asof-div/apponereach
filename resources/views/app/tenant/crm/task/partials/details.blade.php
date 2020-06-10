<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
            <form action="{{ route('tenant.crm.task.comment', [$tenant->domain])}}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                {{ csrf_field() }}

                <input type="hidden" name="task_id" value="{{ $task->id }}">
                @if ($task->status != 'Closed' || $task->status != 'Resolved')

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
                            <button class="btn btn-success btn-transparent" onclick="event.preventDefault(); addresource('resource-container');"> <i class="fa fa-plus"></i> Add Resources </button> (Size 3MB) (Max 5)
                            
                            <div id="resource-container"></div>
                        </div>
                    </div>

                @endif

                <div class="form-group clearfix">
                	<span class="pull-right">
	                    <button type="reset" class="btn btn-default" >Reset</button>
	                    <button type="submit" class="btn btn-success"> <i class="fa fa-comment"></i> Send Comment </button>
                	</span>
                </div>

            </form>
		</div>
	</div>



    <div class="col-md-12">
        
        @foreach($task->comments as $comment)
        
            <div class="media">
                <div class="media-left">
                    <a href="#">
                        <img class="media-object img-circle" src="{{ asset($comment->commentable->avatar ) }}" alt="" width="40" height="40">
                        {{ $comment->commentable->name }}
                    </a>
                </div>
                <div class="media-body">
                    <div style="border: 1px #ddd solid; padding: 15px; background-color: #f2f2f2;">
                    
                    @if($comment->sub_set) <div class="text-info" style="margin: 5px; padding: 5px 10px;  border: 2px #51BB8D solid; border-radius: 5px; border-left: #006e53 10px solid; background-color: #f2f3f3;">{{  $comment->sub_set }} </div> @endif

                        {!! nl2br($comment->comment) !!}

                        <div class="clearfix">
                            <span>
                                @foreach($comment->resources as $resource)
                                    <a class="btn btn-success btn-xs" href="{{ asset('storage/'.$resource->path)}}">{{ $resource->original_name }}</a>
                                @endforeach
                            </span>
                            <span class="pull-right"> 
                                <span>{{ $comment->created_at->diffForHumans() }}</span>
                                <button type="button" class="btn btn-xs btn-success btn-transparent"  data-toggle="modal" data-target="#reply_comment_task_modal" data-sub_set="{{ substr($comment->comment, 0, 50) ."..." }}"> <i class="fa fa-reply"></i> </button>
                            </span>
                        </div>
                    </div>
                </div>
                
            </div>

        @endforeach

    </div>


</div>


