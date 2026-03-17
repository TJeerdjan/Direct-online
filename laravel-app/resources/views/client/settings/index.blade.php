@extends('layouts.app')
@section('page_title', 'Instellingen')

@section('content')
<div class="max-w-2xl" data-testid="settings-page">
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <form method="POST" action="{{ route('client.settings.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <h3 class="text-base font-semibold text-do-darker border-b border-gray-100 pb-3">Website instellingen</h3>

            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Website naam</label>
                <input type="text" name="site_naam" value="{{ old('site_naam', $settings->site_naam) }}"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                       data-testid="settings-site-naam-input">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Primaire kleur</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="primaire_kleur" value="{{ old('primaire_kleur', $settings->primaire_kleur ?? '#129387') }}"
                               class="w-10 h-10 rounded-lg border border-gray-200 cursor-pointer" data-testid="settings-primaire-kleur">
                        <input type="text" value="{{ $settings->primaire_kleur ?? '#129387' }}" readonly
                               class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50 text-do-mid font-mono">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Secundaire kleur</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="secundaire_kleur" value="{{ old('secundaire_kleur', $settings->secundaire_kleur ?? '#f59d0e') }}"
                               class="w-10 h-10 rounded-lg border border-gray-200 cursor-pointer" data-testid="settings-secundaire-kleur">
                        <input type="text" value="{{ $settings->secundaire_kleur ?? '#f59d0e' }}" readonly
                               class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50 text-do-mid font-mono">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Over ons tekst</label>
                <textarea name="over_tekst" rows="4"
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                          data-testid="settings-over-tekst-input">{{ old('over_tekst', $settings->over_tekst) }}</textarea>
            </div>

            <h3 class="text-base font-semibold text-do-darker border-b border-gray-100 pb-3 pt-2">Contactgegevens</h3>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">E-mail</label>
                    <input type="email" name="email" value="{{ old('email', $settings->email) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="settings-email-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Telefoon</label>
                    <input type="text" name="telefoon" value="{{ old('telefoon', $settings->telefoon) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="settings-telefoon-input">
                </div>
            </div>

            <h3 class="text-base font-semibold text-do-darker border-b border-gray-100 pb-3 pt-2">Social media</h3>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1"><i class="fa-brands fa-facebook mr-1"></i> Facebook</label>
                    <input type="url" name="facebook" value="{{ old('facebook', $settings->facebook) }}" placeholder="https://facebook.com/..."
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="settings-facebook-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1"><i class="fa-brands fa-instagram mr-1"></i> Instagram</label>
                    <input type="url" name="instagram" value="{{ old('instagram', $settings->instagram) }}" placeholder="https://instagram.com/..."
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="settings-instagram-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1"><i class="fa-brands fa-linkedin mr-1"></i> LinkedIn</label>
                    <input type="url" name="linkedin" value="{{ old('linkedin', $settings->linkedin) }}" placeholder="https://linkedin.com/..."
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="settings-linkedin-input">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="bg-do-accent hover:bg-do-accent/90 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition" data-testid="save-settings-btn">
                    Instellingen opslaan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
