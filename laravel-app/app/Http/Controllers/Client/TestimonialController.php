<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::where('client_id', session('client_id'))->orderBy('sort_order')->get();
        return view('client.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('client.testimonials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'client_company' => 'nullable|string|max:255',
            'client_title' => 'nullable|string|max:255',
            'quote' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        Testimonial::create([
            'client_id' => session('client_id'),
            'client_name' => $request->client_name,
            'client_title' => $request->client_title,
            'client_company' => $request->client_company,
            'quote' => $request->quote,
            'rating' => $request->rating ?? 5,
            'is_visible' => true,
            'sort_order' => Testimonial::where('client_id', session('client_id'))->max('sort_order') + 1,
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
            'client_name' => 'required|string|max:255',
            'client_company' => 'nullable|string|max:255',
            'client_title' => 'nullable|string|max:255',
            'quote' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'is_visible' => 'nullable|boolean',
        ]);

        $testimonial->update([
            'client_name' => $request->client_name,
            'client_title' => $request->client_title,
            'client_company' => $request->client_company,
            'quote' => $request->quote,
            'rating' => $request->rating ?? 5,
            'is_visible' => $request->boolean('is_visible'),
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
