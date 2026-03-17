@extends('layouts.app')
@section('page_title', 'Klanten')
@section('header_actions')
    <a href="{{ route('agency.clients.create') }}" class="bg-do-accent hover:bg-do-accent/90 text-white text-sm font-medium px-4 py-2 rounded-lg transition" data-testid="add-client-btn">
        <i class="fa-solid fa-plus mr-1"></i> Nieuwe klant
    </a>
@endsection

@section('content')
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden" data-testid="clients-table">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-50 text-left">
                <th class="px-5 py-3 text-xs font-semibold text-do-mid uppercase tracking-wider">Klant</th>
                <th class="px-5 py-3 text-xs font-semibold text-do-mid uppercase tracking-wider">Plan</th>
                <th class="px-5 py-3 text-xs font-semibold text-do-mid uppercase tracking-wider">Status</th>
                <th class="px-5 py-3 text-xs font-semibold text-do-mid uppercase tracking-wider">Portfolio</th>
                <th class="px-5 py-3 text-xs font-semibold text-do-mid uppercase tracking-wider">Berichten</th>
                <th class="px-5 py-3 text-xs font-semibold text-do-mid uppercase tracking-wider">Acties</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($clients as $client)
            <tr class="hover:bg-gray-50/50" data-testid="client-row-{{ $client->id }}">
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-do-dark flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr($client->naam, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-do-darker">{{ $client->naam }}</p>
                            <p class="text-xs text-do-mid">{{ $client->domain }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-3">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-do-accent/10 text-do-accent">
                        {{ ucfirst(str_replace('_', ' ', $client->plan)) }}
                    </span>
                </td>
                <td class="px-5 py-3">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                        {{ $client->status === 'active' ? 'bg-emerald-50 text-emerald-600' : ($client->status === 'suspended' ? 'bg-amber-50 text-amber-600' : 'bg-red-50 text-red-600') }}">
                        {{ ucfirst($client->status) }}
                    </span>
                </td>
                <td class="px-5 py-3 text-sm text-do-mid">{{ $client->projects_count }}</td>
                <td class="px-5 py-3 text-sm text-do-mid">{{ $client->form_submissions_count }}</td>
                <td class="px-5 py-3">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('agency.clients.edit', $client) }}" class="text-do-mid hover:text-do-accent text-sm" title="Bewerken" data-testid="edit-client-{{ $client->id }}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form method="POST" action="{{ route('agency.clients.impersonate', $client) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-do-mid hover:text-do-cta text-sm" title="Bekijk als klant" data-testid="impersonate-client-{{ $client->id }}">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-8 text-center text-sm text-do-mid">Nog geen klanten. <a href="{{ route('agency.clients.create') }}" class="text-do-accent hover:underline">Voeg je eerste klant toe</a></td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
