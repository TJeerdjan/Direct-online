@extends('layouts.app')
@section('page_title', 'Bericht')

@section('content')
<div class="max-w-2xl" data-testid="inbox-detail">
    <a href="{{ route('client.inbox.index') }}" class="inline-flex items-center text-sm text-do-mid hover:text-do-accent mb-4">
        <i class="fa-solid fa-arrow-left mr-2"></i> Terug naar inbox
    </a>

    <div class="bg-white rounded-xl border border-gray-100 p-6 space-y-4">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-lg font-semibold text-do-darker" data-testid="submission-naam">{{ $submission->naam }}</h3>
                <p class="text-sm text-do-mid">{{ $submission->email }} @if($submission->telefoon) &middot; {{ $submission->telefoon }} @endif</p>
            </div>
            <span class="text-xs text-do-mid">{{ \Carbon\Carbon::parse($submission->aangemaakt_op)->format('d-m-Y H:i') }}</span>
        </div>

        <div class="bg-gray-50 rounded-lg p-4" data-testid="submission-bericht">
            <p class="text-sm text-do-darker leading-relaxed whitespace-pre-wrap">{{ $submission->bericht }}</p>
        </div>

        @if($submission->bron_pagina && $submission->bron_pagina !== '/')
        <p class="text-xs text-do-mid"><i class="fa-solid fa-link mr-1"></i> Verzonden vanaf: {{ $submission->bron_pagina }}</p>
        @endif

        {{-- Status Actions --}}
        <div class="flex items-center gap-2 pt-2 border-t border-gray-100">
            @foreach(['nieuw' => 'Nieuw', 'in_behandeling' => 'In behandeling', 'afgehandeld' => 'Afgehandeld'] as $value => $label)
            <form method="POST" action="{{ route('client.inbox.status', $submission) }}" class="inline">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="{{ $value }}">
                <button type="submit"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition
                    {{ $submission->status === $value ? 'bg-do-accent text-white' : 'bg-gray-100 text-do-mid hover:bg-gray-200' }}"
                    data-testid="status-{{ $value }}">
                    {{ $label }}
                </button>
            </form>
            @endforeach

            <form method="POST" action="{{ route('client.inbox.archive', $submission) }}" class="ml-auto">
                @csrf
                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-medium bg-red-50 text-red-500 hover:bg-red-100 transition" data-testid="archive-btn">
                    <i class="fa-solid fa-box-archive mr-1"></i> Archiveren
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
