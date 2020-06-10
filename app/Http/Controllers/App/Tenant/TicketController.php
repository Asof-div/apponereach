<?php

namespace App\Http\Controllers\App\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Operator;
use App\Models\Resource;
use App\Models\Comment;
use App\Models\ChatRoom;
use App\Scopes\Facade\TenantManagerFacade as TenantManager;

use Auth;
use Carbon\Carbon;
use Mail;
use App\Mail\NewTicketMail;
use App\Mail\StatusTicketMail;

use Validator;
use Storage;
use File;

class TicketController extends Controller
{
    public function __construct(){

        $this->middleware(['auth:web', 'tenant']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($domain, Request $request)
    {
        $incidents = Incident::get();

        $tickets = (new Ticket)->newQuery()->company();
        
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
        }

        if($request->has('start_date') ){
            $start_date = $request->start_date ? (new \DateTime($request->start_date))->format('Y-m-d') : (new \DateTime)->modify('-3 month')->format('Y-m-d');

            $start = Carbon::parse($start_date );

            $end_date = $request->end_date ? (new \DateTime($request->end_date))->format('Y-m-d') : (new \DateTime)->format('Y-m-d');

            $end = Carbon::parse($end_date)->endOfMonth();

            $tickets = $tickets->whereDate('start_date', '>=', $start)->whereDate('start_date', '<=', $end); 
        }

        $tickets = $tickets->orderBy('due_date', 'desc')->orderBy('priority', 'desc')->with(['tenant', 'incident'])->paginate(50);

        return view('app.tenant.ticket.index', compact('tickets', 'incidents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($domain)
    {
        $incidents = Incident::get();

        return view('app.tenant.ticket.create', compact('incidents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($domain, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'tenant_id' => 'required|exists:tenants,id',
            'incident_type' => 'required|exists:incidents,id',
            'body' => 'required|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()], 422);
        }
        
        $incident = Incident::find($request->incident_type);
        if(!$incident){ 
            return response()->json(['error'=>['Incident Type does not exists.']], 422);
        }

        $today = Carbon::now();
        $due_date= $today->copy()->modify($incident->initial_response_time)->modify($incident->expected_resolution_time);
        $ticket_no = $today->format('Y-m-d_').sprintf("%04d", $this->generate());
        $chat = ChatRoom::create([
                    'name' => $ticket_no,
                    'type' => 'Group',
                    'creator_id' => Auth::id(),
                    'creator_type' => 'App\Models\User',
                    'associate' => 'Ticket',
                ]);
        $ticket = Ticket::create([
            'ticket_no' =>  $ticket_no,
            'body' => $request->body,
            'title' => $request->title,
            'tenant_id' => $request->tenant_id,
            'incident_id' => $request->incident_type,
            'subject' => $incident->label,
            'initial_response' => $incident->initial_response,
            'initial_response_unit' => $incident->initial_response_unit,
            'escalation_interval' => $incident->escalation_interval,
            'escalation_interval_unit' => $incident->escalation_interval_unit,
            'expected_resolution' => $incident->expected_resolution,
            'expected_resolution_unit' => $incident->expected_resolution_unit,
            'status' => 'Unassigned',
            'start_date' => $today,
            'due_date' => $due_date,
            'priority' => $incident->priority,
            'severity' => $incident->severity, 
            'chat_room_id' => $chat->id,
            'creator_id' => Auth::id(),
            'creator_type' => 'App\Models\User',
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
                $res->owner_type = "User";
                $res->category = "Ticket Resource";
                $res->module_id = $ticket->id;
                $res->module_type = "App\Models\Ticket";
                $res->save();

                $ticket->resources()->save($res, ['allow_tenant' => true, 'created_at' => Carbon::now()]);

            }
        }

        $admin_email = $incident->admin ? $incident->admin->email : '';
        $operator_email = $incident->operator ? $incident->operator->email : '';
        $emails = [$admin_email, $operator_email]; 
        
        foreach ($incident->admins as $admin) {
            $ticket->assignToAdmin($admin);
            $emails[] = $admin->email;
        }


        foreach ($incident->operators as $operator) {
            $ticket->assignToOperator($operator);
            $emails[] = $operator->email;
        }
        
        Mail::bcc($emails)->queue((new NewTicketMail($ticket)) );

        return response()->json(['success'=>'Ticket Successfully Created.', 'url' => route('tenant.ticket.show', [$domain, $ticket->id])]  , 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($domain, Ticket $ticket)
    {
        $ticket->load(['resources', 'operator']);

        return view('app.tenant.ticket.show', compact('ticket'));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status($domain, Request $request)
    {
        $this->validate($request, ['ticket_id' => 'required|exists:tickets,id', 'status' => 'required']);
        
        $ticket = Ticket::find($request->ticket_id);
        if(!$ticket){ 
            return redirect()->back()->withInput($request->input())->withErrors(['Ticket not found.']);
        }

        if(strtolower($ticket->status) == strtolower($request->status)){ return redirect()->route('tenant.ticket.show', [$domain, $ticket->id])->withErrors(['Status is the same as the previous.']); }

        $status = ['old' => $ticket->status() ];
        $incident = $ticket->incident;
        $ticket->update(['status' => ucfirst($request->status)]);
        $status['new'] = $ticket->status();

        $admin_email = $incident->admin ? $incident->admin->email : '';
        $operator_email = $incident->operator ? $incident->operator->email : '';
        $emails = [$admin_email, $operator_email]; 

        Mail::to($ticket->creator ? $ticket->creator->email : '')->bcc($emails)->queue((new StatusTicketMail($ticket, Auth::user()->firstname, $status )) );

        return redirect()->route('tenant.ticket.show', [$domain, $ticket->id])->with('flash_message', 'Ticket Status Successfully Updated');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($domain, Request $request, Ticket $ticket)
    {
        $this->validate($request, ['body' => 'required|max:50000']);

        if(!$ticket){ return redirect()->route('tenant.ticket.show', [$domain, $ticket->id])->withErrors(['Ticket Not Found.']); }
     
        $ticket->update(['body' => $request->body]);

    
        return redirect()->route('tenant.ticket.show', [$domain, $ticket->id])->with('flash_message', 'Ticket Successfully Updated');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($domain, Request $request, Ticket $ticket)
    {
        $ticket->delete();

        return redirect()->route('tenant.ticket.index', [$domain])->with('flash_message', 'Ticket Successfully Deleted');
    }


    public function resource($domain, Request $request, Ticket $ticket){
    
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

            $ticket->resources()->save($res, ['allow_tenant' => true, 'created_at' => Carbon::now()]);

        }

        return redirect()->route('tenant.ticket.show', [$domain, $ticket->id])->with('flash_message', 'Ticket Resource Successfully Uploaded');       

    }

    public function generate(){
        $todays_ticket = Ticket::whereDate('created_at', Carbon::now()->format('Y-m-d').'%' )->get();
        $id = 1;
        if($todays_ticket){
            $id = count($todays_ticket) + 1;
        }

        return $id;
    }

}
