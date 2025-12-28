<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Display the feedback form
     */
    public function create()
    {
        return view('feedback.create');
    }

    /**
     * Store a newly created feedback
     */
    public function store(Request $request)
    {
        $request->validate([
            'feedbackType' => 'required|in:Error/Bug Report,Review,Suggestion',
            'feedbackText' => 'required|string|min:10|max:1000',
        ]);

        Feedback::create([
            'feedbackType' => $request->feedbackType,
            'feedbackStatus' => 'Pending',
            'feedbackText' => $request->feedbackText,
            'feedbackDate' => now(),
            'userID' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Feedback submitted successfully! Thank you for helping us improve.');
    }
}