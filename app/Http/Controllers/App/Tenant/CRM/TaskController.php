<?php

namespace App\Http\Controllers\App\Tenant\CRM;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use App\Models\User;
use App\Models\Contact;
use App\Models\Opportunity;
use App\Models\Quote;
use App\Models\Invoice;
use App\Models\Account;
use App\Models\Comment;
use App\Models\Resource;


use App\Helpers\TaskHelper;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

use App\Services\Tenant\TaskService;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Carbon\Carbon;
use Auth;
use File;
use Storage;
use Validator;

class TaskController extends Controller
{

    function __construct(){

        $this->taskHelper = new TaskHelper;
        $this->middleware(['tenant', 'auth']);
        $this->taskService = new TaskService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Todo::company()->paginate(50);

        return view('app.tenant.crm.task.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($domain, Request $request, $id=null)
    {
        
        $task = Todo::company()->where('id', $id)->get()->first();
        if(!$task){ $task = new Todo; }
        $users = User::company()->get();
        $taskHelper = $this->taskHelper;
 

        return view('app.tenant.crm.task.create', compact('users', 'taskHelper', 'task'));
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
            
            'assignee' => 'required',
            'title' => 'required|min:4|max:255',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'duration' => 'required|numeric|min:1',
            'duration_unit' => 'required',
            'description' => 'max:62500',
            'task_type' => 'required',
            'repeat_interval' => 'required_with:repeat_task',
            'daily_repeat_freq' => 'required_if:repeat_interval,Daily',
            'weekly_repeat_freq' => 'required_if:repeat_interval,Weekly',
            'monthly_repeat_freq' => 'required_if:repeat_interval,Monthly',
            'weekly_repeat_days' => 'nullable|required_if:repeat_interval,Weekly|array',
            'monthly_repeat_type' => 'required_if:repeat_interval,Monthly',
            'monthly_day_num' => 'required_if:monthly_repeat_type,day_num',
            'monthly_day_pos' => 'required_if:monthly_repeat_type,day_pos',
            'monthly_day_name' => 'required_if:monthly_repeat_type,day_pos',
            'repeat_end_type' => 'required_with:repeat_task',
            'repeat_end_date' => 'nullable|required_if:repeat_end_type,Date|date|after:today'

        ]);

    
        if ($validator->fails()) return response()->json(['error' => $validator->errors()->all()], 422);

        $response = $this->taskService->store($request->all());
     
        return response()->json($response['response'], $response['code']); 

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($domain, $id)
    {
        $task = Todo::find($id) ?? abort(404);
        $users = User::company()->get();
        $taskHelper = $this->taskHelper;
        

        return view('app.tenant.crm.task.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($domain, Request  $request, $id)
    {
        $task = Todo::company()->where('id', $id)->get()->first() ?? abort(404);
        if(!$task){ $task = new Todo; }
        $users = User::company()->get();
        $taskHelper = $this->taskHelper;
 

        return view('app.tenant.crm.task.edit', compact('users', 'taskHelper', 'task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            
            'assignee' => 'required',
            'title' => 'required|min:4|max:255',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'duration' => 'required|numeric',
            'duration_unit' => 'required',
            'description' => 'max:62500',
            'task_type' => 'required',
            'task_id' => 'required|exists:todos,id'

        ]);

    
        if ($validator->fails()) return response()->json(['error' => $validator->errors()->all()], 422);

        $response = $this->taskService->store($request->all());
     
        return response()->json($response['response'], $response['code']); 
    }

    public function status(Request $request, $domain)
    {
        $validator = Validator::make($request->all(), [

            'status' => 'required',
            'task_id' => 'required|exists:todos,id',

        ]);

        if ($validator->fails()) return response()->json(['error' => $validator->errors()->all() ], 422);

        $response = $this->taskService->status($request->all());
     
        return response()->json($response['response'], $response['code']);    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($domain, Request $request)
    {        
        $validator = Validator::make($request->all(), [

            'task_id' => 'required|exists:todos,id',

        ]);

        if ($validator->fails()) return response()->json(['error' => $validator->errors()->all() ], 422);
        $tenant = TenantManager::get();

        $task = Todo::find($request->task_id);
        $task->delete();
        
        return response()->json(['success' => 'Task Successfully Deleted', 'url' => route('tenant.crm.task.index', [$domain])], 200);    
    }

    public function comment($domain, Request $request)
    {
        $this->validate($request, [
                'task_id' => 'required|exists:todos,id', 
                'comment' => 'required|string|min:5', 
                'resources.*' => 'mimes:jpeg,png,pdf,doc,docx,pptx,ppt,xls,xlsx,msg|max:4000']);

        $task = Todo::company()->where('id', $request->task_id)->get()->first();

        if(!$task){ 
            return redirect()->back()->withInput($request->input())->withErrors(['task not found.']);
        }

        $comment = Comment::create([
        'commentable_id' => Auth::user()->id,
        'commentable_type' => "App\Models\User",
        'isInternal' => 1,
        'sub_set' => $request->sub_set ? substr($request->sub_set,0,50) : null,
        'comment' => $request->comment,
        'todo_id' => $task->id,
        ]);

        $now = new \DateTime;

        if($request->has('resources')){

            foreach($request->resources as $index => $resource){
                
                $file = $resource;
                $tmpName = $file->getFileName()."".time(); 
                $path = "resource/todo/".$task->id."/".$now->format('Y-M-d')."/";
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
                $res->category = "Task Resource";
                $res->module_id = $task->id;
                $res->module_type = "App\Models\Todo";
                $res->save();


            }  
        }
        
        return back()->with('flash_message', 'Your comment has been sent');
    }

    public function mytask($oomain, Request $request){

        $tasks = (new Todo)->newQuery()->company()->where('assigner_id', Auth::id())->orWhere('assignee_id', Auth::id());

        if ( $request->has('priority') && $request->priority != 'All' ) {
            $priority = $request->priority;
            $tasks = $tasks->where('priority', $priority );
        }

        if ( $request->has('status') && $request->status != 'All' ) {
            $status = $request->status;
            $tasks = $tasks->where('status', $status );
        }        

        if( $request->has('title') && trim($request->title) != '' ){
            $tasks = $tasks->where('title', 'like', '%'. $request->title . '%');
        }

        return view('app.tenant.crm.task.mytask', compact('tasks'));
    }

}
