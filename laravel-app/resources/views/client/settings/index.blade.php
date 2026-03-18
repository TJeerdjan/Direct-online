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
                <input type="text" name="site_name" value="{{ old('site_name', $settings->site_name) }}"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                       data-testid="settings-site-name-input">
            </div>

            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Tagline</label>
                <input type="text" name="tagline" value="{{ old('tagline', $settings->tagline) }}" placeholder="Korte beschrijving van je bedrijf"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                       data-testid="settings-tagline-input">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Primaire kleur</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="primary_color" value="{{ old('primary_color', $settings->primary_color ?? '#129387') }}"
                               class="w-10 h-10 rounded-lg border border-gray-200 cursor-pointer" data-testid="settings-primary-color">
                        <input type="text" value="{{ $settings->primary_color ?? '#129387' }}" readonly
                               class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50 text-do-mid font-mono">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Accent kleur</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="accent_color" value="{{ old('accent_color', $settings->accent_color ?? '#f59d0e') }}"
                               class="w-10 h-10 rounded-lg border border-gray-200 cursor-pointer" data-testid="settings-accent-color">
                        <input type="text" value="{{ $settings->accent_color ?? '#f59d0e' }}" readonly
                               class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50 text-do-mid font-mono">
                    </div>
                </div>
            </div>

            <h3 class="text-base font-semibold text-do-darker border-b border-gray-100 pb-3 pt-2">Social media</h3>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1"><i class="fa-brands fa-facebook mr-1"></i> Facebook</label>
                    <input type="url" name="social_facebook" value="{{ old('social_facebook', $settings->social_facebook) }}" placeholder="https://facebook.com/..."
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="settings-facebook-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1"><i class="fa-brands fa-instagram mr-1"></i> Instagram</label>
                    <input type="url" name="social_instagram" value="{{ old('social_instagram', $settings->social_instagram) }}" placeholder="https://instagram.com/..."
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="settings-instagram-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1"><i class="fa-brands fa-linkedin mr-1"></i> LinkedIn</label>
                    <input type="url" name="social_linkedin" value="{{ old('social_linkedin', $settings->social_linkedin) }}" placeholder="https://linkedin.com/..."
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="settings-linkedin-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1"><i class="fa-brands fa-twitter mr-1"></i> Twitter</label>
                    <input type="url" name="social_twitter" value="{{ old('social_twitter', $settings->social_twitter) }}" placeholder="https://twitter.com/..."
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="settings-twitter-input">
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
