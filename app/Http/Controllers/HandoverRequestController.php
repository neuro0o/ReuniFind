<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\HandoverRequest;
use App\Models\ItemReport;
use Illuminate\Support\Facades\Auth;

class HandoverRequestController extends Controller
{
    // Show all handovers related to the logged-in user
    public function index(Request $request)
    {
        $userID = Auth::id();
        $status = $request->query('status'); // 'sent' or 'received'

        $query = HandoverRequest::with(['report', 'sender', 'recipient'])
            ->orderBy('created_at', 'desc');

        if ($status === 'sent') {
            $query->where('senderID', $userID);
        } elseif ($status === 'received') {
            $query->where('recipientID', $userID);
        } else {
            // Default to 'sent' if no status provided
            $query->where('senderID', $userID);
            $status = 'sent';
        }

        $handovers = $query->get();

        return view('handover.index', compact('handovers', 'status'));
    }


    // ------------------ INSTANT HANDOVER ------------------ //
    public function instantHandover($reportID)
    {
        $recipientReport = ItemReport::findOrFail($reportID);
        $senderID = Auth::id();
        $recipientID = $recipientReport->userID;
        $oppositeType = $recipientReport->reportType === 'Lost' ? 'Found' : 'Lost';

        $senderReports = ItemReport::where('userID', $senderID)
            ->where('reportType', $oppositeType)
            ->where('reportStatus', 'Published')
            ->get();

        $eligibleSenderReport = $senderReports->first(function($report) use ($recipientReport, $senderID) {
            return $report->canHandoverWith($recipientReport, $senderID);
        });

        if (!$eligibleSenderReport) {
            return redirect()->back()->with('error', 'No eligible report found for handover.');
        }

        $requestType = $eligibleSenderReport->reportType === 'Found' ? 'Return' : 'Claim';

        HandoverRequest::create([
            'reportID'       => $recipientReport->reportID,
            'senderID'       => $senderID,
            'senderReportID' => $eligibleSenderReport->reportID,
            'recipientID'    => $recipientID,
            'requestType'    => $requestType,
            'proofText'      => $eligibleSenderReport->verificationNote,
            'proofImg'       => $eligibleSenderReport->verificationImg,
            'requestStatus'  => 'Pending',
        ]);

        // Update MatchSuggestion to 'pending' if coming from suggested matches
        \App\Models\MatchSuggestion::where(function($q) use ($senderReport, $recipientReport, $senderID, $recipientID) {
            $q->where(function($q2) use ($senderReport, $recipientReport, $senderID) {
                $q2->where('reportID', $senderReport->reportID)
                ->where('matchedReportID', $recipientReport->reportID)
                ->where('userID', $senderID);
            })->orWhere(function($q3) use ($senderReport, $recipientReport, $recipientID) {
                $q3->where('reportID', $recipientReport->reportID)
                ->where('matchedReportID', $senderReport->reportID)
                ->where('userID', $recipientID);
            });
        })->update(['matchStatus' => 'pending']);


        return redirect()->back()->with('success', 'Handover request sent!');
    }



    // ------------------ STORE HANDOVER ------------------ //
    public function store(Request $request)
    {
        $request->validate([
            'recipientReportID' => 'required|exists:item_reports,reportID',
            'senderReportID'    => 'required|exists:item_reports,reportID',
            'proofText'         => 'required|string|max:1000',
            'proofImg'          => 'nullable|image|max:2048',
        ]);

        $recipientReport = ItemReport::findOrFail($request->recipientReportID);
        $senderReport    = ItemReport::findOrFail($request->senderReportID);
        $senderID        = Auth::id();
        $recipientID     = $recipientReport->userID;

        if (!$senderReport->canHandoverWith($recipientReport, $senderID)) {

            $existingHandover = \App\Models\HandoverRequest::whereIn('requestStatus', ['Pending','Approved','Completed'])
                ->where('senderID', $senderID)
                ->where('recipientID', $recipientID)
                ->where(function($q) use ($senderReport, $recipientReport) {
                    $q->where('senderReportID', $senderReport->reportID)
                    ->orWhere('reportID', $recipientReport->reportID);
                })
                ->first();

            if ($existingHandover) {
                return redirect()->back()->with('error', 'You already have an active handover request between these items with this user.');
            }

            return redirect()->back()->with('error', 'This report cannot be used for handover with the selected item.');
        }

        $requestType = $senderReport->reportType === 'Found' ? 'Return' : 'Claim';
        $proofImgPath = $request->hasFile('proofImg') ? $request->file('proofImg')->store('handover_proofs', 'public') : null;

        HandoverRequest::create([
            'reportID'       => $recipientReport->reportID,
            'senderID'       => $senderID,
            'senderReportID' => $senderReport->reportID,
            'recipientID'    => $recipientID,
            'requestType'    => $requestType,
            'proofText'      => $request->proofText,
            'proofImg'       => $proofImgPath,
            'requestStatus'  => 'Pending',
        ]);

        // Update MatchSuggestion to 'pending' if coming from suggested matches
        \App\Models\MatchSuggestion::where(function($q) use ($senderReport, $recipientReport, $senderID, $recipientID) {
            $q->where(function($q2) use ($senderReport, $recipientReport, $senderID) {
                $q2->where('reportID', $senderReport->reportID)
                ->where('matchedReportID', $recipientReport->reportID)
                ->where('userID', $senderID);
            })->orWhere(function($q3) use ($senderReport, $recipientReport, $recipientID) {
                $q3->where('reportID', $recipientReport->reportID)
                ->where('matchedReportID', $senderReport->reportID)
                ->where('userID', $recipientID);
            });
        })->update(['matchStatus' => 'pending']);



        return redirect()->back()->with('success', 'Handover request sent!');
    }

