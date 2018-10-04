<?php

namespace App\Http\Controllers;

use App\Conversations;
use App\Messages;
use App\User;
use App\ConversationUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $check = false;
        $existingconid='';
        $loggedInUser = Auth::user()->id;
        $conIdsForUser = ConversationUser::where('user_id','=', $loggedInUser)->get();
        if($conIdsForUser == null){
            //Create Conversation
            $conversationId = Conversations::create([
                'user_id' => Auth::user()->id
            ]);

            //Sender
            $conversation_user = ConversationUser::create([
                'user_id' => Auth::user()->id,
                'conversation_id' => $conversationId->id
            ]);

            //reciever
            ConversationUser::firstOrCreate([
                'user_id' => $request->user_id,
                'conversation_id' => $conversationId->id
            ]);
            $conversationId = $conversationId->id;
        }
        else{
            foreach($conIdsForUser as $item){
                $conItem = $item->conversation_id;
                $conversations = ConversationUser::where('conversation_id', '=', $conItem)->get();
                foreach($conversations as $con){
                    if($con->user_id == $request->user_id){
                        $check = true;
                        $conversationId = $conItem;
                    }
                }
            }
        }

        if (!$check){
            //Create Conversation
            $conversationId = Conversations::create([
                'user_id' => Auth::user()->id
            ]);

            //Sender
            $conversation_user = ConversationUser::create([
                'user_id' => Auth::user()->id,
                'conversation_id' => $conversationId->id
            ]);

            //reciever
            ConversationUser::firstOrCreate([
                'user_id' => $request->user_id,
                'conversation_id' => $conversationId->id
            ]);

            $conversationId = $conversationId->id;
        }

        //Message
        Messages::create([
            'conversation_id' => $conversationId,
            'user_id' => Auth::user()->id,
            'message' => $input['message'],
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Conversations  $conversations
     * @return \Illuminate\Http\Response
     */
    public function show(string $conversations)
    {
        $check = false;
        $existingconid = '';
        $loggedInUser = Auth::user()->id;
        $tests = ConversationUser::where('user_id','=', $loggedInUser)->get();
        foreach($tests as $test){
            $conid = $test->conversation_id;
            $getcons = ConversationUser::where('conversation_id', '=', $conid)->get();
            foreach($getcons as $getcon){
                if($getcon->user_id == $conversations){
                    $check = true;
                    $existingconid = $getcon->conversation_id;
                }
            }
        }
        $chats = Messages::where('conversation_id', '=', $existingconid)->where('read','=', true)->get();
        $users = User::find($conversations);
        return view('chat.index')->with(compact('conversations', 'chats', 'users', 'existingconid'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Conversations  $conversations
     * @return \Illuminate\Http\Response
     */
    public function edit(Conversations $conversations)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Conversations  $conversations
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Conversations $conversations)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Conversations  $conversations
     * @return \Illuminate\Http\Response
     */
    public function destroy(Conversations $conversations)
    {
        //
    }

    public function ajax(Request $request){
        $data = $request->all();
        $conversationId = $data['conversation_id'];
        $userId = $data['user_id'];
        $message = Messages::where('conversation_id', '=', $conversationId)->where('user_id', '=', $userId)->where('read', '=', false)->first();
        if (count($message) > 0)
        {
            $message->read = true;
            $message->save();
            return $message->message;
        }
        if (count($message) > 0)
        {
            return response()->json([
                'message' => $message
            ]);
        }

        // return response()->json([
        //     'message' => $message
        // ]);
        // $message = Messages::where('sender_username', '!=', $username)->where('read', '=', false)->first();
        // while(Messages::where('read',false))
        // echo $data;
    }

}
