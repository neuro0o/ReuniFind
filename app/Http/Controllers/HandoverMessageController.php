<?php

namespace App\Http\Controllers;

use App\Models\HandoverMessage;
use App\Models\HandoverRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HandoverMessageController extends Controller
{
    /**
     * Display list of all chats (approved handovers)
     * Like WhatsApp chat list
     */
    public function index()
    {
        $userId = Auth::id();

        // Get all approved or completed handovers where user is involved
        $chats = HandoverRequest::with(['sender', 'recipient', 'report', 'messages'])
            ->where(function($query) use ($userId) {
                $query->where('senderID', $userId)
                      ->orWhere('recipientID', $userId);
            })
            ->whereIn('requestStatus', ['Approved', 'Completed'])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function($handover) use ($userId) {
                // Get the other user (not the current user)
                $otherUser = ($handover->senderID === $userId) 
                    ? $handover->recipient 
                    : $handover->sender;

                // Get last message
                $lastMessage = $handover->messages()->latest('created_at')->first();

                // Count unread messages (you can implement this later if needed)
                $unreadCount = 0;

                return [
                    'handover' => $handover,
                    'otherUser' => $otherUser,
                    'lastMessage' => $lastMessage,
                    'unreadCount' => $unreadCount,
                    'lastActivity' => $lastMessage ? $lastMessage->created_at : $handover->updated_at,
                ];
            })
            ->sortByDesc('lastActivity');

        return view('handover.chat_list', compact('chats'));
    }

    /**
     * Display the chat page for a specific handover request
     */
    public function show($requestID)
    {
        // Get the handover request with related data
        $handover = HandoverRequest::with(['sender', 'recipient', 'report'])
            ->findOrFail($requestID);

        // Check if user is authorized (either sender or recipient)
        $userId = Auth::id();
        if ($handover->senderID !== $userId && $handover->recipientID !== $userId) {
            abort(403, 'Unauthorized access to this chat.');
        }

        // Check if handover is approved (only approved requests can chat)
        if ($handover->requestStatus !== 'Approved' && $handover->requestStatus !== 'Completed') {
            return redirect()->route('handover.index')
                ->with('error', 'Chat is only available for approved handover requests.');
        }

        // Get all messages for this handover request
        $messages = HandoverMessage::where('requestID', $requestID)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Determine the other user in the conversation
        $otherUser = ($handover->senderID === $userId) 
            ? $handover->recipient 
            : $handover->sender;

        return view('handover.chat', compact('handover', 'messages', 'otherUser'));
    }

    /**
     * Store a new message in the chat
     */
    public function store(Request $request, $requestID)
    {
        $handover = HandoverRequest::findOrFail($requestID);
        $userId = Auth::id();

        // Verify user is part of this handover
        if ($handover->senderID !== $userId && $handover->recipientID !== $userId) {
            abort(403, 'Unauthorized');
        }

        // Validate the request
        $validated = $request->validate([
            'messageText' => 'nullable|string|max:1000',
            'messageImg' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        // At least one of text or image must be provided
        if (empty($validated['messageText']) && !$request->hasFile('messageImg')) {
            return back()->with('error', 'Please provide either a message or an image.');
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('messageImg')) {
            $imagePath = $request->file('messageImg')->store('images/chat', 'public');
        }

        // Create the message
        HandoverMessage::create([
            'requestID' => $requestID,
            'senderID' => $userId,
            'messageText' => $validated['messageText'],
            'messageImg' => $imagePath,
            'created_at' => now(),
        ]);

        // Update handover's updated_at timestamp
        $handover->touch();

        return back()->with('success', 'Message sent successfully.');
    }

    /**
     * Fetch messages via AJAX for real-time updates
     */
    public function fetchMessages($requestID)
    {
        $handover = HandoverRequest::findOrFail($requestID);
        $userId = Auth::id();

        // Verify authorization
        if ($handover->senderID !== $userId && $handover->recipientID !== $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = HandoverMessage::where('requestID', $requestID)
            ->with('sender:userID,userName,profileImg')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) use ($userId) {
                return [
                    'messageID' => $message->messageID,
                    'senderID' => $message->senderID,
                    'senderName' => $message->sender->userName,
                    'senderImg' => $message->sender->profileImg 
                        ? asset('storage/' . $message->sender->profileImg) 
                        : asset('images/profiles/user_default.png'),
                    'messageText' => $message->messageText,
                    'messageImg' => $message->messageImg 
                        ? asset('storage/' . $message->messageImg) 
                        : null,
                    'created_at' => $message->created_at->format('h:i A'),
                    'isOwn' => $message->senderID === $userId,
                ];
            });

        return response()->json($messages);
    }
}