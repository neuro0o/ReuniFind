<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ItemReport;
use App\Models\ItemCategory;
use App\Models\ItemLocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\MatchSuggestion;
use Carbon\Carbon;


class ItemReportController extends Controller
{   
    // -------------------- CREATE ITEM REPORT (Lost Form) -------------------- //
    public function reportLost()
    {
        $categories = ItemCategory::orderBy('categoryName')->get();
        $locations = ItemLocation::orderBy('locationName')->get();

        return view('item_report.report_lost', compact('categories', 'locations'));
    }

    // -------------------- CREATE ITEM REPORT (Found Form) -------------------- //
    public function reportFound()
    {
        $categories = ItemCategory::orderBy('categoryName')->get();
        $locations = ItemLocation::orderBy('locationName')->get();

        return view('item_report.report_found', compact('categories', 'locations'));
    }

    // -------------------- STORE ITEM REPORT -------------------- //
    public function processForm(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'reportType' => 'required|in:Lost,Found',
            'itemName' => 'required|string|max:255',
            'itemCategory' => 'required|exists:item_categories,categoryID',
            'itemDescription' => 'nullable|string',
            'itemLocation' => 'required|exists:item_locations,locationID',
            'reportDate' => 'required|date',
            'itemImg' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'verificationNote' => 'nullable|string|max:500',
            'verificationImg' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle main image upload
        $path = null;
        if ($request->hasFile('itemImg')) {
            $path = $request->file('itemImg')->store('images/items', 'public');
        }

        // Handle verification image upload
        $verificationPath = null;
        if ($request->hasFile('verificationImg')) {
            $verificationPath = $request->file('verificationImg')->store('images/verification', 'public');
        }

        // Create new report
        $report = new ItemReport();
        $report->reportType = $validated['reportType']; // Use form value
        $report->itemName = $validated['itemName'];
        $report->itemCategory = $validated['itemCategory'];
        $report->itemDescription = $validated['itemDescription'];
        $report->itemLocation = $validated['itemLocation'];

        // Combine user-selected date with current time
        $report->reportDate = Carbon::parse($validated['reportDate'])
            ->setTimeFromTimeString(now()->format('H:i:s'));
    
        $report->itemImg = $path;
        $report->verificationNote = $validated['verificationNote'] ?? null;
        $report->verificationImg = $verificationPath;
        $report->userID = Auth::user()->userID; // Assign logged-in user's ID
        $report->save(); // Save the model

        // Redirect with success message
        return redirect()->route('item_report.my_report')->with('success', 'Item report submitted successfully!');
    }


