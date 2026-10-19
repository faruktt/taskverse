<?php

namespace App\Http\Controllers;

use App\Models\{Conversation, Message};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function send(Request $request, Conversation $conversation)
    {
        $user = Auth::user();
        abort_if($conversation->buyer_id !== $user->id && $conversation->seller_id !== $user->id, 403);

        $request->validate([
            'body'       => 'nullable|string|max:2000',
            'attachment' => 'nullable|file|max:5120',
        ]);

        $attachment = null;
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment')->store('attachments','public');
        }

        $msg = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $user->id,
            'body'            => $request->body,
            'attachment'      => $attachment,
        ]);

        $conversation->update(['last_message_at' => now()]);

        return back();
    }
}
