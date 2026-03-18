@extends('layouts.app')
@section('page_title', 'Nieuw project')

@section('content')
<div class="max-w-2xl" data-testid="create-project-form">
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <form method="POST" action="{{ route('client.portfolio.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Titel *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                       data-testid="project-title-input">
                @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Beschrijving</label>
                <textarea name="description" rows="4"
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                          data-testid="project-description-input">{{ old('description') }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Categorie</label>
                    <input type="text" name="category" value="{{ old('category') }}" placeholder="bijv. Bruiloften"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="project-category-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">URL</label>
                    <input type="url" name="external_url" value="{{ old('external_url') }}" placeholder="https://..."
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="project-url-input">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Afbeelding</label>
                <input type="file" name="afbeelding" accept="image/*"
                       class="w-full text-sm text-do-mid file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-do-accent/10 file:text-do-accent hover:file:bg-do-accent/20 cursor-pointer"
                       data-testid="project-afbeelding-input">
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-do-accent hover:bg-do-accent/90 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition" data-testid="save-project-btn">
                    Project opslaan
                </button>
                <a href="{{ route('client.portfolio.index') }}" class="text-sm text-do-mid hover:text-do-darker">Annuleren</a>
            </div>
        </form>
    </div>
</div>
@endsection
