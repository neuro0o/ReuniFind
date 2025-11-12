<?php

namespace App\Http\Controllers;


use App\Models\ItemReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminController extends Controller
{
    // -------------------- Show Admin Dashboard -------------------- //
    public function dashboard()
    {
        if (Auth::user()->userRole !== 'Admin') {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.admin_dashboard');
    }

    /**
     * Show all users (optional — for future feature).
     */
    public function manageUsers()
    {
        if (Auth::user()->userRole !== 'Admin') {
            abort(403, 'Unauthorized access.');
        }

        $users = \App\Models\User::all();
        return view('admin.users.index', compact('users'));
    }

    // -------------------- Item Category -------------------- //
    


    // -------------------- Item Report -------------------- //
    // Show Lost reports with optional status filter (case-sensitive)
    public function manageLostReports(Request $request)
    {
        $status = $request->query('status'); // may be null

        // Default to Pending if not set
        if (!$status) {
            $status = 'pending';
        }

        $statusEnum = ucfirst(strtolower($status)); // Match enum exactly

        $lostReports = ItemReport::where('reportType', 'Lost')
                        ->where('reportStatus', $statusEnum)
                        ->orderBy('reportDate', 'desc')
                        ->get();

        return view('admin.manage_report_lost', compact('lostReports', 'status'));
    }


    // Show Found reports with optional status filter (case-sensitive)
    public function manageFoundReports(Request $request)
    {
        $status = $request->query('status');

        if (!$status) {
            $status = 'pending';
        }

        $statusEnum = ucfirst(strtolower($status));

        $foundReports = ItemReport::where('reportType', 'Found')
                        ->where('reportStatus', $statusEnum)
                        ->orderBy('reportDate', 'desc')
                        ->get();

        return view('admin.manage_report_found', compact('foundReports', 'status'));
    }
    
    // Approve report
    public function approveReport($id)
    {
        $report = ItemReport::findOrFail($id);
        $report->reportStatus = 'Published';
        $report->rejectionNote = null;
        $report->save();

        // Redirect to appropriate list
        if ($report->reportType === 'Lost') {
            return redirect()->route('admin.manage_report_lost')
                ->with('success', 'Report has been approved and published.');
        } else {
            return redirect()->route('admin.manage_report_found')
                ->with('success', 'Report has been approved and published.');
        }
    }

    // Reject report
    public function rejectReport(Request $request, $id)
    {
        $report = ItemReport::findOrFail($id);
        $report->reportStatus = 'Rejected';
        $report->rejectionNote = $request->input('rejectionNote');
        $report->save();

       // Redirect to appropriate list
        if ($report->reportType === 'Lost') {
            return redirect()->route('admin.manage_report_lost')
                ->with('error', 'Report has been rejected.');
        } else {
            return redirect()->route('admin.manage_report_found')
                ->with('error', 'Report has been rejected.');
        }
    }

    // Delete Report
    public function deleteReport($id)
    {
        $report = ItemReport::findOrFail($id);
        $report->delete();

        if ($report->reportType === 'Lost') {
            return redirect()->route('admin.manage_report_lost')
                ->with('success', 'Report deleted successfully.');
        } else {
            return redirect()->route('admin.manage_report_found')
                ->with('success', 'Report deleted successfully.');
        }
    }

    // View detailed report info
    public function showReport($id)
    {
        $report = ItemReport::findOrFail($id);
        return view('admin.manage_report_detail', compact('report'));
    }


    // -------------------- Helper -------------------- //
    private function getEnumValues($table, $column)
    {
        $type = DB::select("SHOW COLUMNS FROM {$table} WHERE Field = '{$column}'")[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);

        return collect(explode(',', $matches[1]))->map(fn($v) => trim($v, "'"))->toArray();
    }
}
