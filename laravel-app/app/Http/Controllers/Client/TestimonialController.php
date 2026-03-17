<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::where('client_id', session('client_id'))->orderBy('volgorde')->get();
        return view('client.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('client.testimonials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'naam' => 'required|string|max:255',
            'bedrijf' => 'nullable|string|max:255',
            'tekst' => 'required|string',
            'score' => 'nullable|integer|min:1|max:5',
        ]);

        Testimonial::create([
            'client_id' => session('client_id'),
            'naam' => $request->naam,
            'bedrijf' => $request->bedrijf,
            'tekst' => $request->tekst,
            'score' => $request->score ?? 5,
            'is_actief' => true,
            'volgorde' => Testimonial::where('client_id', session('client_id'))->max('volgorde') + 1,
        ]);

        return redirect()->route('client.testimonials.index')->with('success', 'Testimonial toegevoegd');
    }

    public function edit(Testimonial $testimonial)
    {
        if ($testimonial->client_id != session('client_id')) {
            abort(403);
        }
        return view('client.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        if ($testimonial->client_id != session('client_id')) {
            abort(403);
        }

        $request->validate([
            'naam' => 'required|string|max:255',
            'bedrijf' => 'nullable|string|max:255',
            'tekst' => 'required|string',
            'score' => 'nullable|integer|min:1|max:5',
            'is_actief' => 'nullable|boolean',
        ]);

        $testimonial->update([
            'naam' => $request->naam,
            'bedrijf' => $request->bedrijf,
            'tekst' => $request->tekst,
            'score' => $request->score ?? 5,
            'is_actief' => $request->boolean('is_actief'),
        ]);

        return redirect()->route('client.testimonials.index')->with('success', 'Testimonial bijgewerkt');
    }

    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->client_id != session('client_id')) {
            abort(403);
        }
        $testimonial->delete();
        return redirect()->route('client.testimonials.index')->with('success', 'Testimonial verwijderd');
    }
}
