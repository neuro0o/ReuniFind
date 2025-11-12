<?php

namespace App\Http\Controllers;

use App\Models\HandoverRequest;
use App\Models\HandoverMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HandoverMessageController extends Controller
{
    /**
     * Display all messages for a specific handover request.
     */
    public function show($handoverID)
    {
        $handover = HandoverRequest::with(['sender', 'recipient', 'report'])->findOrFail($handoverID);

        // Ensure only the sender or recipient can view the chat
        if (!in_array(Auth::id(), [$handover->senderID, $handover->recipientID])) {
            abort(403, 'Unauthorized access to this chat.');
        }

        $messages = HandoverMessage::where('handoverID', $handoverID)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('handover.chat', compact('handover', 'messages'));
    }

    /**
     * Store a new message in the chat.
     */
    public function store(Request $request, $handoverID)
    {
        $handover = HandoverRequest::findOrFail($handoverID);

        // Ensure user is part of the chat
        if (!in_array(Auth::id(), [$handover->senderID, $handover->recipientID])) {
            abort(403, 'Unauthorized to send message in this chat.');
        }

        $validated = $request->validate([
            'message' => 'nullable|string|max:1000',
            'messageImage' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('messageImage')) {
            $imagePath = $request->file('messageImage')->store('handover_messages', 'public');
        }

        HandoverMessage::create([
            'handoverID' => $handoverID,
            'senderID' => Auth::id(),
            'message' => $validated['message'] ?? null,
            'messageImage' => $imagePath,
        ]);

        return redirect()->route('handover.chat.show', $handoverID)->with('success', 'Message sent!');
    }
}
