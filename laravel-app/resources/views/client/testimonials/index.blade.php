@extends('layouts.app')
@section('page_title', 'Testimonials')
@section('header_actions')
    <a href="{{ route('client.testimonials.create') }}" class="bg-do-accent hover:bg-do-accent/90 text-white text-sm font-medium px-4 py-2 rounded-lg transition" data-testid="add-testimonial-btn">
        <i class="fa-solid fa-plus mr-1"></i> Nieuwe testimonial
    </a>
@endsection

@section('content')
<div data-testid="testimonials-list">
    @if($testimonials->count())
    <div class="space-y-3">
        @foreach($testimonials as $testimonial)
        <div class="bg-white rounded-xl border border-gray-100 p-5" data-testid="testimonial-card-{{ $testimonial->id }}">
            <div class="flex items-start justify-between">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-do-cta/10 flex items-center justify-center text-do-cta font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr($testimonial->client_name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-semibold text-do-darker">{{ $testimonial->client_name }}</h3>
                            @if($testimonial->client_company)
                            <span class="text-xs text-do-mid">&mdash; {{ $testimonial->client_company }}</span>
                            @endif
                        </div>
                        @if($testimonial->client_title)
                        <p class="text-xs text-do-mid">{{ $testimonial->client_title }}</p>
                        @endif
                        <div class="flex items-center gap-0.5 my-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star text-xs {{ $i <= $testimonial->rating ? 'text-do-cta' : 'text-gray-200' }}"></i>
                            @endfor
                        </div>
                        <p class="text-sm text-do-mid leading-relaxed">{{ $testimonial->quote }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0 ml-4">
                    <span class="w-2 h-2 rounded-full {{ $testimonial->is_visible ? 'bg-emerald-400' : 'bg-gray-300' }}"></span>
                    <a href="{{ route('client.testimonials.edit', $testimonial) }}" class="text-do-mid hover:text-do-accent" data-testid="edit-testimonial-{{ $testimonial->id }}">
                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                    </a>
                    <form method="POST" action="{{ route('client.testimonials.destroy', $testimonial) }}" class="inline" onsubmit="return confirm('Verwijderen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-do-mid hover:text-red-500" data-testid="delete-testimonial-{{ $testimonial->id }}">
                            <i class="fa-solid fa-trash text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-xl border border-gray-100 p-12 text-center" data-testid="testimonials-empty">
        <i class="fa-solid fa-star text-4xl text-gray-200 mb-3"></i>
        <h3 class="text-base font-semibold text-do-darker mb-1">Nog geen testimonials</h3>
        <p class="text-sm text-do-mid mb-4">Voeg klantbeoordelingen toe om op je website te tonen.</p>
        <a href="{{ route('client.testimonials.create') }}" class="inline-flex items-center bg-do-accent hover:bg-do-accent/90 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <i class="fa-solid fa-plus mr-2"></i> Testimonial toevoegen
        </a>
    </div>
    @endif
</div>
@endsection
