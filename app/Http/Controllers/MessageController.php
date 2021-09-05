<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Http\Requests\MessageRequest;
use App\Models\Message;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except(['store']);
    }

    public function index()
    {
        $messages = Message::latest()->get();
        return MessageResource::collection($messages);
    }

    public function store(MessageRequest $request)
    {
        $message = new Message;
        $message->name = $request->name;
        $message->email = $request->email;
        $message->content = $request->content;
        $message->save();
        return response()->json();
    }

    public function show($id)
    {
        //
    }

    public function update(MessageRequest $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
