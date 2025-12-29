<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ItemCategory;
use App\Models\ItemReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    // -------------------- User Management -------------------- //
    /**
     * Show all users (optional — for future feature).
     */
    public function manageUsers()
    {
        if (Auth::user()->userRole !== 'Admin') {
            abort(403, 'Unauthorized access.');
        }

        $users = User::orderBy('userID', 'asc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'userName' => 'required|string|max:255',
            'userEmail' => 'required|email|max:255|unique:users,userEmail',
            'password' => 'required|min:3|confirmed',
            'contactInfo' => 'nullable|string|max:50',
            'userRole' => 'required|in:Admin,User',
            'profileImg' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('profileImg')) {
            $validated['profileImg'] = $request->file('profileImg')
                ->store('/images/profiles', 'public');
        }

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'New user created successfully.');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'userName' => 'required|string|max:255',
            'userEmail' => 'required|email|max:255|unique:users,userEmail,' . $id . ',userID',
            'password' => 'nullable|min:3|confirmed',
            'contactInfo' => 'nullable|string|max:50',
            'userRole' => 'required|in:Admin,User',
            'profileImg' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('profileImg')) {
            if ($user->profileImg && Storage::disk('public')->exists($user->profileImg)) {
                Storage::disk('public')->delete($user->profileImg);
            }

            $validated['profileImg'] = $request->file('profileImg')
                ->store('/images/profiles', 'public');
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function resetProfile($id)
    {
        $user = User::findOrFail($id);
        
        // Delete the uploaded file if exists
        if ($user->profileImg && Storage::exists($user->profileImg)) {
            Storage::delete($user->profileImg);
        }
        
        $user->profileImg = null;
        $user->save();

        return back()->with('success', 'Profile image reset to default.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->userRole === 'Admin') {
            return back()->with('error', 'Admin accounts cannot be deleted.');
        }

        if ($user->profileImg && Storage::disk('public')->exists($user->profileImg)) {
            Storage::disk('public')->delete($user->profileImg);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    // -------------------- Item Report -------------------- //
    // Show Lost reports with optional status filter
    public function manageLostReports(Request $request)
    {
        $query = ItemReport::where('reportType', 'Lost');

        // Filter by status (optional)
        if ($request->has('status') && $request->status != '') {
            $query->where('reportStatus', ucfirst($request->status));
        }

        $lostReports = $query->orderBy('reportDate', 'desc')->get();

        return view('admin.manage_report_lost', [
            'lostReports' => $lostReports,
            'status' => $request->status,
        ]);
    }


    // Show Found reports with optional status filter
    public function manageFoundReports(Request $request)
    {
        $query = ItemReport::where('reportType', 'Found');

        // Filter by status (optional)
        if ($request->has('status') && $request->status != '') {
            $query->where('reportStatus', ucfirst($request->status));
        }

        $foundReports = $query->orderBy('reportDate', 'desc')->get();

        return view('admin.manage_report_found', [
            'foundReports' => $foundReports,
            'status' => $request->status,
        ]);
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

    // Delete Report (for non-completed reports)
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

    /**
     * Delete completed report pair (both reports in the handover)
     * This is used specifically for completed reports to delete both paired reports
     */
    public function deleteCompletedReportPair($reportID)
    {
        // Find the handover request for this report
        $handover = \App\Models\HandoverRequest::where(function ($query) use ($reportID) {
            $query->where('reportID', $reportID)
                  ->orWhere('senderReportID', $reportID);
        })
        ->where('requestStatus', 'Completed')
        ->first();

        if (!$handover) {
            return redirect()->back()->with('error', 'No completed handover found for this report.');
        }

        // Get both report IDs
        $reportID1 = $handover->reportID;
        $reportID2 = $handover->senderReportID;

        // Get the report type for redirect
        $report = ItemReport::find($reportID);
        $reportType = $report ? $report->reportType : 'Lost';

        // Delete the handover form file if it exists
        if ($handover->handoverForm) {
            Storage::disk('public')->delete($handover->handoverForm);
        }

        // Delete match suggestions for this pair
        \App\Models\MatchSuggestion::where(function($q) use ($reportID1, $reportID2) {
            $q->where(function($q2) use ($reportID1, $reportID2) {
                $q2->where('reportID', $reportID1)
                   ->where('matchedReportID', $reportID2);
            })->orWhere(function($q3) use ($reportID1, $reportID2) {
                $q3->where('reportID', $reportID2)
                   ->where('matchedReportID', $reportID1);
            });
        })->delete();

        // Delete the handover request
        $handover->delete();

        // Delete BOTH item reports
        ItemReport::whereIn('reportID', [$reportID1, $reportID2])->delete();

        // Redirect to appropriate list based on original report type
        if ($reportType === 'Lost') {
            return redirect()->route('admin.manage_report_lost')
                ->with('success', 'Both reports in the handover pair have been deleted successfully.');
        } else {
            return redirect()->route('admin.manage_report_found')
                ->with('success', 'Both reports in the handover pair have been deleted successfully.');
        }
    }

    // View detailed report info
    public function showReport($id)
    {
        $report = ItemReport::findOrFail($id);
        return view('admin.manage_report_detail', compact('report'));
    }



    // -------------------- FEEDBACK CONTROLLER -------------------- //

    /**
     * Display all feedbacks with filters
     */
    public function feedbacks(Request $request)
    {
        $query = \App\Models\Feedback::with('user');

        // Filter by feedback type
        if ($request->has('type') && $request->type != '') {
            $query->where('feedbackType', $request->type);
        }

        // Filter by feedback status
        if ($request->has('status') && $request->status != '') {
            $query->where('feedbackStatus', $request->status);
        }

        // Order by most recent
        $feedbacks = $query->orderBy('feedbackDate', 'desc')->paginate(15);

        return view('admin.feedbacks.index', compact('feedbacks'));
    }

    /**
     * Mark feedback as reviewed
     */
    public function markAsReviewed($id)
    {
        $feedback = \App\Models\Feedback::findOrFail($id);
        $feedback->update(['feedbackStatus' => 'Reviewed']);

        return redirect()->back()->with('success', 'Feedback marked as reviewed.');
    }

    /**
     * Delete feedback
     */
    public function deleteFeedback($id)
    {
        $feedback = \App\Models\Feedback::findOrFail($id);
        $feedback->delete();

        return redirect()->back()->with('success', 'Feedback deleted successfully.');
    }


    // ==================== FAQ CONTROLLER ====================

    /**
     * Display all FAQs
     */
    public function faqs()
    {
        $faqs = \App\Models\FAQ::orderBy('created_at', 'desc')->get();
        return view('admin.faqs.index', compact('faqs'));
    }

    /**
     * Show create FAQ form
     */
    public function createFaq()
    {
        return view('admin.faqs.create');
    }

    /**
     * Store new FAQ
     */
    public function storeFaq(Request $request)
    {
        $validated = $request->validate([
            'faqQuestion' => 'required|string|max:255',
            'faqAnswer' => 'required|string|max:1000',
        ]);

        \App\Models\FAQ::create($validated);

        return redirect()->route('admin.faqs')
            ->with('success', 'FAQ created successfully!');
    }

    /**
     * Show edit FAQ form
     */
    public function editFaq($id)
    {
        $faq = \App\Models\FAQ::findOrFail($id);
        return view('admin.faqs.edit', compact('faq'));
    }

    /**
     * Update FAQ
     */
    public function updateFaq(Request $request, $id)
    {
        $faq = \App\Models\FAQ::findOrFail($id);

        $validated = $request->validate([
            'faqQuestion' => 'required|string|max:255',
            'faqAnswer' => 'required|string|max:1000',
        ]);

        $faq->update($validated);

        return redirect()->route('admin.faqs')
            ->with('success', 'FAQ updated successfully!');
    }

    /**
     * Delete FAQ
     */
    public function deleteFaq($id)
    {
        $faq = \App\Models\FAQ::findOrFail($id);
        $faq->delete();

        return redirect()->route('admin.faqs')
            ->with('success', 'FAQ deleted successfully!');
    }


    // -------------------- Helper -------------------- //
    private function getEnumValues($table, $column)
    {
        $type = DB::select("SHOW COLUMNS FROM {$table} WHERE Field = '{$column}'")[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);

        return collect(explode(',', $matches[1]))->map(fn($v) => trim($v, "'"))->toArray();
    }
}