<?php

namespace App\Http\Controllers\App\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\Ticket;
use App\Models\Tenant;
use App\Models\Operator;
use App\Models\User;
use App\Models\Admin;
use App\Models\Resource;
use App\Models\Comment;
use App\Models\ChatRoom;

use Auth;
use Carbon\Carbon;
use Mail;
use App\Mail\NewTicketMail;
use App\Mail\AssignTicketMail;
use App\Mail\StatusTicketMail;

use Validator;
use Storage;
use File;

class TicketController extends Controller
{
        public function __construct(){

        $this->middleware('auth:operator');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $operators = Operator::get();
        $incidents = Incident::get();

        $tickets = (new Ticket)->newQuery();

        if ( $request->has('name') && !is_null($request->name) ) {

            $tickets = $tickets->whereHas('tenant', function($query) use ($request) {
                $query->where('name', 'ilike', '%' . $request->name . '%');
            });
        }
        
        if ( $request->has('incident') && $request->incident != 'All' ) {
            $incident = $request->incident;
            $tickets = $tickets->where('incident_id', $incident );
        }

        if ( $request->has('priority') && $request->priority != 'All' ) {
            $priority = $request->priority;
            $tickets = $tickets->where('priority', $priority );
        }

        if ( $request->has('status') && $request->status != 'All' ) {
            $status = $request->status;
            $tickets = $tickets->where('status', $status );
        }elseif (! $request->has('status') ) {
            
            $ticket = $tickets->where('status', 'Unassigned')->orWhere('status', 'Open');
        }

        if($request->has('start_date') ){
            $start_date = $request->start_date ? (new \DateTime($request->start_date))->format('Y-m-d') : (new \DateTime)->modify('-3 month')->format('Y-m-d');

            $start = Carbon::parse($start_date );

            $end_date = $request->end_date ? (new \DateTime($request->end_date))->format('Y-m-d') : (new \DateTime)->format('Y-m-d');

            $end = Carbon::parse($end_date)->endOfMonth();

            $tickets = $tickets->whereDate('start_date', '>=', $start)->whereDate('start_date', '<=', $end); 
        }

        $tickets = $tickets->orderBy('due_date', 'desc')->orderBy('priority', 'desc')->with(['tenant', 'incident'])->paginate(50);

        return view('app.operator.ticket.index', compact('tickets', 'operators', 'incidents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $incidents = Incident::get();
        $customers = Tenant::get();

        return view('app.operator.ticket.create', compact('incidents', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'incident_type' => 'required|exists:incidents,id',
            'body' => 'required|min:10',
            'resources.*' => 'mimes:jpeg,png,pdf,doc,docx,pptx,ppt,xls,xlsx,msg|max:3000'
            ]);
        $incident = Incident::find($request->incident_type);
        if(!$incident){ 
            return redirect()->route('operator.ticket.create')->withInput($request->input())->withErrors(['Incident Type does not exists.']);
        }

        $today = Carbon::now();
        $due_date= $today->copy()->modify($incident->initial_response_time)->modify($incident->expected_resolution_time);
        $ticket_no = $today->format('Y-m-d_').sprintf("%04d", $this->generate());
        $chat = ChatRoom::create([
                    'name' => $ticket_no,
                    'type' => 'Group',
                    'creator_id' => Auth::id(),
                    'creator_type' => 'App\Models\Operator',
                    'associate' => 'Ticket',
                ]);
        $ticket = Ticket::create([
            'ticket_no' =>  $ticket_no,
            'body' => $request->body,
            'title' => $request->title,
            'incident_id' => $request->incident_type,
            'subject' => $incident->label,
            'initial_response' => $incident->initial_response,
            'initial_response_unit' => $incident->initial_response_unit,
            'escalation_interval' => $incident->escalation_interval,
            'escalation_interval_unit' => $incident->escalation_interval_unit,
            'expected_resolution' => $incident->expected_resolution,
            'expected_resolution_unit' => $incident->expected_resolution_unit,
            'start_date' => $today,
            'tenant_id' => $request->customer,
            'status' => 'Unassigned',
            'due_date' => $due_date,
            'priority' => $incident->priority,
            'severity' => $incident->severity, 
            'chat_room_id' => $chat->id,
            'created_by_operator_id' => Auth::id(),
            'creator_id' => Auth::id(),
            'creator_type' => 'App\Models\Operator',
            ]);

        $allow_tenant = $request->allow_tenant ? true : false;
        $now = new \DateTime;
        $emails = [];

        if($request->has('resources')){
            foreach($request->resources as $index => $resource){
                
                $file = $resource;
                $tmpName = $file->getFileName()."".time(); 
                $path = "resource/ticket/".$ticket->ticket_no."/".$now->format('Y-M-d')."/";
                $filename = $file->getClientOriginalName();
                $ext = $file->getClientOriginalExtension();
                $fileSize = $file->getClientSize();

                Storage::disk('local')->put("public/".$path . $tmpName.".".$ext,  File::get($file));

                $res = new Resource;
                $res->original_name = $filename;
                $res->ext = $ext;
                $res->filename = $tmpName;
                $res->size = $fileSize;
                $res->mime_type = $file->getMimeType();
                $res->error = $file->getError()?1:0;
                $res->path = $path.$tmpName.".".$ext;
                $res->operator_id = Auth::user()->id;
                $res->owner_type = "Operator";
                $res->category = "Ticket Resource";
                $res->module_id = $ticket->id;
                $res->module_type = "App\Models\Ticket";
                $res->save();

                $ticket->resources()->save($res, ['allow_tenant' => $allow_tenant, 'created_at' => Carbon::now()]);

            }
        }

        $admin_email = $incident->admin ? $incident->admin->email : '';
        $operator_email = $incident->operator ? $incident->operator->email : '';

        if($admin_email) {$emails[] = $admin_email; } 
        if($operator_email) {$emails[] = $operator_email; } 
        foreach ($incident->admins as $admin) {
            $ticket->assignToAdmin($admin);
            $emails[] = $admin->email;
        }

        $user_emails = [];
        foreach (User::where('tenant_id', $request->customer)->get() as $key => $user) {
            if($user->hasPermission(['tenant.ticket.create', 'tenant.ticket.read', 'tenant.ticket.update', 'tenant.ticket.delete', 'admin.access'])){
                $user_emails[] = $user->email;
            }
        }

        foreach ($incident->operators as $operator) {
            $ticket->assignToOperator($operator);
            $emails[] = $operator->email;
        }

        Mail::to($emails)->queue((new NewTicketMail($ticket)) );
        Mail::to($user_emails)->queue((new NewTicketMail($ticket, 'tenant')) );

        return redirect()->route('operator.ticket.show', [$ticket->id])->with('flash_message', 'Ticket Successfully Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        
        $operators = Operator::get();
        $incidents = Incident::get();

        return view('app.operator.ticket.show', compact('ticket', 'operators', 'incidents'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request)
    {
        $this->validate($request, ['ticket_id' => 'required|exists:tickets,id', 'status' => 'required']);
        
        $ticket = Ticket::find($request->ticket_id);
        if(!$ticket){ 
            return redirect()->back()->withInput($request->input())->withErrors(['Ticket not found.']);
        }

        if(strtolower($ticket->status) == strtolower($request->status)){ return redirect()->route('operator.ticket.show', [$ticket->id])->withErrors(['Status is the same as the previous.']); }

        $status = ['old' => $ticket->status() ];
        $incident = $ticket->incident;
        $ticket->update(['status' => ucfirst($request->status)]);
        $status['new'] = $ticket->status();

        $admin_email = $incident->admin ? $incident->admin->email : '';
        $operator_email = $incident->operator ? $incident->operator->email : '';
        $emails = [$admin_email, $operator_email]; 

        Mail::to($ticket->creator ? $ticket->creator->email : '')->bcc($emails)->queue((new StatusTicketMail($ticket, Auth::user()->firstname, $status )) );

        return redirect()->route('operator.ticket.show', [$ticket->id])->with('flash_message', 'Ticket Status Successfully Updated');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        $this->validate($request, ['incident' => 'required|exists:incidents,id']);

        if($ticket->incident_id == $request->incident){ return redirect()->route('operator.ticket.show', [$ticket->id])->withErrors(['Incident is the same as the previous.']); }
     
        $incident = Incident::find($request->incident_type);
        if(!$incident){ 
            return redirect()->route('operator.ticket.show', [$ticket->id])->withInput($request->input())->withErrors(['Incident Type does not exists.']);
        }
        $ticket->update(['incident_id' => $request->incident]);

        $ticket->admins()->detach(); 
        foreach ($incident->admins as $admin) {
            $ticket->assignToAdmin($admin);
        }


        $ticket->operators()->detach(); 
        foreach ($incident->operators as $operator) {
            $ticket->assignToOperator($operator);
        }

        return redirect()->route('operator.ticket.show', [$ticket->id])->with('flash_message', 'Ticket Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, Ticket $ticket)
    {
        $ticket->delete();

        return redirect()->route('operator.ticket.index')->with('flash_message', 'Ticket Successfully Deleted');
    }

    public function generate(){
        $todays_ticket = Ticket::whereDate('created_at', Carbon::now()->format('Y-m-d').'%' )->get();
        $id = 1;
        if($todays_ticket){
            $id = count($todays_ticket) + 1;
        }

        return $id;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function unassigned(Request $request)
    {
        $operators = Operator::get();
        $incidents = Incident::get();

        $tickets = (new Ticket)->newQuery();

        if ( $request->has('name') && !is_null($request->name) ) {

            $tickets = $tickets->whereHas('tenant', function($query) use ($request) {
                $query->where('name', 'ilike', '%' . $request->name . '%');
            });
        }
        
        if ( $request->has('incident') && $request->incident != 'All' ) {
            $incident = $request->incident;
            $tickets = $tickets->where('incident_id', $incident );
        }

        if ( $request->has('priority') && $request->priority != 'All' ) {
            $priority = $request->priority;
            $tickets = $tickets->where('priority', $priority );
        }
 
        if($request->has('start_date') ){
            $start_date = $request->start_date ? (new \DateTime($request->start_date))->format('Y-m-d') : (new \DateTime)->modify('-3 month')->format('Y-m-d');

            $start = Carbon::parse($start_date );

            $end_date = $request->end_date ? (new \DateTime($request->end_date))->format('Y-m-d') : (new \DateTime)->format('Y-m-d');

            $end = Carbon::parse($end_date)->endOfMonth();

            $tickets = $tickets->whereDate('start_date', '>=', $start)->whereDate('start_date', '<=', $end); 
        }

        $tickets = $tickets->where('status', 'Unassigned')->orderBy('due_date', 'desc')->orderBy('priority', 'desc')->with(['tenant', 'incident'])->paginate(50);

        return view('app.operator.ticket.unassigned', compact('tickets', 'operators', 'incidents'));
    }

    public function assign(Request $request){

        $this->validate($request, [
            'ticket_id' => 'required|exists:tickets,id',
            'user_id' => 'required|exists:operators,id',
        ]);

        $ticket = Ticket::find($request->ticket_id);
        if(!$ticket){ 
            return redirect()->back()->withInput($request->input())->withErrors(['Ticket not found.']);
        }
        $assignee = Operator::find($request->user_id);

        $ticket->update([
            'assigned_operator_id' => $request->user_id,
            'status' => 'Open',
        ]);

        Mail::to($assignee? $assignee->email : '')->cc(Auth::user()->email)->queue((new AssignTicketMail($ticket, Auth::user()->name, $assignee->lastname)) );

        if(strtolower($request->page) == 'index'){
            return redirect()->route('operator.ticket.index')->with('flash_message', 'Ticket Successfully Assigned');
        }

        return redirect()->route('operator.ticket.show', [$ticket->id])->with('flash_message', 'Ticket Successfully Assigned');
    }

    public function escalate(Request $request, Ticket $ticket){

        return redirect()->route('operator.ticket.show', [$ticket->id])->with('flash_message', 'Ticket Successfully Escalated To Admin');
    }

    public function resource(Request $request, Ticket $ticket){
    
        $this->validate($request, [
            
            'ticket_id' => 'required|exists:tickets,id',
            'resources.*' => 'mimes:jpeg,png,pdf,doc,docx,pptx,ppt,xls,xlsx,msg|max:3000'

        ]);


        if(!$ticket){ 
            return redirect()->back()->withInput($request->input())->withErrors(['Ticket not found.']);
        }

        if( count($request->resources) < 1 ){ 
            return redirect()->back()->withInput($request->input())->withErrors(['No Resource attached.']);
        }
    
        $allow_tenant = $request->allow_tenant ? true : false;
        $now = new \DateTime;

        foreach($request->resources as $index => $resource){
            
            $file = $resource;
            $tmpName = $file->getFileName()."".time(); 
            $path = "resource/ticket/".$ticket->ticket_no."/".$now->format('Y-M-d')."/";
            $filename = $file->getClientOriginalName();
            $ext = $file->getClientOriginalExtension();
            $fileSize = $file->getClientSize();

            Storage::disk('local')->put("public/".$path . $tmpName.".".$ext,  File::get($file));

            $res = new Resource;
            $res->original_name = $filename;
            $res->ext = $ext;
            $res->filename = $tmpName;
            $res->size = $fileSize;
            $res->mime_type = $file->getMimeType();
            $res->error = $file->getError()?1:0;
            $res->path = $path.$tmpName.".".$ext;
            $res->operator_id = Auth::user()->id;
            $res->owner_type = "Operator";
            $res->category = "Ticket Resource";
            $res->module_id = $ticket->id;
            $res->module_type = "App\Models\Ticket";
            $res->save();

            $ticket->resources()->save($res, ['allow_tenant' => $allow_tenant, 'created_at' => Carbon::now()]);

        }

        return redirect()->route('operator.ticket.show', [$ticket->id])->with('flash_message', 'Ticket Resource Successfully Uploaded');       

    }

    public function comment(Request $request, Ticket $ticket)
    {
        $this->validate($request, ['comment' => 'required|string|min:5', 'resources.*' => 'mimes:jpeg,png,pdf,doc,docx,pptx,ppt,xls,xlsx,msg|max:4000']);

        if(!$ticket){ 
            return redirect()->back()->withInput($request->input())->withErrors(['Ticket not found.']);
        }

        $comment = Comment::create([
        'commentable_id' => Auth::user()->id,
        'commentable_type' => "App\Models\Operator",
        'isInternal' => 1,
        'sub_set' => $request->sub_set ? substr($request->sub_set,0,50) : null,
        'comment' => $request->comment,
        'ticket_id' => $ticket->id,
        ]);

        $now = new \DateTime;

        if($request->has('resources')){

            foreach($request->resources as $index => $resource){
                
                $file = $resource;
                $tmpName = $file->getFileName()."".time(); 
                $path = "resource/ticket/".$ticket->ticket_no."/".$now->format('Y-M-d')."/";
                $filename = $file->getClientOriginalName();
                $ext = $file->getClientOriginalExtension();
                $fileSize = $file->getClientSize();

                Storage::disk('local')->put("public/".$path . $tmpName.".".$ext,  File::get($file));

                $res = new Resource;
                $res->original_name = $filename;
                $res->ext = $ext;
                $res->filename = $tmpName;
                $res->size = $fileSize;
                $res->mime_type = $file->getMimeType();
                $res->error = $file->getError()?1:0;
                $res->path = $path.$tmpName.".".$ext;
                $res->operator_id = Auth::user()->id;
                $res->owner_type = "Operator";
                $res->category = "Ticket Resource";
                $res->module_id = $ticket->id;
                $res->module_type = "App\Models\Ticket";
                $res->save();

                $comment->resources()->save($res, ['ticket_id' => $ticket->id, 'allow_tenant' => false, 'created_at' => Carbon::now()]);

            }  
        }
        
        return back()->with('flash_message', 'Your comment has been sent');
    }


}
