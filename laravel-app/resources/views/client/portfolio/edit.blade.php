@extends('layouts.app')
@section('page_title', 'Project bewerken')

@section('content')
<div class="max-w-2xl" data-testid="edit-project-form">
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <form method="POST" action="{{ route('client.portfolio.update', $project) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Titel *</label>
                <input type="text" name="titel" value="{{ old('titel', $project->titel) }}" required
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                       data-testid="project-titel-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Beschrijving</label>
                <textarea name="beschrijving" rows="4"
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                          data-testid="project-beschrijving-input">{{ old('beschrijving', $project->beschrijving) }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Categorie</label>
                    <input type="text" name="categorie" value="{{ old('categorie', $project->categorie) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="project-categorie-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">URL</label>
                    <input type="url" name="url" value="{{ old('url', $project->url) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="project-url-input">
                </div>
            </div>
            @if($project->afbeelding)
            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Huidige afbeelding</label>
                <img src="{{ $project->afbeelding }}" alt="{{ $project->titel }}" class="h-32 rounded-lg object-cover">
            </div>
            @endif
            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Nieuwe afbeelding</label>
                <input type="file" name="afbeelding" accept="image/*"
                       class="w-full text-sm text-do-mid file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-do-accent/10 file:text-do-accent hover:file:bg-do-accent/20 cursor-pointer">
            </div>
            <div class="flex items-center gap-2">
                <label class="flex items-center gap-2 text-sm text-do-mid cursor-pointer">
                    <input type="hidden" name="is_actief" value="0">
                    <input type="checkbox" name="is_actief" value="1" {{ $project->is_actief ? 'checked' : '' }}
                           class="rounded border-gray-300 text-do-accent focus:ring-do-accent" data-testid="project-actief-toggle">
                    Actief op website
                </label>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-do-accent hover:bg-do-accent/90 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition" data-testid="update-project-btn">
                    Opslaan
                </button>
                <a href="{{ route('client.portfolio.index') }}" class="text-sm text-do-mid hover:text-do-darker">Annuleren</a>
            </div>
        </form>
    </div>
</div>
@endsection
