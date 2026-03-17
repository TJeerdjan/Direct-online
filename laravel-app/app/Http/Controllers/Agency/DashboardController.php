<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\FormSubmission;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_clients' => Client::count(),
            'active_clients' => Client::where('status', 'active')->count(),
            'new_submissions' => FormSubmission::where('status', 'nieuw')->count(),
        ];
        $recent_clients = Client::orderBy('created_at', 'desc')->take(5)->get();
        return view('agency.dashboard', compact('stats', 'recent_clients'));
    }
}
