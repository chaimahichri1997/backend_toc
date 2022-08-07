<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\MessageResource;
use App\Http\Requests\storeMessageRequest;
class MessageController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(storeMessageRequest $request)
    {
        $message = new Message();
		$message->body = $request['body'];
		$message->read = false;
		$message->user_id = $request->user()->id;
		$message->conversation_id = (int)$request['conversation_id'];
		$message->save();

		$conversation = $message->conversation;

		$user = User::findOrFail($conversation->user_id == $request->user()->id ? $conversation->second_user_id: $conversation->user_id);
		// $user->pushNotification(auth()->user()->name.' send you a message',$message->body,$message);
		return new MessageResource($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        //
    }
}
