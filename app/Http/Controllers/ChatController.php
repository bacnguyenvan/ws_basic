<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use App\Events\ChatEvent;

class ChatController extends Controller
{
    public function index()
    {
        $lists = User::where('id', '!=', auth()->user()->id)->get();
        $conversations = [];
        
        if(count($lists)) {
            $userFirst = $lists[0];
            $senderId = auth()->user()->id;
            $receiverId = $userFirst->id;

            $conversations = Message::getConversations($senderId, $receiverId);
        }

        return view('chat', compact('lists', 'conversations'));
    }

    public function chat(Request $request)
    {
        $message = $request->message;
        $senderId = $request->sender_id;
        $receiverId = $request->receiver_id;

        $data = [
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'content' => $message
        ];

        Message::create($data);

        event(new ChatEvent($senderId, $receiverId, $message));

        return $message;
    }
}
