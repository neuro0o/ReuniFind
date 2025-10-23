<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ItemReport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemReportController extends Controller
{   
    // -------------------- CREATE ITEM REPORT (Lost Form) -------------------- //
    public function reportLost()
    {
        $categoryEnum = $this->getEnumValues('item_reports', 'itemCategory');
        $locationEnum = $this->getEnumValues('item_reports', 'itemLocation');

        return view('item_report.report_lost', compact('categoryEnum', 'locationEnum'));
    }

    // -------------------- CREATE ITEM REPORT (Found Form) -------------------- //
    public function reportFound()
    {
        $categoryEnum = $this->getEnumValues('item_reports', 'itemCategory');
        $locationEnum = $this->getEnumValues('item_reports', 'itemLocation');

        return view('item_report.report_found', compact('categoryEnum', 'locationEnum'));
    }

    // -------------------- STORE ITEM REPORT -------------------- //
    public function processForm(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'reportType' => 'required|in:Lost,Found',
            'itemName' => 'required|string|max:255',
            'itemCategory' => 'required|string',
            'itemDescription' => 'nullable|string',
            'itemLocation' => 'required|string',
            'reportDate' => 'required|date',
            'itemImg' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle file upload
        $path = null;
        if ($request->hasFile('itemImg')) {
            $path = $request->file('itemImg')->store('images/items', 'public');
        }

        // Create new report
        $report = new ItemReport();
        $report->reportType = $validated['reportType']; // Use form value
        $report->itemName = $validated['itemName'];
        $report->itemCategory = $validated['itemCategory'];
        $report->itemDescription = $validated['itemDescription'];
        $report->itemLocation = $validated['itemLocation'];
        $report->reportDate = $validated['reportDate'];
        $report->itemImg = $path;
        $report->userID = Auth::user()->userID; // Assign logged-in user's ID
        $report->save(); // Save the model

        // Redirect with success message
        return redirect()->route('item_report.my_report')->with('success', 'Item report submitted successfully!');
    }


    // -------------------- READ (View All Reports) -------------------- //
    public function viewReports(Request $request)
    {
        $query = ItemReport::query();

        // Keyword Filter
        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('itemName', 'like', '%' . $request->keyword . '%')
                ->orWhere('itemDescription', 'like', '%' . $request->keyword . '%');
            });
        }

        // Status Filter (Lost / Found / All)
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('reportType', ucfirst($request->status));
        }

        // Category Filter
        if ($request->filled('category') && $request->category !== 'all' && $request->category !== '') {
            $query->where('itemCategory', ucfirst($request->category));
        }

        // Get all reports
        $reports = $query->orderBy('reportDate', 'desc')->get();

        // Get ENUM options from database
        $statusEnum = $this->getEnumValues('item_reports', 'reportType');
        $categoryEnum = $this->getEnumValues('item_reports', 'itemCategory');

        // Pass everything to Blade View
        return view('item_report.view', compact('reports', 'statusEnum', 'categoryEnum'));
    }

    public function show($id)
    {
        $report = ItemReport::findOrFail($id);
        return view('user.report_details', compact('report'));
    }


    // -------------------- READ (User's Reports) -------------------- //
    public function myReports()
    {
        $userReports = ItemReport::where('userID', Auth::user()->userID)
        ->orderBy('reportDate', 'desc')
        ->get();

        return view('item_report.my_report', compact('userReports'));
    }

    // -------------------- EDIT ITEM REPORT -------------------- //
    public function edit($id)
    {
        $report = ItemReport::findOrFail($id);

        // Restrict access so only the owner can edit
        if ($report->userID !== Auth::user()->userID) {
            abort(403, 'Unauthorized action.');
        }

        $categoryEnum = $this->getEnumValues('item_reports', 'itemCategory');
        $locationEnum = $this->getEnumValues('item_reports', 'itemLocation');

        return view('item_report.edit', compact('report', 'categoryEnum', 'locationEnum'));
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
            'itemCategory' => 'required|string',
            'itemDescription' => 'nullable|string',
            'itemLocation' => 'required|string',
            'reportDate' => 'required|date',
            'itemImg' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle image update
        if ($request->hasFile('itemImg')) {
            // Delete old image if exists
            if ($report->itemImg && Storage::disk('public')->exists($report->itemImg)) {
                Storage::disk('public')->delete($report->itemImg);
            }

            $path = $request->file('itemImg')->store('images/items', 'public');
            $validated['itemImg'] = $path;
        }

        $report->update($validated);

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

    // -------------------- Helper -------------------- //
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
    public function showLostForm()
    {
        return view('item_report.report_lost');
    }

    public function showFoundForm()
    {
        return view('item_report.report_found');
    }

    public function reportMatchmaking()
    {
        return view('item_report.report_matchmaking');
    }
}
