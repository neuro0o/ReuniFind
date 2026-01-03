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

// PDF Generation Libraries
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    // -------------------- Show Admin Dashboard -------------------- //
    public function dashboard()
    {
        if (Auth::user()->userRole !== 'Admin') {
            abort(403, 'Unauthorized access.');
        }

        $dashboardData = $this->getAdminDashboardData();
        
        return view('admin.admin_dashboard', $dashboardData);
    }

    // -------------------- Export Dashboard as PDF -------------------- //
    public function exportDashboardPDF()
    {
        if (Auth::user()->userRole !== 'Admin') {
            abort(403, 'Unauthorized access.');
        }

        $dashboardData = $this->getAdminDashboardData();
        
        // Generate PDF
        $pdf = Pdf::loadView('admin.dashboard_pdf', $dashboardData);
        
        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');
        
        // Generate filename with current date
        $filename = 'ReuniFind_Analytics_Report_' . now()->format('Y-m-d') . '.pdf';
        
        // Download PDF
        return $pdf->download($filename);
    }

    // -------------------- Helper: Get Dashboard Data -------------------- //
    private function getAdminDashboardData()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Overall System Summary
        $totalUsers = User::count();
        $totalLostReports = ItemReport::where('reportType', 'Lost')->count();
        $totalFoundReports = ItemReport::where('reportType', 'Found')->count();

        // Report Status Breakdown
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

        // Unresolved Cases: Active published reports in the system
        $unresolvedCases = ItemReport::where('reportStatus', 'Published')->count();
        
        // Completed Cases: Successfully reunited item pairs
        $completedCases = \App\Models\HandoverRequest::where('requestStatus', 'Completed')->count();

        // Success Rate: Handover completion rate
        $totalHandoverAttempts = \App\Models\HandoverRequest::count();
        $successRate = $totalHandoverAttempts > 0 
            ? round(($completedCases / $totalHandoverAttempts) * 100, 1) 
            : 0;

        // Monthly Statistics
        $totalReportsThisMonth = ItemReport::whereMonth('reportDate', $currentMonth)
            ->whereYear('reportDate', $currentYear)
            ->count();

        $lostReportsThisMonth = ItemReport::where('reportType', 'Lost')
            ->whereMonth('reportDate', $currentMonth)
            ->whereYear('reportDate', $currentYear)
            ->count();

        $foundReportsThisMonth = ItemReport::where('reportType', 'Found')
            ->whereMonth('reportDate', $currentMonth)
            ->whereYear('reportDate', $currentYear)
            ->count();

        // Monthly Unresolved Cases: Active published reports this month
        $unresolvedCasesThisMonth = ItemReport::where('reportStatus', 'Published')
            ->whereMonth('reportDate', $currentMonth)
            ->whereYear('reportDate', $currentYear)
            ->count();

        // Monthly Completed Cases: Completed handover requests this month
        $completedCasesThisMonth = \App\Models\HandoverRequest::where('requestStatus', 'Completed')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        // Monthly Success Rate: Handover completion rate for this month
        $totalHandoverAttemptsThisMonth = \App\Models\HandoverRequest::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
        $monthlySuccessRate = $totalHandoverAttemptsThisMonth > 0 
            ? round(($completedCasesThisMonth / $totalHandoverAttemptsThisMonth) * 100, 1) 
            : 0;

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

        // Top Lost Locations (Hotspots)
        $topLostLocations = \App\Models\ItemLocation::withCount(['lostReports as lost_reports_count'])
            ->orderByDesc('lost_reports_count')
            ->take(5)
            ->get();

        // Top Found Locations (Recovery Spots)
        $topFoundLocations = \App\Models\ItemLocation::withCount(['foundReports as found_reports_count'])
            ->orderByDesc('found_reports_count')
            ->take(5)
            ->get();

        return compact(
            'totalUsers', 'totalLostReports', 'totalFoundReports',
            'publishedLostReports', 'publishedFoundReports',
            'pendingLostReports', 'pendingFoundReports',
            'unresolvedCases', 'completedCases', 'successRate',
            'totalReportsThisMonth', 'lostReportsThisMonth', 'foundReportsThisMonth',
            'unresolvedCasesThisMonth', 'completedCasesThisMonth', 'monthlySuccessRate',
            'topLostCategories', 'topFoundCategories',
            'topLostLocations', 'topFoundLocations'
        );
    }

    // -------------------- User Management -------------------- //
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

    // -------------------- Item Report Management -------------------- //
    public function manageLostReports(Request $request)
    {
        $query = ItemReport::where('reportType', 'Lost');

        if ($request->has('status') && $request->status != '') {
            $query->where('reportStatus', ucfirst($request->status));
        }

        $lostReports = $query->orderBy('reportDate', 'desc')->get();

        return view('admin.manage_report_lost', [
            'lostReports' => $lostReports,
            'status' => $request->status,
        ]);
    }

    public function manageFoundReports(Request $request)
    {
        $query = ItemReport::where('reportType', 'Found');

        if ($request->has('status') && $request->status != '') {
            $query->where('reportStatus', ucfirst($request->status));
        }

        $foundReports = $query->orderBy('reportDate', 'desc')->get();

        return view('admin.manage_report_found', [
            'foundReports' => $foundReports,
            'status' => $request->status,
        ]);
    }
    
    public function approveReport($id)
    {
        $report = ItemReport::findOrFail($id);
        $report->reportStatus = 'Published';
        $report->rejectionNote = null;
        $report->save();

        if ($report->reportType === 'Lost') {
            return redirect()->route('admin.manage_report_lost')
                ->with('success', 'Report has been approved and published.');
        } else {
            return redirect()->route('admin.manage_report_found')
                ->with('success', 'Report has been approved and published.');
        }
    }

    public function rejectReport(Request $request, $id)
    {
        $report = ItemReport::findOrFail($id);
        $report->reportStatus = 'Rejected';
        $report->rejectionNote = $request->input('rejectionNote');
        $report->save();

        if ($report->reportType === 'Lost') {
            return redirect()->route('admin.manage_report_lost')
                ->with('error', 'Report has been rejected.');
        } else {
            return redirect()->route('admin.manage_report_found')
                ->with('error', 'Report has been rejected.');
        }
    }

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

    public function deleteCompletedReportPair($reportID)
    {
        $handover = \App\Models\HandoverRequest::where(function ($query) use ($reportID) {
            $query->where('reportID', $reportID)
                  ->orWhere('senderReportID', $reportID);
        })
        ->where('requestStatus', 'Completed')
        ->first();

        if (!$handover) {
            return redirect()->back()->with('error', 'No completed handover found for this report.');
        }

        $reportID1 = $handover->reportID;
        $reportID2 = $handover->senderReportID;

        $report = ItemReport::find($reportID);
        $reportType = $report ? $report->reportType : 'Lost';

        if ($handover->handoverForm) {
            Storage::disk('public')->delete($handover->handoverForm);
        }

        \App\Models\MatchSuggestion::where(function($q) use ($reportID1, $reportID2) {
            $q->where(function($q2) use ($reportID1, $reportID2) {
                $q2->where('reportID', $reportID1)
                   ->where('matchedReportID', $reportID2);
            })->orWhere(function($q3) use ($reportID1, $reportID2) {
                $q3->where('reportID', $reportID2)
                   ->where('matchedReportID', $reportID1);
            });
        })->delete();

        $handover->delete();
        ItemReport::whereIn('reportID', [$reportID1, $reportID2])->delete();

        if ($reportType === 'Lost') {
            return redirect()->route('admin.manage_report_lost')
                ->with('success', 'Both reports in the handover pair have been deleted successfully.');
        } else {
            return redirect()->route('admin.manage_report_found')
                ->with('success', 'Both reports in the handover pair have been deleted successfully.');
        }
    }

    public function showReport($id)
    {
        $report = ItemReport::findOrFail($id);
        return view('admin.manage_report_detail', compact('report'));
    }

    // -------------------- Feedback Management -------------------- //
    public function feedbacks(Request $request)
    {
        $query = \App\Models\Feedback::with('user');

        if ($request->has('type') && $request->type != '') {
            $query->where('feedbackType', $request->type);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('feedbackStatus', $request->status);
        }

        $feedbacks = $query->orderBy('feedbackDate', 'desc')->paginate(15);

        return view('admin.feedbacks.index', compact('feedbacks'));
    }

    public function markAsReviewed($id)
    {
        $feedback = \App\Models\Feedback::findOrFail($id);
        $feedback->update(['feedbackStatus' => 'Reviewed']);

        return redirect()->back()->with('success', 'Feedback marked as reviewed.');
    }

    public function deleteFeedback($id)
    {
        $feedback = \App\Models\Feedback::findOrFail($id);
        $feedback->delete();

        return redirect()->back()->with('success', 'Feedback deleted successfully.');
    }

    // -------------------- FAQ Management -------------------- //
    public function faqs()
    {
        $faqs = \App\Models\FAQ::orderBy('created_at', 'asc')->get();
        return view('admin.faqs.index', compact('faqs'));
    }

    public function createFaq()
    {
        return view('admin.faqs.create');
    }

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

    public function editFaq($id)
    {
        $faq = \App\Models\FAQ::findOrFail($id);
        return view('admin.faqs.edit', compact('faq'));
    }

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

    public function deleteFaq($id)
    {
        $faq = \App\Models\FAQ::findOrFail($id);
        $faq->delete();

        return redirect()->route('admin.faqs')
            ->with('success', 'FAQ deleted successfully!');
    }

    // -------------------- Forum Management -------------------- //
    public function forumPosts(Request $request)
    {
        $query = \App\Models\ForumPost::with(['user', 'comments', 'likes']);

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('forumCategory', $request->category);
        }

        if ($request->filled('author')) {
            if ($request->author === 'admin_posts') {
                $query->whereHas('user', function($q) {
                    $q->where('userRole', 'Admin');
                });
            } elseif ($request->author === 'user_posts') {
                $query->whereHas('user', function($q) {
                    $q->where('userRole', 'User');
                });
            }
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('forumTitle', 'like', '%' . $searchTerm . '%')
                  ->orWhere('forumContent', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('user', function($q2) use ($searchTerm) {
                      $q2->where('userName', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        $posts = $query->orderBy('created_at', 'desc')->get();
        
        return view('admin.forum.index', compact('posts'));
    }

    public function deleteForumPost($id)
    {
        $post = \App\Models\ForumPost::findOrFail($id);
        
        if ($post->forumImg && Storage::disk('public')->exists($post->forumImg)) {
            Storage::disk('public')->delete($post->forumImg);
        }
        
        $post->delete();
        
        return redirect()->route('admin.forum.posts')
            ->with('success', 'Forum post deleted successfully!');
    }

    public function deleteForumComment($id)
    {
        $comment = \App\Models\ForumComment::findOrFail($id);
        $comment->delete();
        
        return redirect()->back()
            ->with('success', 'Comment deleted successfully!');
    }

    // -------------------- Helper Functions -------------------- //
    private function getEnumValues($table, $column)
    {
        $type = DB::select("SHOW COLUMNS FROM {$table} WHERE Field = '{$column}'")[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);

        return collect(explode(',', $matches[1]))->map(fn($v) => trim($v, "'"))->toArray();
    }
}
