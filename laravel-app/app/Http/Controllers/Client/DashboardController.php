<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Testimonial;
use App\Models\FormSubmission;

class DashboardController extends Controller
{
    public function index()
    {
        $clientId = session('client_id');
        $stats = [
            'portfolio' => Project::where('client_id', $clientId)->count(),
            'testimonials' => Testimonial::where('client_id', $clientId)->count(),
            'inbox_nieuw' => FormSubmission::where('client_id', $clientId)->where('status', 'nieuw')->count(),
            'inbox_totaal' => FormSubmission::where('client_id', $clientId)->count(),
        ];
        $recent_submissions = FormSubmission::where('client_id', $clientId)->orderByDesc('aangemaakt_op')->take(5)->get();
        return view('client.dashboard', compact('stats', 'recent_submissions'));
    }
}
