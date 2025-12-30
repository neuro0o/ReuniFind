<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ItemReport;
use App\Models\HandoverRequest;

class LandingController extends Controller
{
    public function index()
    {
        // Get statistics for landing page
        $totalUsers = User::where('userRole', 'User')->count();
        
        $totalLost = ItemReport::where('reportType', 'Lost')
            ->where('reportStatus', 'Published')
            ->count();
            
        $totalFound = ItemReport::where('reportType', 'Found')
            ->where('reportStatus', 'Published')
            ->count();

        $totalHandover = HandoverRequest::where('requestStatus', 'Completed')->count();

        return view('landing', compact(
            'totalUsers',
            'totalLost',
            'totalFound',
            'totalHandover'
        ));
    }
}