    public function cancel($id)
    {
        $handover = HandoverRequest::findOrFail($id);

        // Only sender can cancel
        if ($handover->senderID !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Revert MatchSuggestion to 'suggested'
        \App\Models\MatchSuggestion::where(function($q) use ($handover) {
            $q->where(function($q2) use ($handover) {
                $q2->where('reportID', $handover->senderReportID)
                ->where('matchedReportID', $handover->reportID)
                ->where('userID', $handover->senderID);
            })->orWhere(function($q3) use ($handover) {
                $q3->where('reportID', $handover->reportID)
                ->where('matchedReportID', $handover->senderReportID)
                ->where('userID', $handover->recipientID);
            });
        })->update(['matchStatus' => 'suggested']);

        $handover->delete();

        return redirect()->route('handover.index')->with('success', 'Handover request suggested successfully.');
    }


    // Show one specific handover (details)
    public function show($id)
    {
        $handover = HandoverRequest::with(['report', 'sender', 'recipient'])->findOrFail($id);

        if (!in_array(Auth::id(), [$handover->senderID, $handover->recipientID])) {
            abort(403, 'Unauthorized');
        }

        return view('handover.show', compact('handover'));
    }

    // Update handover request status
    public function update(Request $request, $id)
    {
        $handover = HandoverRequest::findOrFail($id);

        if (Auth::id() !== $handover->recipientID) {
            abort(403);
        }

        $request->validate([
            'handoverStatus' => 'required|in:Pending,Approved,Rejected,Completed',
            'rejectionNote' => 'nullable|string|max:500',
        ]);

        $handover->requestStatus = $request->handoverStatus;

        if ($request->handoverStatus === 'Rejected') {
        $handover->rejectionNote = $request->rejectionNote;

        // Update matchStatus to dismissed
        \App\Models\MatchSuggestion::where('reportID', $handover->senderReportID)
            ->where('matchedReportID', $handover->reportID)
            ->where('userID', $handover->senderID)
            ->update(['matchStatus' => 'dismissed']);

        } elseif ($request->handoverStatus === 'Approved') {
            // Update matchStatus to accepted
            \App\Models\MatchSuggestion::where('reportID', $handover->senderReportID)
                ->where('matchedReportID', $handover->reportID)
                ->where('userID', $handover->senderID)
                ->update(['matchStatus' => 'accepted']);

        } elseif ($request->handoverStatus === 'Completed') {
            // Update matchStatus to completed
            \App\Models\MatchSuggestion::where('reportID', $handover->senderReportID)
                ->where('matchedReportID', $handover->reportID)
                ->where('userID', $handover->senderID)
                ->update(['matchStatus' => 'completed']);
        }

        $handover->save();

        return back()->with('success', 'Handover status updated.');
    }

    
    // ------------------ HELPER ------------------ //

    
    // ------------------ GET OPPOSITE REPORTS (AJAX) ------------------ //
    public function getOppositeReports($reportID)
    {
        $recipientReport = ItemReport::findOrFail($reportID);
        $oppositeType = $recipientReport->reportType === 'Lost' ? 'Found' : 'Lost';
        $senderID = Auth::id();

        $allReports = ItemReport::where('userID', $senderID)
            ->where('reportType', $oppositeType)
            ->where('reportStatus', 'Published')
            ->get();

        $availableReports = $allReports->filter(function($report) use ($recipientReport, $senderID) {
            return $report->canHandoverWith($recipientReport, $senderID);
        });

        if ($availableReports->isEmpty()) {
            return response()->json(['noneAvailable' => true]);
        }

        $formattedReports = $availableReports->map(function($r) {
            return [
                'reportID'   => $r->reportID,
                'itemName'   => $r->itemName,
                'reportDate' => \Carbon\Carbon::parse($r->reportDate)->format('d/m/Y'),
            ];
        });

        return response()->json($formattedReports->values());
    }
}
