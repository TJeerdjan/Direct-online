@extends('layouts.app')
@section('page_title', 'Nieuwe testimonial')

@section('content')
<div class="max-w-2xl" data-testid="create-testimonial-form">
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <form method="POST" action="{{ route('client.testimonials.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Naam *</label>
                    <input type="text" name="naam" value="{{ old('naam') }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="testimonial-naam-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Bedrijf</label>
                    <input type="text" name="bedrijf" value="{{ old('bedrijf') }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="testimonial-bedrijf-input">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Tekst *</label>
                <textarea name="tekst" rows="4" required
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                          data-testid="testimonial-tekst-input">{{ old('tekst') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Score (1-5 sterren)</label>
                <select name="score" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none" data-testid="testimonial-score-select">
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ old('score', 5) == $i ? 'selected' : '' }}>{{ $i }} {{ str_repeat('★', $i) }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-do-accent hover:bg-do-accent/90 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition" data-testid="save-testimonial-btn">
                    Testimonial opslaan
                </button>
                <a href="{{ route('client.testimonials.index') }}" class="text-sm text-do-mid hover:text-do-darker">Annuleren</a>
            </div>
        </form>
    </div>
</div>
@endsection
