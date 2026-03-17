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
        $projects = Project::where('client_id', session('client_id'))->orderBy('volgorde')->get();
        return view('client.portfolio.index', compact('projects'));
    }

    public function create()
    {
        return view('client.portfolio.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titel' => 'required|string|max:255',
            'beschrijving' => 'nullable|string',
            'categorie' => 'nullable|string|max:100',
            'url' => 'nullable|url|max:500',
            'afbeelding' => 'nullable|image|max:5120',
        ]);

        $data = $request->only(['titel', 'beschrijving', 'categorie', 'url']);
        $data['client_id'] = session('client_id');
        $data['slug'] = Str::slug($request->titel);
        $data['is_actief'] = true;
        $data['volgorde'] = Project::where('client_id', session('client_id'))->max('volgorde') + 1;

        if ($request->hasFile('afbeelding')) {
            $path = $request->file('afbeelding')->store('portfolio', 'public');
            $data['afbeelding'] = '/storage/' . $path;
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
            'titel' => 'required|string|max:255',
            'beschrijving' => 'nullable|string',
            'categorie' => 'nullable|string|max:100',
            'url' => 'nullable|url|max:500',
            'afbeelding' => 'nullable|image|max:5120',
            'is_actief' => 'nullable|boolean',
        ]);

        $data = $request->only(['titel', 'beschrijving', 'categorie', 'url']);
        $data['slug'] = Str::slug($request->titel);
        $data['is_actief'] = $request->boolean('is_actief');

        if ($request->hasFile('afbeelding')) {
            $path = $request->file('afbeelding')->store('portfolio', 'public');
            $data['afbeelding'] = '/storage/' . $path;
        }

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