    // -------------------- READ (View All Reports) -------------------- //
    public function viewReports(Request $request)
    {
        $userID = Auth::user()->userID;

        $query = ItemReport::where('reportStatus', 'Published');

        $dismissedReportIDs = MatchSuggestion::where('userID', $userID)
            ->where('matchStatus', 'dismissed')
            ->pluck('matchedReportID')
            ->toArray();

        // Exclude own reports if not admin
        if (Auth::user()->userRole !== 'Admin') {
            $query->where('userID', '!=', $userID);
        }

        // Exclude reports involved in any pending handover (either as sender or recipient)
        $query->whereDoesntHave('sentHandoverRequests', function($q) {
            $q->where('requestStatus', 'Pending');
        })
        ->whereDoesntHave('receivedHandoverRequests', function($q) {
            $q->where('requestStatus', 'Pending');
        });

        
        // Keyword filter
        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('itemName', 'like', '%' . $request->keyword . '%')
                ->orWhere('itemDescription', 'like', '%' . $request->keyword . '%');
            });
        }

        // Status filter (Lost / Found / All)
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('reportType', ucfirst($request->status));
        }

        // Category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('itemCategory', $request->category); // categoryID
        }

        $reports = $query->orderBy('reportDate', 'desc')->get();

        $statusEnum = $this->getEnumValues('item_reports', 'reportType');
        $categories = ItemCategory::orderBy('categoryName')->get();

        return view('item_report.view', compact('reports', 'statusEnum', 'categories'));
    }



    // -------------------- READ (User's Reports) -------------------- //
    public function myReports(Request $request)
    {
        $status = $request->query('status'); 

        $query = ItemReport::where('userID', Auth::user()->userID)
            ->orderBy('reportDate', 'desc');

        $enumStatusMap = [
            'pending' => 'Pending',
            'published' => 'Published',
            'rejected' => 'Rejected',
            'completed' => 'Completed',
        ];

        // Default to 'pending' if $status is not set
        $statusKey = strtolower($status ?? 'pending');

        if (isset($enumStatusMap[$statusKey])) {
            $query->where('reportStatus', $enumStatusMap[$statusKey]);
        }

        $userReports = $query->get();


        return view('item_report.my_report', compact('userReports', 'status'));
    }

    // -------------------- EDIT ITEM REPORT -------------------- //
    public function edit($id)
    {
        $report = ItemReport::findOrFail($id);

        // Restrict access so only the owner can edit
        if ($report->userID !== Auth::user()->userID) {
            abort(403, 'Unauthorized action.');
        }

        $categories = ItemCategory::orderBy('categoryName')->get();
        $locations = ItemLocation::orderBy('locationName')->get();

        return view('item_report.edit', compact('report', 'categories', 'locations'));
    }

    // -------------------- UPDATE ITEM REPORT -------------------- //
    public function update(Request $request, $id)
    {
        $report = ItemReport::findOrFail($id);

        // Restrict access so only the owner can update
        if ($report->userID !== Auth::user()->userID) {
            abort(403, 'Unauthorized action.');
        }

        // Validate input
        $validated = $request->validate([
            'reportType' => 'required|in:Lost,Found',
            'itemName' => 'required|string|max:255',
            'itemCategory' => 'required|exists:item_categories,categoryID',
            'itemDescription' => 'nullable|string',
            'itemLocation' => 'required|exists:item_locations,locationID',
            'reportDate' => 'required|date',
            'itemImg' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'verificationNote' => 'nullable|string|max:500',
            'verificationImg' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update main image if new file uploaded
        if ($request->hasFile('itemImg')) {
            if ($report->itemImg && Storage::disk('public')->exists($report->itemImg)) {
                Storage::disk('public')->delete($report->itemImg);
            }
            $report->itemImg = $request->file('itemImg')->store('images/items', 'public');
        }

        // Update verification image if new file uploaded
        if ($request->hasFile('verificationImg')) {
            if ($report->verificationImg && Storage::disk('public')->exists($report->verificationImg)) {
                Storage::disk('public')->delete($report->verificationImg);
            }
            $report->verificationImg = $request->file('verificationImg')->store('images/verification', 'public');
        }

        // Update other fields, including verification note
        $report->reportType = $validated['reportType'];
        $report->itemName = $validated['itemName'];
        $report->itemCategory = $validated['itemCategory'];
        $report->itemDescription = $validated['itemDescription'];
        $report->itemLocation = $validated['itemLocation'];

        // Combine user-selected date with current time
        $report->reportDate = Carbon::parse($validated['reportDate'])
            ->setTimeFromTimeString(now()->format('H:i:s'));
            
        $report->verificationNote = $validated['verificationNote'] ?? null;

        $report->save();

        return redirect()->route('item_report.my_report')->with('success', 'Report updated successfully!');
    }


    // -------------------- DELETE -------------------- //
    public function destroy($id)
    {
        $report = ItemReport::findOrFail($id);
        $this->authorizeUser($report);

        // Delete the file
        if ($report->itemImg && Storage::disk('public')->exists($report->itemImg)) {
            Storage::disk('public')->delete($report->itemImg);
        }

        $report->delete();

        return redirect()->route('item_report.my_report')->with('success', 'Report deleted successfully.');
    }

    /*
    * Check opposite item report type
    * Check if eligible for handover (continue) or not (reject)
    * Check matching words from itemName and itemDescription
    */
    // -------------------- DISPLAY MATCH SUGGESTIONS (AUTO) -------------------- //
    public function matchmaking()
    {
        $userID = Auth::user()->userID;
        $userReports = ItemReport::where('userID', $userID)->get();

        // If user has no reports
        if ($userReports->isEmpty()) {
            return view('item_report.suggested_matches', [
                'matchesByStatus' => [],
                'message' => 'You have no reports yet.'
            ]);
        }

        // --- MAIN MATCHING LOGIC ---
        foreach ($userReports as $report) {
            // 1. Check opposite report type
            $oppositeType = $report->reportType === 'Lost' ? 'Found' : 'Lost';

            // Get all possible matches from other users
            $potentialMatches = ItemReport::where('reportType', $oppositeType)
                ->where('userID', '!=', $userID)
                ->get();

            // Extract all words from this report
            $reportKeywords = array_filter(
                preg_split('/\s+/', strtolower($report->itemName . ' ' . ($report->itemDescription ?? '')))
            );            

            foreach ($potentialMatches as $match) {
                // 2. Check if eligible for handover (not dismissed, not used)
                if (!$report->canHandoverWith($match, $userID)) continue;

                // 3️. Check matching words (simple word equality)
                $matchWords = array_filter(
                    preg_split('/\s+/', strtolower($match->itemName . ' ' . ($match->itemDescription ?? '')))
                );

                $hasCommonWord = false;
                foreach ($reportKeywords as $keyword) {
                    if (strlen($keyword) < 3) continue; // ignore tiny words like "a", "to", etc.
                    if (in_array($keyword, $matchWords)) {
                        $hasCommonWord = true;
                        break;
                    }
                }

                if ($hasCommonWord) {
                    // Avoid duplicate entries
                    $exists = MatchSuggestion::where('reportID', $report->reportID)
                        ->where('matchedReportID', $match->reportID)
                        ->exists();

                    if (!$exists) {
                        MatchSuggestion::create([
                            'reportID' => $report->reportID,
                            'matchedReportID' => $match->reportID,
                            'userID' => $userID,
                            'matchStatus' => 'suggested',
                            'matchedAt' => now(),
                        ]);
                    }
                }
            }
        }

        // -------------------- FETCH SUGGESTED MATCHES BY STATUS -------------------- //
        $allStatuses = ['suggested', 'pending', 'accepted', 'dismissed', 'completed'];
        $matchesByStatus = [];

        foreach ($allStatuses as $status) {
            $matchesByStatus[$status] = MatchSuggestion::with(['report', 'matchedReport', 'user'])
                ->whereIn('reportID', $userReports->pluck('reportID'))
                ->where('matchStatus', $status)
                ->latest('matchedAt')
                ->get();
        }

        return view('item_report.suggested_matches', [
            'matchesByStatus' => $matchesByStatus
        ]);
    }


    // -------------------- DISMISS MATCH -------------------- //
    public function dismissMatch($id)
    {
        $match = \App\Models\MatchSuggestion::findOrFail($id);
        $match->update(['matchStatus' => 'dismissed']);

        return redirect()->back()->with('success', 'Match dismissed successfully.');
    }

    // -------------------- UNDO DISMISS MATCH -------------------- //
    public function undoDismiss($id)
    {
        $match = \App\Models\MatchSuggestion::findOrFail($id);

        // Only allow undo if currently dismissed
        if ($match->matchStatus === 'dismissed') {
            $match->update(['matchStatus' => 'suggested']);
            return redirect()->back()->with('success', 'Dismissed match has been restored.');
        }

        return redirect()->back()->with('error', 'Cannot undo this match.');
    }


    // -------------------- HELPER -------------------- //
    private function getEnumValues($table, $column)
    {
        $type = DB::select("SHOW COLUMNS FROM {$table} WHERE Field = '{$column}'")[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);

        return collect(explode(',', $matches[1]))->map(fn($v) => trim($v, "'"))->toArray();
    }

    private function authorizeUser($report)
    {
        if ($report->userID !== Auth::user()->userID) {
            abort(403, 'Unauthorized action.');
        }
    }

    
    // temp
    public function reportMatchmaking()
    {
        return view('item_report.report_matchmaking');
    }
}