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

        // ---------------- Handover Requests ----------------
        $newClaimRequestsCount = HandoverRequest::where('requestType', 'Claim')
            ->where('requestStatus', 'Pending')
            ->where('recipientID', $userId)
            ->count();

        $newReturnRequestsCount = HandoverRequest::where('requestType', 'Return')
            ->where('requestStatus', 'Pending')
            ->where('recipientID', $userId)
            ->count();

        $pendingHandoverCount = HandoverRequest::where('requestStatus', 'Pending')
            ->where('recipientID', $userId)
            ->count();

        $acceptedHandoverCount = HandoverRequest::where('requestStatus', 'Accepted')
            ->where('recipientID', $userId)
            ->count();

        $rejectedHandoverCount = HandoverRequest::where('requestStatus', 'Rejected')
            ->where('recipientID', $userId)
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
            'rejectedHandoverCount'
        ));
    }
}
