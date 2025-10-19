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

    // -------------------- STORE ITEM REPORT -------------------- //
    public function processForm(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'itemName' => 'required|string|max:255',
            'itemCategory' => 'required|string',
            'itemDescription' => 'nullable|string|max:255',
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
        $report->reportType = 'Lost';
        $report->itemName = $validated['itemName'];
        $report->itemCategory = $validated['itemCategory'];
        $report->itemDescription = $validated['itemDescription'];
        $report->itemLocation = $validated['itemLocation'];
        $report->reportDate = $validated['reportDate'];
        $report->itemImg = $path;
        $report->userID = auth()->user()->userID; // Assign logged-in user's ID
        $report->save(); // Save the model

        // Redirect with success message
        return redirect()->route('item_report.my_report')->with('success', 'Item report submitted successfully!');
    }


    // -------------------- READ (View All Reports) -------------------- //
    public function viewReports()
    {
        $reports = ItemReport::with('user')->latest()->get();
        return view('item_report.view', compact('reports'));
    }

    // -------------------- READ (User’s Reports) -------------------- //
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
        $this->authorizeUser($report);

        $categoryEnum = $this->getEnumValues('item_reports', 'itemCategory');
        $locationEnum = $this->getEnumValues('item_reports', 'itemLocation');

        return view('item_report.edit', compact('report', 'categoryEnum', 'locationEnum'));
    }

    // -------------------- UPDATE ITEM REPORT -------------------- //
    public function update(Request $request, $id)
    {
        $report = ItemReport::findOrFail($id);
        $this->authorizeUser($report);

        $request->validate([
            'itemName' => 'required|string|max:255',
            'itemCategory' => 'required',
            'itemDescription' => 'required|string',
            'itemLocation' => 'required',
            'reportDate' => 'required|date',
            'itemImg' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('itemImg')) {
            if ($report->itemImg) {
                Storage::disk('public')->delete($report->itemImg);
            }
            $report->itemImg = $request->file('itemImg')->store('images/items', 'public');
        }

        $report->update($request->except(['_token', '_method']));

        return redirect()->route('item_report.my_report')->with('success', 'Report updated successfully.');
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
