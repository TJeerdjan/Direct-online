@extends('layouts.app')
@section('page_title', 'Klant bewerken')

@section('content')
<div class="max-w-2xl" data-testid="edit-client-form">
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <form method="POST" action="{{ route('agency.clients.update', $client) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Bedrijfsnaam *</label>
                    <input type="text" name="naam" value="{{ old('naam', $client->naam) }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="edit-client-naam-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Domein *</label>
                    <input type="text" name="domain" value="{{ old('domain', $client->domain) }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="edit-client-domain-input">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Contactpersoon</label>
                    <input type="text" name="contact_naam" value="{{ old('contact_naam', $client->contact_naam) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Contact e-mail</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $client->contact_email) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Plan *</label>
                    <select name="plan" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none">
                        @foreach(['early_bird' => 'Early Bird', 'starter' => 'Starter', 'growth' => 'Growth', 'webshop' => 'Webshop'] as $value => $label)
                            <option value="{{ $value }}" {{ old('plan', $client->plan) === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Status *</label>
                    <select name="status" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none">
                        @foreach(['active' => 'Actief', 'suspended' => 'Gepauzeerd', 'cancelled' => 'Opgezegd'] as $value => $label)
                            <option value="{{ $value }}" {{ old('status', $client->status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- API Key (read-only) --}}
            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">API Key</label>
                <div class="flex items-center gap-2">
                    <input type="text" value="{{ $client->api_key }}" readonly
                           class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50 text-do-mid font-mono text-xs" data-testid="client-api-key">
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-do-accent hover:bg-do-accent/90 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition" data-testid="update-client-btn">
                    Opslaan
                </button>
                <a href="{{ route('agency.clients.index') }}" class="text-sm text-do-mid hover:text-do-darker">Annuleren</a>
            </div>
        </form>
    </div>

    {{-- Users Section --}}
    @if($client->users->count())
    <div class="bg-white rounded-xl border border-gray-100 p-6 mt-6" data-testid="client-users-section">
        <h3 class="text-base font-semibold text-do-darker mb-4">Gebruikers</h3>
        <div class="space-y-2">
            @foreach($client->users as $user)
            <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-sm font-medium text-do-darker">{{ $user->naam }}</p>
                    <p class="text-xs text-do-mid">{{ $user->email }}</p>
                </div>
                <span class="text-xs bg-do-accent/10 text-do-accent px-2 py-0.5 rounded">{{ $user->role }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
