@extends('layouts.app')
@section('page_title', 'Media Bibliotheek')

@section('content')
<div data-testid="media-library" x-data="{ showUpload: false }">
    {{-- Upload area --}}
    <div class="mb-6">
        <button @click="showUpload = !showUpload" class="bg-do-accent hover:bg-do-accent/90 text-white text-sm font-medium px-4 py-2 rounded-lg transition" data-testid="toggle-upload-btn">
            <i class="fa-solid fa-cloud-arrow-up mr-1"></i> Upload bestand
        </button>
    </div>

    <div x-show="showUpload" x-cloak class="bg-white rounded-xl border border-gray-100 p-6 mb-6" data-testid="upload-form">
        <form method="POST" action="{{ route('client.media.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Bestand *</label>
                <input type="file" name="file" required accept="image/*,video/*,.pdf"
                       class="w-full text-sm text-do-mid file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-do-accent/10 file:text-do-accent hover:file:bg-do-accent/20 cursor-pointer"
                       data-testid="media-file-input">
                <p class="text-xs text-do-mid mt-1">Max 10MB. Afbeeldingen, video's en PDF's.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Alt tekst</label>
                <input type="text" name="alt_text" placeholder="Beschrijf de afbeelding"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                       data-testid="media-alt-input">
            </div>
            <button type="submit" class="bg-do-accent hover:bg-do-accent/90 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition" data-testid="upload-media-btn">
                Uploaden
            </button>
        </form>
    </div>

    {{-- Media Grid --}}
    @if($media->count())
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3" data-testid="media-grid">
        @foreach($media as $item)
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden group relative" data-testid="media-item-{{ $item->id }}">
            @if($item->media_type === 'image')
            <div class="aspect-square bg-gray-100 overflow-hidden">
                <img src="{{ $item->url }}" alt="{{ $item->alt_text ?? $item->file_name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
            </div>
            @elseif($item->media_type === 'video')
            <div class="aspect-square bg-do-dark flex items-center justify-center">
                <i class="fa-solid fa-video text-2xl text-white/30"></i>
            </div>
            @else
            <div class="aspect-square bg-gray-50 flex items-center justify-center">
                <i class="fa-solid fa-file-pdf text-2xl text-red-300"></i>
            </div>
            @endif

            <div class="p-2">
                <p class="text-xs text-do-darker truncate font-medium">{{ $item->file_name }}</p>
                <p class="text-xs text-do-mid">{{ $item->file_size ? round($item->file_size / 1024) . ' KB' : '' }}</p>
            </div>

            {{-- Overlay actions --}}
            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2">
                <button onclick="copyUrl('{{ $item->url }}')" class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-do-darker hover:text-do-accent" title="Kopieer URL">
                    <i class="fa-solid fa-link text-xs"></i>
                </button>
                <form method="POST" action="{{ route('client.media.destroy', $item) }}" onsubmit="return confirm('Verwijderen?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-do-darker hover:text-red-500" title="Verwijderen">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-xl border border-gray-100 p-12 text-center" data-testid="media-empty">
        <i class="fa-solid fa-photo-film text-4xl text-gray-200 mb-3"></i>
        <h3 class="text-base font-semibold text-do-darker mb-1">Nog geen bestanden</h3>
        <p class="text-sm text-do-mid mb-4">Upload afbeeldingen om te gebruiken in je portfolio en producten.</p>
        <button @click="showUpload = true" class="inline-flex items-center bg-do-accent hover:bg-do-accent/90 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Upload bestand
        </button>
    </div>
    @endif
</div>

<script>
function copyUrl(url) {
    const fullUrl = window.location.origin + url;
    navigator.clipboard.writeText(fullUrl).then(() => {
        alert('URL gekopieerd!');
    });
}
</script>
@endsection
