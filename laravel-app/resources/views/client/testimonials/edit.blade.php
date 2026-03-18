@extends('layouts.app')
@section('page_title', 'Testimonial bewerken')

@section('content')
<div class="max-w-2xl" data-testid="edit-testimonial-form">
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <form method="POST" action="{{ route('client.testimonials.update', $testimonial) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Naam *</label>
                    <input type="text" name="client_name" value="{{ old('client_name', $testimonial->client_name) }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="testimonial-name-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Bedrijf</label>
                    <input type="text" name="client_company" value="{{ old('client_company', $testimonial->client_company) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="testimonial-company-input">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Functie</label>
                <input type="text" name="client_title" value="{{ old('client_title', $testimonial->client_title) }}"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                       data-testid="testimonial-title-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Tekst *</label>
                <textarea name="quote" rows="4" required
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                          data-testid="testimonial-quote-input">{{ old('quote', $testimonial->quote) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Score</label>
                <select name="rating" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none" data-testid="testimonial-rating-select">
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ old('rating', $testimonial->rating) == $i ? 'selected' : '' }}>{{ $i }} {{ str_repeat('★', $i) }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex items-center gap-2">
                <label class="flex items-center gap-2 text-sm text-do-mid cursor-pointer">
                    <input type="hidden" name="is_visible" value="0">
                    <input type="checkbox" name="is_visible" value="1" {{ $testimonial->is_visible ? 'checked' : '' }}
                           class="rounded border-gray-300 text-do-accent focus:ring-do-accent" data-testid="testimonial-visible-toggle">
                    Zichtbaar op website
                </label>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-do-accent hover:bg-do-accent/90 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition" data-testid="update-testimonial-btn">
                    Opslaan
                </button>
                <a href="{{ route('client.testimonials.index') }}" class="text-sm text-do-mid hover:text-do-darker">Annuleren</a>
            </div>
        </form>
    </div>
</div>
@endsection
