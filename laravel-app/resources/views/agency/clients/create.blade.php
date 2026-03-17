@extends('layouts.app')
@section('page_title', 'Nieuwe klant')

@section('content')
<div class="max-w-2xl" data-testid="create-client-form">
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <form method="POST" action="{{ route('agency.clients.store') }}" class="space-y-6">
            @csrf

            <h3 class="text-base font-semibold text-do-darker border-b border-gray-100 pb-3">Bedrijfsgegevens</h3>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Bedrijfsnaam *</label>
                    <input type="text" name="naam" value="{{ old('naam') }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="client-naam-input">
                    @error('naam') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Domein *</label>
                    <input type="text" name="domain" value="{{ old('domain') }}" required placeholder="voorbeeld.nl"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="client-domain-input">
                    @error('domain') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Contactpersoon</label>
                    <input type="text" name="contact_naam" value="{{ old('contact_naam') }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="client-contact-naam-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Contact e-mail</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email') }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="client-contact-email-input">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Plan *</label>
                <select name="plan" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none" data-testid="client-plan-select">
                    <option value="early_bird" {{ old('plan') === 'early_bird' ? 'selected' : '' }}>Early Bird - &euro;10/mnd</option>
                    <option value="starter" {{ old('plan', 'starter') === 'starter' ? 'selected' : '' }}>Starter - &euro;29/mnd</option>
                    <option value="growth" {{ old('plan') === 'growth' ? 'selected' : '' }}>Growth - &euro;59/mnd</option>
                    <option value="webshop" {{ old('plan') === 'webshop' ? 'selected' : '' }}>Webshop - &euro;99/mnd</option>
                </select>
            </div>

            <h3 class="text-base font-semibold text-do-darker border-b border-gray-100 pb-3 pt-2">Gebruikersaccount</h3>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Naam *</label>
                    <input type="text" name="user_naam" value="{{ old('user_naam') }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="user-naam-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">E-mail *</label>
                    <input type="email" name="user_email" value="{{ old('user_email') }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="user-email-input">
                    @error('user_email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Wachtwoord *</label>
                <input type="password" name="user_password" required minlength="6"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                       data-testid="user-password-input">
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-do-accent hover:bg-do-accent/90 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition" data-testid="save-client-btn">
                    Klant aanmaken
                </button>
                <a href="{{ route('agency.clients.index') }}" class="text-sm text-do-mid hover:text-do-darker">Annuleren</a>
            </div>
        </form>
    </div>
</div>
@endsection
