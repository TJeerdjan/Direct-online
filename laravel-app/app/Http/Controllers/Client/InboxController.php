<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\FormSubmission;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function index(Request $request)
    {
        $query = FormSubmission::where('client_id', session('client_id'))
            ->where('is_gearchiveerd', false);

        if ($request->status && $request->status !== 'alle') {
            $query->where('status', $request->status);
        }

        $submissions = $query->orderByDesc('aangemaakt_op')->get();
        return view('client.inbox.index', compact('submissions'));
    }

    public function show(FormSubmission $submission)
    {
        if ($submission->client_id != session('client_id')) {
            abort(403);
        }
        if (!$submission->is_gelezen) {
            $submission->update(['is_gelezen' => true]);
        }
        return view('client.inbox.show', compact('submission'));
    }

    public function updateStatus(Request $request, FormSubmission $submission)
    {
        if ($submission->client_id != session('client_id')) {
            abort(403);
        }
        $submission->update(['status' => $request->status]);
        return back()->with('success', 'Status bijgewerkt');
    }

    public function archive(FormSubmission $submission)
    {
        if ($submission->client_id != session('client_id')) {
            abort(403);
        }
        $submission->update(['is_gearchiveerd' => true]);
        return redirect()->route('client.inbox.index')->with('success', 'Bericht gearchiveerd');
    }
}
