<div class="col-md-12">
   <ul class="nav nav-tabs nav-theme ">
        <li class="active"><a href="#nav-activities" data-toggle="tab"> Ticket Activities </a></li>
        <li><a href="#nav-chat" data-toggle="tab"> Chat </a></li>
        <li><a href="#nav-operators" data-toggle="tab"> Operators </a></li>
        <li><a href="#nav-resources" data-toggle="tab"> Resources </a></li>
    </ul>
    <div class="tab-content bg-silver">
        <div class="tab-pane fade clearfix active in" id="nav-activities">                 
            <div class="table-responsive">
                <table class="table">
                	<tr>
                		<th>BODY</th>
                		<td>{!! nl2br($ticket->body) !!}</td>
                        @if ($ticket->status != 'Closed' || $ticket->status != 'Resolved')
                            <td><a href="javascript:;" class="btn btn-info" data-toggle="modal" data-target="#add_comment_ticket_modal"> <i class="fa fa-comment"></i> Add Comment </a></td>
                        @endif
                	</tr>
                </table>
            </div>
            <div class="col-md-12">
            	
                @foreach($ticket->comments as $comment)
                
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
                                        <button type="button" class="btn btn-xs btn-success btn-transparent"  data-toggle="modal" data-target="#reply_comment_ticket_modal" data-sub_set="{{ substr($comment->comment, 0, 50) ."..." }}"> <i class="fa fa-reply"></i> </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                    </div>

                @endforeach

            </div>

        </div>
        
        <div class="tab-pane fade clearfix" id="nav-chat">                 

	        <div class="col-md-12 no-p">

	            <div class="chat-box">
	                <div class="chat-heading pane-theme">
	                    Chat
	                </div>

	                <div class="chat-body">
	                  <div class="chat-body-overlay">
	                      <div class="chat-messages">

	                        @foreach($ticket->chat_room->conversations as $conversation)
	                          <div class="message{{ $conversation->sender_id != Auth::id() && $conversation->sender_type != 'App\Models\Operator' ? ' others' : '' }}">
	                            <header>{{$conversation->sender_type() }} : {{ $conversation->sender_id != Auth::id() && $conversation->sender_type != 'App\Models\Operator' ? $conversation->sender ? $conversation->sender->name :' Unknown' : 'Me' }}</header>
	                            <article>{{ $conversation->message }}</article>
	                            <span class="time">{{ $conversation->created_at->format('H:i') }}</span>
	                          </div>
	                        @endforeach
	                      
	                      </div>

                            @if (Gate::check('owns.ticket', $ticket) )
                                <div class="chat-input">
                                    <form>
                                        <input type="text" id="chat-input" maxlength="200" placeholder="Send a message..."/>
                                    </form>
                                </div>
                            @endif
	                  </div>
	                </div>

	            </div>

	        </div>

        </div>

        <div class="tab-pane fade clearfix" id="nav-operators">                 
        	<div class="table-responsive">
        		<table class="table">
        			<thead>
        				<tr>
        					<th>Name</th>
        					<th>Email</th>
        				</tr>
        			</thead>
        			<tbody>
	        			@foreach($ticket->operators as $operator)
	        				<tr>
	        					<td>{{ $operator->name }}</td>
	        					<td>{{ $operator->email }}</td>
	        				</tr>
	        			@endforeach
	        		</tbody>
        		</table>
        	</div>
        </div>

        <div class="tab-pane fade clearfix" id="nav-resources">                 

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <form id="resources-form" method="post" action="{{ route('operator.ticket.resource', [$ticket->id])}}" accept-charset="utf-8" enctype="multipart/form-data">
                
                    <div class="form-group" style="border: 1px black solid; padding: 4px;">

                        {{csrf_field()}}
                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                        <button class="btn btn-success btn-transparent btn-xs" onclick="event.preventDefault(); addresource('resource-container');"> <i class="fa fa-plus"></i> Add Resources </button> (Size 3MB) (Max 5)

                    </div>
                
                    <div class="form-group">

                        <div id="resource-container" style="background: #47a3da;"></div>
                    
                    </div>

                    <div class="form-group clearfix" style="border: 1px black solid; padding: 5px;">
                        <label class="f-s-15"><input type="checkbox" name="allow_tenant"> Make Resource Visible <i class="fa fa-eye"></i> to customer </label>
                        <button type="submit" class="btn pull-right btn-transparent btn-success "> Submit</button>
                    </div>

                </form>
                
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                <span class="preview-block hide" style="display: inline-block;">
                    <div class="clearfix"> <span class="preview-title h2"> </span> <button type="button" class="btn btn-transparent btn-danger pull-right close-content"> Close </button ></div>
                    {{-- <iframe id="preview_content"  height="700" src="" style="width: 100%;"> </iframe> --}}
                    <object id="preview_content"  height="800" data="" style="width: 100%;"></object>
                </span>

                <span class="resource-list" style="display: inline-block; min-width: 200px;">
                    @foreach($ticket->resources as $resource)

                        <div class="resource-file" >
                            <div class="cell {{$resource->getIcon()}}">
                                <span class="hover-name text-info" >{{ $resource->owner() }}  <span> &nbsp; ( {{$resource->getSize()}} ) </span></span>
                            </div>

                            <div>
                                <p> 
                                <a class="btn btn-success btn-xs" href="{{ asset('storage/'.$resource->path)}}"><i class="fa fa-download"></i></a>
                                @if($resource->getIcon() == "audio" || $resource->getIcon() == "image_icon" || $resource->getIcon() == "pdf" || $resource->getIcon() == "video")
                                <button type="button" class="btn btn-default btn-xs preview-btn" data-title={{str_replace(" ", "_", $resource->original_name) }} data-content="{{URL::to('storage/'.$resource->path)}}" > <i class="fa fa-eye"></i> </button>
                                @endif
                                <button class="btn btn-xs btn-danger" type="button"><i class="fa fa-times"></i></button>
                                </p>
                                <p> <label style="overflow-wrap: break-word; text-overflow: ellipsis; padding: 0px 6px;">{{$resource->original_name}}</label> </p>
                            </div>
                            
                            
                        </div>

                    @endforeach
                </span>
            </div>


        </div>

    </div>	
</div>
