<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\HandoverRequest;
use App\Models\ItemReport;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

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

        // Update BOTH users' match suggestions to pending
        \App\Models\MatchSuggestion::where(function($q) use ($eligibleSenderReport, $recipientReport) {
            $q->where(function($q2) use ($eligibleSenderReport, $recipientReport) {
                $q2->where('reportID', $eligibleSenderReport->reportID)
                   ->where('matchedReportID', $recipientReport->reportID);
            })->orWhere(function($q3) use ($eligibleSenderReport, $recipientReport) {
                $q3->where('reportID', $recipientReport->reportID)
                   ->where('matchedReportID', $eligibleSenderReport->reportID);
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

        // Update BOTH users' match suggestions to pending
        \App\Models\MatchSuggestion::where(function($q) use ($senderReport, $recipientReport) {
            $q->where(function($q2) use ($senderReport, $recipientReport) {
                $q2->where('reportID', $senderReport->reportID)
                   ->where('matchedReportID', $recipientReport->reportID);
            })->orWhere(function($q3) use ($senderReport, $recipientReport) {
                $q3->where('reportID', $recipientReport->reportID)
                   ->where('matchedReportID', $senderReport->reportID);
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

        // Revert BOTH users' match suggestions to 'suggested'
        \App\Models\MatchSuggestion::where(function($q) use ($handover) {
            $q->where(function($q2) use ($handover) {
                $q2->where('reportID', $handover->senderReportID)
                   ->where('matchedReportID', $handover->reportID);
            })->orWhere(function($q3) use ($handover) {
                $q3->where('reportID', $handover->reportID)
                   ->where('matchedReportID', $handover->senderReportID);
            });
        })->update(['matchStatus' => 'suggested']);

        $handover->delete();

        return redirect()->route('handover.index')->with('success', 'Handover request cancelled successfully.');
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

            // Update BOTH users' match suggestions to 'rejected'
            \App\Models\MatchSuggestion::where(function($q) use ($handover) {
                $q->where(function($q2) use ($handover) {
                    $q2->where('reportID', $handover->senderReportID)
                       ->where('matchedReportID', $handover->reportID);
                })->orWhere(function($q3) use ($handover) {
                    $q3->where('reportID', $handover->reportID)
                       ->where('matchedReportID', $handover->senderReportID);
                });
            })->update(['matchStatus' => 'rejected']);

        } elseif ($request->handoverStatus === 'Approved') {
            // Update BOTH users' match suggestions to accepted
            \App\Models\MatchSuggestion::where(function($q) use ($handover) {
                $q->where(function($q2) use ($handover) {
                    $q2->where('reportID', $handover->senderReportID)
                       ->where('matchedReportID', $handover->reportID);
                })->orWhere(function($q3) use ($handover) {
                    $q3->where('reportID', $handover->reportID)
                       ->where('matchedReportID', $handover->senderReportID);
                });
            })->update(['matchStatus' => 'accepted']);

        } elseif ($request->handoverStatus === 'Completed') {
            // Update BOTH users' match suggestions to completed
            \App\Models\MatchSuggestion::where(function($q) use ($handover) {
                $q->where(function($q2) use ($handover) {
                    $q2->where('reportID', $handover->senderReportID)
                       ->where('matchedReportID', $handover->reportID);
                })->orWhere(function($q3) use ($handover) {
                    $q3->where('reportID', $handover->reportID)
                       ->where('matchedReportID', $handover->senderReportID);
                });
            })->update(['matchStatus' => 'completed']);

            // Update BOTH item reports to Completed
            ItemReport::whereIn('reportID', [$handover->reportID, $handover->senderReportID])
                ->update(['reportStatus' => 'Completed']);
        }

        $handover->save();

        return back()->with('success', 'Handover status updated.');
    }

    /**
     * Download handover form PDF (blank template with item details)
     */
    public function downloadHandoverForm($requestID)
    {
        $handover = HandoverRequest::with(['sender', 'recipient', 'report.category', 'report.location'])
            ->findOrFail($requestID);
        
        $userId = Auth::id();
        $isAdmin = Auth::user()->userRole === 'Admin';
        
        // Verify user is part of this handover OR is admin
        if ($handover->senderID !== $userId && $handover->recipientID !== $userId && !$isAdmin) {
            abort(403, 'Unauthorized');
        }

        // Must be approved to download form
        if ($handover->requestStatus !== 'Approved' && $handover->requestStatus !== 'Completed') {
            return back()->with('error', 'Handover form can only be downloaded for approved/completed requests.');
        }
        
        // Determine who is finder and who is owner based on request type
        if ($handover->requestType === 'Claim') {
            // Sender is claiming (owner), Recipient found the item (finder)
            $finder = $handover->recipient;
            $owner = $handover->sender;
        } else {
            // Return: Sender is returning (finder), Recipient lost the item (owner)
            $finder = $handover->sender;
            $owner = $handover->recipient;
        }
        
        $data = [
            'handover' => $handover,
            'report' => $handover->report,
            'finder' => $finder,
            'owner' => $owner,
            'dateClaimed' => now()->format('d/m/Y'),
        ];
        
        $pdf = Pdf::loadView('handover.handover_form_pdf', $data);
        
        return $pdf->download('ReuniFind_Handover_Form_' . $handover->report->itemName . '.pdf');
    }

    /**
     * Upload signed handover form (auto-completes handover)
     */
    public function uploadHandoverForm(Request $request, $requestID)
    {
        $handover = HandoverRequest::findOrFail($requestID);
        $userId = Auth::id();
        
        // Verify user is part of this handover
        if ($handover->senderID !== $userId && $handover->recipientID !== $userId) {
            abort(403, 'Unauthorized');
        }

        // Must be approved to upload form
        if ($handover->requestStatus !== 'Approved') {
            return back()->with('error', 'Handover form can only be uploaded for approved requests.');
        }
        
        // Validate the uploaded file
        $request->validate([
            'handoverForm' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ]);
        
        // Delete old form if exists
        if ($handover->handoverForm) {
            Storage::disk('public')->delete($handover->handoverForm);
        }
        
        // Store the new form
        $path = $request->file('handoverForm')->store('handover_forms', 'public');
        
        // Update handover with form path and mark as completed
        $handover->update([
            'handoverForm' => $path,
            'requestStatus' => 'Completed',
        ]);

        // Update BOTH users' match suggestions to completed
        \App\Models\MatchSuggestion::where(function($q) use ($handover) {
            $q->where(function($q2) use ($handover) {
                $q2->where('reportID', $handover->senderReportID)
                   ->where('matchedReportID', $handover->reportID);
            })->orWhere(function($q3) use ($handover) {
                $q3->where('reportID', $handover->reportID)
                   ->where('matchedReportID', $handover->senderReportID);
            });
        })->update(['matchStatus' => 'completed']);

        // Update BOTH item reports to Completed
        ItemReport::whereIn('reportID', [$handover->reportID, $handover->senderReportID])
            ->update(['reportStatus' => 'Completed']);
        
        return back()->with('success', 'Handover form uploaded successfully! Handover marked as completed.');
    }

    /**
     * View uploaded handover form (inline in browser - NEW)
     */
    public function viewHandoverForm($requestID)
    {
        $handover = HandoverRequest::findOrFail($requestID);
        $userId = Auth::id();
        $isAdmin = Auth::user()->userRole === 'Admin';
        
        // Verify user is part of this handover OR is admin
        if ($handover->senderID !== $userId && $handover->recipientID !== $userId && !$isAdmin) {
            abort(403, 'Unauthorized');
        }
        
        if (!$handover->handoverForm) {
            return back()->with('error', 'No handover form has been uploaded yet.');
        }
        
        $filePath = storage_path('app/public/' . $handover->handoverForm);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'Form file not found.');
        }
        
        // Return file inline (opens in browser)
        return response()->file($filePath);
    }

    /**
     * Download uploaded handover form (force download - NEW)
     */
    public function downloadUploadedForm($requestID)
    {
        $handover = HandoverRequest::findOrFail($requestID);
        $userId = Auth::id();
        $isAdmin = Auth::user()->userRole === 'Admin';
        
        // Verify user is part of this handover OR is admin
        if ($handover->senderID !== $userId && $handover->recipientID !== $userId && !$isAdmin) {
            abort(403, 'Unauthorized');
        }
        
        if (!$handover->handoverForm) {
            return back()->with('error', 'No handover form has been uploaded yet.');
        }
        
        return Storage::disk('public')->download(
            $handover->handoverForm,
            'Handover_Form_' . $handover->requestID . '.pdf'
        );
    }

    
    // ------------------ HELPER ------------------ //

    
    // ------------------ GET OPPOSITE REPORTS (AJAX) ------------------ //
    public function getOppositeReports($reportID)
    {
        $recipientReport = ItemReport::findOrFail($reportID);
        $oppositeType = $recipientReport->reportType === 'Lost' ? 'Found' : 'Lost';
        $senderID = Auth::id();

        // Get user's reports of opposite type, excluding those in active handovers
        $allReports = ItemReport::where('userID', $senderID)
            ->where('reportType', $oppositeType)
            ->where('reportStatus', 'Published')
            // EXCLUDE reports already in active handovers (Pending, Approved, Completed)
            ->whereDoesntHave('sentHandoverRequests', function($q) {
                $q->whereIn('requestStatus', ['Pending', 'Approved', 'Completed']);
            })
            ->whereDoesntHave('receivedHandoverRequests', function($q) {
                $q->whereIn('requestStatus', ['Pending', 'Approved', 'Completed']);
            })
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
