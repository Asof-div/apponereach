<?php

namespace App\Http\Controllers\App\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ChatRoom;
use App\Models\User;
use Auth;

class ChatController extends Controller
{    
    public function __construct()
    {
        $this->middleware(['tenant', 'auth:web']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rooms = Auth::user()->chat_rooms->where('associate', 'User');
        $users = User::company()->get();

        return view('app.tenant.chat.index', compact('rooms', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $ids = User::find(6)->chat_rooms->where('type', 'Private')->pluck('id');
        \Log::log('info', is_array( $ids) );    
        dd($user->hasChatRoom($ids ) );


        // $room = ChatRoom::create([
        //     'name' => time(),
        //     'type' => 'Private',
        //     'creator_id' => Auth::id(),
        //     'creator_type' => 'App\Models\User',
        // ]);

        // $user->chat_rooms()->save($room);

        // User::find(6)->chat_rooms()->save($room);
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
            'user_id' => 'required|exists:users,id',
            'sender' => 'required|exists:users,id',
        ]);



        ChatRoom::create([
            'name' => $request->name,
            'type' => $request->type,
            'created_by' => Auth::id()
        ]);

        return redirect()->to('home');
    }

    public function join(Room $room)
    {
        $room->load('conversations.user');
        $conversations = $room->conversations()->orderBy('created_at')->get();

        return view('app.conversation.show', compact('room', 'conversations'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
