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
                <input type="text" name="title" value="{{ old('title', $project->title) }}" required
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                       data-testid="project-title-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Beschrijving</label>
                <textarea name="description" rows="4"
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                          data-testid="project-description-input">{{ old('description', $project->description) }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Categorie</label>
                    <input type="text" name="category" value="{{ old('category', $project->category) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="project-category-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">URL</label>
                    <input type="url" name="external_url" value="{{ old('external_url', $project->external_url) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="project-url-input">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <label class="flex items-center gap-2 text-sm text-do-mid cursor-pointer">
                    <input type="hidden" name="is_visible" value="0">
                    <input type="checkbox" name="is_visible" value="1" {{ $project->is_visible ? 'checked' : '' }}
                           class="rounded border-gray-300 text-do-accent focus:ring-do-accent" data-testid="project-visible-toggle">
                    Zichtbaar op website
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
