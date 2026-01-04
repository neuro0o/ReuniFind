<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    /**
     * Display FAQ list with search functionality
     */
    public function index(Request $request)
    {
        $query = FAQ::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('faqQuestion', 'like', '%' . $searchTerm . '%')
                  ->orWhere('faqAnswer', 'like', '%' . $searchTerm . '%');
            });
        }

        $faqs = $query->orderBy('faqID', 'asc')->get();

        return view('faq.index', compact('faqs'));
    }
}
