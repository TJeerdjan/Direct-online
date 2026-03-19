@extends('layouts.app')
@section('page_title', 'Import voorbeeld')

@section('content')
<div class="max-w-5xl" data-testid="bulk-import-preview">
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-semibold text-do-darker">Controleer je producten</h3>
                <p class="text-sm text-do-mid">{{ count($rows) }} producten gevonden in het CSV bestand.</p>
            </div>
            <span class="bg-do-accent/10 text-do-accent text-sm font-semibold px-3 py-1 rounded-full" data-testid="product-count-badge">
                {{ count($rows) }} producten
            </span>
        </div>

        {{-- Preview table --}}
        <div class="overflow-x-auto mb-6 border border-gray-100 rounded-lg">
            <table class="w-full text-sm">
                <thead class="bg-do-surface">
                    <tr>
                        <th class="text-left py-2.5 px-3 text-xs font-semibold text-do-mid uppercase tracking-wide">#</th>
                        <th class="text-left py-2.5 px-3 text-xs font-semibold text-do-mid uppercase tracking-wide">Naam</th>
                        <th class="text-left py-2.5 px-3 text-xs font-semibold text-do-mid uppercase tracking-wide">Beschrijving</th>
                        <th class="text-left py-2.5 px-3 text-xs font-semibold text-do-mid uppercase tracking-wide">Categorie</th>
                        <th class="text-left py-2.5 px-3 text-xs font-semibold text-do-mid uppercase tracking-wide">Afbeelding</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $index => $row)
                    <tr class="border-t border-gray-50 {{ $index % 2 === 0 ? '' : 'bg-gray-50/50' }}">
                        <td class="py-2 px-3 text-xs text-do-mid">{{ $index + 1 }}</td>
                        <td class="py-2 px-3 font-medium text-do-darker">{{ $row['name'] }}</td>
                        <td class="py-2 px-3 text-do-mid max-w-xs truncate">{{ Str::limit($row['description'], 60) }}</td>
                        <td class="py-2 px-3">
                            @if($row['category'])
                            <span class="text-xs bg-do-accent/10 text-do-accent px-2 py-0.5 rounded">{{ $row['category'] }}</span>
                            @else
                            <span class="text-xs text-do-mid">-</span>
                            @endif
                        </td>
                        <td class="py-2 px-3">
                            @if($row['image_name'])
                            <span class="text-xs font-mono text-do-mid">{{ $row['image_name'] }}</span>
                            @else
                            <span class="text-xs text-do-mid">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Info box about image matching --}}
        @php
            $withImages = collect($rows)->filter(fn($r) => $r['image_name'] !== '')->count();
        @endphp
        @if($withImages > 0)
        <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 mb-6 text-sm text-blue-700" data-testid="image-info-box">
            <i class="fa-solid fa-circle-info mr-1"></i>
            <strong>{{ $withImages }} producten</strong> hebben een afbeeldingsnaam. Deze worden automatisch gekoppeld aan bestanden in je Media Bibliotheek (op basis van bestandsnaam). Afbeeldingen die nog niet zijn geupload kun je later toevoegen.
        </div>
        @endif

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            <form method="POST" action="{{ route('client.products.bulk-import.store') }}">
                @csrf
                <button type="submit" class="bg-do-accent hover:bg-do-accent/90 text-white font-medium px-6 py-2.5 rounded-lg text-sm transition" data-testid="confirm-import-btn">
                    <i class="fa-solid fa-check mr-1"></i> {{ count($rows) }} producten importeren
                </button>
            </form>
            <a href="{{ route('client.products.bulk-import') }}" class="text-sm text-do-mid hover:text-do-darker">
                <i class="fa-solid fa-arrow-left mr-1"></i> Terug
            </a>
        </div>
    </div>
</div>
@endsection
