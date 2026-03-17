@extends('layouts.app')
@section('page_title', 'Portfolio')
@section('header_actions')
    <a href="{{ route('client.portfolio.create') }}" class="bg-do-accent hover:bg-do-accent/90 text-white text-sm font-medium px-4 py-2 rounded-lg transition" data-testid="add-project-btn">
        <i class="fa-solid fa-plus mr-1"></i> Nieuw project
    </a>
@endsection

@section('content')
<div data-testid="portfolio-list">
    @if($projects->count())
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($projects as $project)
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden group" data-testid="project-card-{{ $project->id }}">
            @if($project->afbeelding)
            <div class="h-40 bg-gray-100 overflow-hidden">
                <img src="{{ $project->afbeelding }}" alt="{{ $project->titel }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
            </div>
            @else
            <div class="h-40 bg-gradient-to-br from-do-dark to-do-darker flex items-center justify-center">
                <i class="fa-solid fa-image text-2xl text-white/20"></i>
            </div>
            @endif
            <div class="p-4">
                <div class="flex items-start justify-between mb-2">
                    <div>
                        <h3 class="text-sm font-semibold text-do-darker">{{ $project->titel }}</h3>
                        @if($project->categorie)
                        <span class="text-xs text-do-accent">{{ $project->categorie }}</span>
                        @endif
                    </div>
                    <span class="w-2 h-2 rounded-full {{ $project->is_actief ? 'bg-emerald-400' : 'bg-gray-300' }}" title="{{ $project->is_actief ? 'Actief' : 'Inactief' }}"></span>
                </div>
                @if($project->beschrijving)
                <p class="text-xs text-do-mid mb-3 line-clamp-2">{{ $project->beschrijving }}</p>
                @endif
                <div class="flex items-center gap-2 pt-2 border-t border-gray-50">
                    <a href="{{ route('client.portfolio.edit', $project) }}" class="text-xs text-do-accent hover:underline" data-testid="edit-project-{{ $project->id }}">
                        <i class="fa-solid fa-pen-to-square mr-1"></i> Bewerken
                    </a>
                    <form method="POST" action="{{ route('client.portfolio.destroy', $project) }}" class="inline" onsubmit="return confirm('Weet je zeker dat je dit project wilt verwijderen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-400 hover:text-red-600" data-testid="delete-project-{{ $project->id }}">
                            <i class="fa-solid fa-trash mr-1"></i> Verwijderen
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-xl border border-gray-100 p-12 text-center" data-testid="portfolio-empty">
        <i class="fa-solid fa-images text-4xl text-gray-200 mb-3"></i>
        <h3 class="text-base font-semibold text-do-darker mb-1">Nog geen projecten</h3>
        <p class="text-sm text-do-mid mb-4">Voeg je eerste portfolio-item toe om te beginnen.</p>
        <a href="{{ route('client.portfolio.create') }}" class="inline-flex items-center bg-do-accent hover:bg-do-accent/90 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <i class="fa-solid fa-plus mr-2"></i> Project toevoegen
        </a>
    </div>
    @endif
</div>
@endsection
