<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemReport;

class MapController extends Controller
{
    /**
     * Display the full-screen map for a specific item report.
     */
    public function show($id)
    {
        $report = ItemReport::with('location', 'category')->findOrFail($id);

        $coords = $report->location
            ? [
                floatval($report->location->latitude),
                floatval($report->location->longitude)
            ]
            : [6.033178, 116.122771]; // default fallback

        return view('item_report.view_map', compact('report', 'coords'));
    }

    
}
