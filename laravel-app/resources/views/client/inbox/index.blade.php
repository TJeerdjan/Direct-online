@extends('layouts.app')
@section('page_title', 'Inbox')

@section('content')
<div data-testid="inbox-page">
    {{-- Filter --}}
    <div class="mb-4 flex items-center gap-2" data-testid="inbox-filters">
        @foreach(['alle' => 'Alle', 'nieuw' => 'Nieuw', 'in_behandeling' => 'In behandeling', 'afgehandeld' => 'Afgehandeld'] as $value => $label)
        <a href="{{ route('client.inbox.index', ['status' => $value]) }}"
           class="px-3 py-1.5 rounded-lg text-sm font-medium transition
           {{ (request('status', 'alle') === $value) ? 'bg-do-accent text-white' : 'bg-white text-do-mid border border-gray-200 hover:border-do-accent/30' }}"
           data-testid="filter-{{ $value }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- Messages --}}
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden" data-testid="inbox-list">
        <div class="divide-y divide-gray-50">
            @forelse($submissions as $sub)
            <a href="{{ route('client.inbox.show', $sub) }}" class="px-5 py-4 flex items-center gap-4 hover:bg-gray-50/50 block" data-testid="inbox-item-{{ $sub->id }}">
                <div class="w-2 h-2 rounded-full flex-shrink-0 {{ $sub->is_gelezen ? 'bg-gray-200' : 'bg-do-accent' }}"></div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-0.5">
                        <p class="text-sm font-medium text-do-darker {{ !$sub->is_gelezen ? 'font-semibold' : '' }}">{{ $sub->naam }}</p>
                        <span class="text-xs text-do-mid">{{ $sub->email }}</span>
                    </div>
                    <p class="text-sm text-do-mid truncate">{{ Str::limit($sub->bericht, 80) }}</p>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                        {{ $sub->status === 'nieuw' ? 'bg-blue-50 text-blue-600' : ($sub->status === 'in_behandeling' ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600') }}">
                        {{ str_replace('_', ' ', ucfirst($sub->status)) }}
                    </span>
                    <span class="text-xs text-do-mid">{{ \Carbon\Carbon::parse($sub->aangemaakt_op)->diffForHumans() }}</span>
                </div>
            </a>
            @empty
            <div class="px-5 py-12 text-center">
                <i class="fa-solid fa-inbox text-4xl text-gray-200 mb-3"></i>
                <p class="text-sm text-do-mid">Geen berichten gevonden</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
