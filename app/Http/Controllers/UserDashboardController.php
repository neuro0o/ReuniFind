<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ItemReport;
use App\Models\HandoverRequest;
use App\Models\MatchSuggestion;

class UserDashboardController extends Controller
{
    public function index() {
        $userId = auth()->id();

        // ---------------- Item Reports ----------------
        $lostReportsCount = ItemReport::where('reportType', 'Lost')
            ->where('userID', $userId)
            ->count();

        $foundReportsCount = ItemReport::where('reportType', 'Found')
            ->where('userID', $userId)
            ->count();

        $pendingReportsCount = ItemReport::where('userID', $userId)
            ->where('reportStatus', 'Pending')
            ->count();

        $publishedReportsCount = ItemReport::where('userID', $userId)
            ->where('reportStatus', 'Published')
            ->count();

        $rejectedReportsCount = ItemReport::where('userID', $userId)
            ->where('reportStatus', 'Rejected')
            ->count();

        $completedReportsCount = ItemReport::where('userID', $userId)
            ->where('reportStatus', 'Completed')
            ->count();

        // ---------------- Suggested Matches ----------------
        $newSuggestedMatchesCount = MatchSuggestion::whereHas('item', function($q) use ($userId) {
            $q->where('userID', $userId);
        })->where('matchStatus', 'suggested')
            ->count();

        // ---------------- Handover Requests (BOTH SENDER AND RECIPIENT) ----------------
        
        // New Claim Requests (as recipient only - you receive claim requests)
        $newClaimRequestsCount = HandoverRequest::where('requestType', 'Claim')
            ->where('requestStatus', 'Pending')
            ->where('recipientID', $userId)
            ->count();

        // New Return Requests (as recipient only - you receive return requests)
        $newReturnRequestsCount = HandoverRequest::where('requestType', 'Return')
            ->where('requestStatus', 'Pending')
            ->where('recipientID', $userId)
            ->count();

        // Pending Handovers (as BOTH sender OR recipient)
        $pendingHandoverCount = HandoverRequest::where('requestStatus', 'Pending')
            ->where(function($query) use ($userId) {
                $query->where('recipientID', $userId)
                      ->orWhere('senderID', $userId);
            })
            ->count();

        // Accepted Handovers (as BOTH sender OR recipient)
        $acceptedHandoverCount = HandoverRequest::where('requestStatus', 'Approved')
            ->where(function($query) use ($userId) {
                $query->where('recipientID', $userId)
                      ->orWhere('senderID', $userId);
            })
            ->count();

        // Rejected Handovers (as BOTH sender OR recipient)
        $rejectedHandoverCount = HandoverRequest::where('requestStatus', 'Rejected')
            ->where(function($query) use ($userId) {
                $query->where('recipientID', $userId)
                      ->orWhere('senderID', $userId);
            })
            ->count();

        // Completed Handovers (as BOTH sender OR recipient)
        $completedHandoverCount = HandoverRequest::where('requestStatus', 'Completed')
            ->where(function($query) use ($userId) {
                $query->where('recipientID', $userId)
                      ->orWhere('senderID', $userId);
            })
            ->count();

        return view('user.dashboard', compact(
            'lostReportsCount',
            'foundReportsCount',
            'pendingReportsCount',
            'publishedReportsCount',
            'rejectedReportsCount',
            'completedReportsCount',
            'newSuggestedMatchesCount',
            'newClaimRequestsCount',
            'newReturnRequestsCount',
            'pendingHandoverCount',
            'acceptedHandoverCount',
            'rejectedHandoverCount',
            'completedHandoverCount'
        ));
    }
}