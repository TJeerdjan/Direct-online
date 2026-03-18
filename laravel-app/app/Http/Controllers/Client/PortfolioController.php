<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function index()
    {
        $projects = Project::where('client_id', session('client_id'))->orderBy('sort_order')->get();
        return view('client.portfolio.index', compact('projects'));
    }

    public function create()
    {
        return view('client.portfolio.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'external_url' => 'nullable|url|max:500',
            'afbeelding' => 'nullable|image|max:5120',
        ]);

        $data = $request->only(['title', 'description', 'category', 'external_url']);
        $data['client_id'] = session('client_id');
        $data['slug'] = Str::slug($request->title);
        $data['is_visible'] = true;
        $data['sort_order'] = Project::where('client_id', session('client_id'))->max('sort_order') + 1;

        if ($request->hasFile('afbeelding')) {
            $path = $request->file('afbeelding')->store('portfolio', 'public');
            // Store path reference; image_id would link to media_DO in full implementation
        }

        Project::create($data);
        return redirect()->route('client.portfolio.index')->with('success', 'Project toegevoegd');
    }

    public function edit(Project $project)
    {
        if ($project->client_id != session('client_id')) {
            abort(403);
        }
        return view('client.portfolio.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        if ($project->client_id != session('client_id')) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'external_url' => 'nullable|url|max:500',
            'is_visible' => 'nullable|boolean',
        ]);

        $data = $request->only(['title', 'description', 'category', 'external_url']);
        $data['slug'] = Str::slug($request->title);
        $data['is_visible'] = $request->boolean('is_visible');

        $project->update($data);
        return redirect()->route('client.portfolio.index')->with('success', 'Project bijgewerkt');
    }

    public function destroy(Project $project)
    {
        if ($project->client_id != session('client_id')) {
            abort(403);
        }
        $project->delete();
        return redirect()->route('client.portfolio.index')->with('success', 'Project verwijderd');
    }
}
