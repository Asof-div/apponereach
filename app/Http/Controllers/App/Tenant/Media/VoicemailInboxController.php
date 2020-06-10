<?php

namespace App\Http\Controllers\App\Tenant\Media;

use App\Http\Controllers\Controller;

use App\Models\VoicemailInbox;
use App\Models\PlayMedia; 

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

use App\Repos\VoicemailBoxRepo;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VoicemailInboxController extends Controller
{

    public function __construct(){

        $this->middleware(['tenant', 'auth']);
        $this->voicemailBoxRepo = new VoicemailBoxRepo;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $inboxes = VoicemailInbox::company()->get();
            
        return view('app.tenant.media-services.voicemail_inbox.index', compact('inboxes'));        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $inboxes = VoicemailInbox::company()->get();
        $sounds = PlayMedia::company()->where('application', 'file')->get();
            
        return view('app.tenant.media-services.voicemail_inbox.create', compact('inboxes', 'sounds'));
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
            'title' => ['required', Rule::unique('voicemail_inboxes', 'title')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                }), 'min:4'], 
            'user' => ['required', 'numeric', Rule::unique('voicemail_inboxes', 'user')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey())->where('isGlobal', 1);
                }), 'min:1000', 'max:99999' ],
            'pin' => 'required|numeric|min:1000|max:99999',
            'email' => 'required_if:send_to_mail,1',

            ]);
        
        $tenant = TenantManager::get();

        $this->voicemailBoxRepo->store($request->all());

        return redirect()->route('tenant.media-service.inbox.index', [$tenant->domain])->with('flash_message', 'Voicemail Box Successfully Configured');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($domain, $id, Request $request)
    {
        
        $sounds = PlayMedia::company()->where('application', 'file')->get();
        $inbox = VoicemailInbox::company()->where('user', $id)->get()->first();

        if(!$inbox){

            abort(404);
        }

        return view('app.tenant.media-services.voicemail_inbox.show', compact('inbox', 'sounds'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $this->validate($request, [
            'inbox_id' => 'required|exists:voicemail_inboxes,id',
            'title' => ['required', Rule::unique('voicemail_inboxes', 'title')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                })->ignore($request->inbox_id), 'min:4'], 
            'user' => ['required', 'numeric', Rule::unique('voicemail_inboxes', 'user')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey())->where('isGlobal', 1);
                })->ignore($request->inbox_id), 'min:1000', 'max:99999' ],
            'pin' => 'required|numeric|min:1000|max:99999',
            'email' => 'required_if:send_to_mail,1',

            ]);
        
        $tenant = TenantManager::get();

        $this->voicemailBoxRepo->update($request->all());

        return redirect()->route('tenant.media-service.inbox.show', [$tenant->domain, $request->user])->with('flash_message', 'Voicemail Box Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($domain, Request $request)
    {
        
        $this->validate($request, [
            'inbox_id' => 'required|exists:voicemail_inboxes,id',]);

        $tenant = TenantManager::get();

        $this->voicemailBoxRepo->delete($request->all());

        
        return redirect()->route('tenant.media-service.inbox.index', [$tenant->domain])->with('flash_message', 'Voicemail Box Successfully Deleted');   

    }
}
