@extends('layouts.app')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-6" data-testid="client-dashboard">
    {{-- Welcome --}}
    <div class="bg-gradient-to-r from-do-dark to-do-darker rounded-xl p-6 text-white" data-testid="welcome-banner">
        <h3 class="text-lg font-semibold">Welkom, {{ session('user_naam') }}</h3>
        <p class="text-white/60 text-sm mt-1">{{ session('client_naam') }} &mdash; Overzicht van je website</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 p-4" data-testid="stat-portfolio">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-do-accent/10 flex items-center justify-center text-do-accent">
                    <i class="fa-solid fa-images text-sm"></i>
                </div>
                <div>
                    <p class="text-xl font-bold text-do-darker">{{ $stats['portfolio'] }}</p>
                    <p class="text-xs text-do-mid">Portfolio</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-4" data-testid="stat-testimonials">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-do-cta/10 flex items-center justify-center text-do-cta">
                    <i class="fa-solid fa-star text-sm"></i>
                </div>
                <div>
                    <p class="text-xl font-bold text-do-darker">{{ $stats['testimonials'] }}</p>
                    <p class="text-xs text-do-mid">Testimonials</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-4" data-testid="stat-inbox-new">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500">
                    <i class="fa-solid fa-envelope text-sm"></i>
                </div>
                <div>
                    <p class="text-xl font-bold text-do-darker">{{ $stats['inbox_nieuw'] }}</p>
                    <p class="text-xs text-do-mid">Nieuw</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-4" data-testid="stat-inbox-total">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-purple-50 flex items-center justify-center text-purple-500">
                    <i class="fa-solid fa-chart-simple text-sm"></i>
                </div>
                <div>
                    <p class="text-xl font-bold text-do-darker">{{ $stats['inbox_totaal'] }}</p>
                    <p class="text-xs text-do-mid">Totaal berichten</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Messages --}}
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden" data-testid="recent-messages">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-do-darker">Recente berichten</h3>
            <a href="{{ route('client.inbox.index') }}" class="text-sm text-do-accent hover:underline">Bekijk inbox</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recent_submissions as $sub)
            <a href="{{ route('client.inbox.show', $sub) }}" class="px-5 py-3 flex items-center justify-between hover:bg-gray-50/50 block">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-2 h-2 rounded-full {{ $sub->is_gelezen ? 'bg-gray-200' : 'bg-do-accent' }} flex-shrink-0"></div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-do-darker truncate">{{ $sub->naam }}</p>
                        <p class="text-xs text-do-mid truncate">{{ Str::limit($sub->bericht, 60) }}</p>
                    </div>
                </div>
                <span class="text-xs text-do-mid flex-shrink-0 ml-3">{{ \Carbon\Carbon::parse($sub->aangemaakt_op)->diffForHumans() }}</span>
            </a>
            @empty
            <p class="px-5 py-8 text-center text-sm text-do-mid">Nog geen berichten ontvangen</p>
            @endforelse
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <a href="{{ route('client.portfolio.create') }}" class="bg-white rounded-xl border border-gray-100 p-4 hover:border-do-accent/30 transition group" data-testid="quick-add-portfolio">
            <i class="fa-solid fa-plus text-do-accent mb-2"></i>
            <p class="text-sm font-medium text-do-darker group-hover:text-do-accent transition">Nieuw project</p>
        </a>
        <a href="{{ route('client.testimonials.create') }}" class="bg-white rounded-xl border border-gray-100 p-4 hover:border-do-accent/30 transition group" data-testid="quick-add-testimonial">
            <i class="fa-solid fa-plus text-do-cta mb-2"></i>
            <p class="text-sm font-medium text-do-darker group-hover:text-do-accent transition">Nieuwe testimonial</p>
        </a>
        <a href="{{ route('client.inbox.index') }}" class="bg-white rounded-xl border border-gray-100 p-4 hover:border-do-accent/30 transition group" data-testid="quick-inbox">
            <i class="fa-solid fa-inbox text-blue-500 mb-2"></i>
            <p class="text-sm font-medium text-do-darker group-hover:text-do-accent transition">Inbox</p>
        </a>
        <a href="{{ route('client.settings.index') }}" class="bg-white rounded-xl border border-gray-100 p-4 hover:border-do-accent/30 transition group" data-testid="quick-settings">
            <i class="fa-solid fa-gear text-do-mid mb-2"></i>
            <p class="text-sm font-medium text-do-darker group-hover:text-do-accent transition">Instellingen</p>
        </a>
    </div>
</div>
@endsection
