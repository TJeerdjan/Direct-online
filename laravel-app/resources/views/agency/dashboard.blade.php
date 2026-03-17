@extends('layouts.app')
@section('page_title', 'Agency Dashboard')

@section('content')
<div class="space-y-6" data-testid="agency-dashboard">
    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 p-5" data-testid="stat-total-clients">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-do-accent/10 flex items-center justify-center text-do-accent">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-do-darker">{{ $stats['total_clients'] }}</p>
                    <p class="text-xs text-do-mid">Totaal klanten</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5" data-testid="stat-active-clients">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-500">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-do-darker">{{ $stats['active_clients'] }}</p>
                    <p class="text-xs text-do-mid">Actieve klanten</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5" data-testid="stat-new-submissions">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-do-cta/10 flex items-center justify-center text-do-cta">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-do-darker">{{ $stats['new_submissions'] }}</p>
                    <p class="text-xs text-do-mid">Nieuwe berichten</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Clients --}}
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden" data-testid="recent-clients">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-do-darker">Recente klanten</h3>
            <a href="{{ route('agency.clients.index') }}" class="text-sm text-do-accent hover:underline">Alle klanten</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recent_clients as $client)
            <div class="px-5 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-do-dark flex items-center justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr($client->naam, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-do-darker">{{ $client->naam }}</p>
                        <p class="text-xs text-do-mid">{{ $client->domain }}</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                    {{ $client->status === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-500' }}">
                    {{ ucfirst($client->status) }}
                </span>
            </div>
            @empty
            <p class="px-5 py-8 text-center text-sm text-do-mid">Nog geen klanten</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
