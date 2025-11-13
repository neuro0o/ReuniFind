<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ItemCategory;
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

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $totalUsers = User::count();
        $totalLostReports = ItemReport::where('reportType','Lost')->count();
        $totalFoundReports = ItemReport::where('reportType','Found')->count();

        $pendingLostReports = ItemReport::where('reportType', 'Lost')
            ->where('reportStatus', 'Pending')
            ->count();

        $pendingFoundReports = ItemReport::where('reportType', 'Found')
            ->where('reportStatus', 'Pending')
            ->count();

        $publishedLostReports = ItemReport::where('reportType', 'Lost')
            ->where('reportStatus', 'Published')
            ->count();

        $publishedFoundReports = ItemReport::where('reportType', 'Found')
            ->where('reportStatus', 'Published')
            ->count();


        $unresolvedCases = ItemReport::where('reportStatus','!=','Completed')->count();
        $completedCases = ItemReport::where('reportStatus','Completed')->count();

        // Monthly reports
        $totalReportsThisMonth = ItemReport::whereMonth('reportDate',$currentMonth)
            ->whereYear('reportDate',$currentYear)->count();
        $lostReportsThisMonth = ItemReport::where('reportType','Lost')
            ->whereMonth('reportDate',$currentMonth)
            ->whereYear('reportDate',$currentYear)->count();
        $foundReportsThisMonth = ItemReport::where('reportType','Found')
            ->whereMonth('reportDate',$currentMonth)
            ->whereYear('reportDate',$currentYear)->count();

        $unresolvedCasesThisMonth = ItemReport::where('reportStatus','!=','Completed')
            ->whereMonth('reportDate',$currentMonth)
            ->whereYear('reportDate',$currentYear)->count();
        $completedCasesThisMonth = ItemReport::where('reportStatus','Completed')
            ->whereMonth('reportDate',$currentMonth)
            ->whereYear('reportDate',$currentYear)->count();


        // Top Lost Categories
        $topLostCategories = ItemCategory::withCount(['itemReports as lost_reports_count' => function($query) {
            $query->where('reportType', 'Lost');
        }])
        ->orderByDesc('lost_reports_count')
        ->take(5)
        ->get();

        // Top Found Categories
        $topFoundCategories = ItemCategory::withCount(['itemReports as found_reports_count' => function($query) {
            $query->where('reportType', 'Found');
        }])
        ->orderByDesc('found_reports_count')
        ->take(5)
        ->get();

        // Top Lost Locations
        $topLostLocations = \App\Models\ItemLocation::withCount(['lostReports as lost_reports_count'])
            ->orderByDesc('lost_reports_count')
            ->take(5)
            ->get();

        // Top Found Locations
        $topFoundLocations = \App\Models\ItemLocation::withCount(['foundReports as found_reports_count'])
            ->orderByDesc('found_reports_count')
            ->take(5)
            ->get();

        return view('admin.admin_dashboard', compact(
            'totalUsers','totalLostReports','totalFoundReports',
            'publishedLostReports','publishedFoundReports',
            'pendingLostReports','pendingFoundReports',
            'unresolvedCases','completedCases',
            'totalReportsThisMonth','lostReportsThisMonth','foundReportsThisMonth',
            'unresolvedCasesThisMonth','completedCasesThisMonth',
            'topLostCategories',
            'topFoundCategories',
            'topLostLocations',
            'topFoundLocations'
        ));

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
