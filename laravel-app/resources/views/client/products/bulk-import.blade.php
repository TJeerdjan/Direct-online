@extends('layouts.app')
@section('page_title', 'Producten importeren')

@section('content')
<div class="max-w-2xl" data-testid="bulk-import-page">
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <div class="mb-6">
            <h3 class="text-base font-semibold text-do-darker mb-2">Bulk importeren via CSV</h3>
            <p class="text-sm text-do-mid">Upload een CSV bestand om meerdere producten tegelijk toe te voegen. De afbeeldingen koppel je later via de Media Bibliotheek.</p>
        </div>

        {{-- Step 1: Download template --}}
        <div class="bg-do-surface rounded-lg p-4 mb-6 border border-gray-100">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-do-accent/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <span class="text-sm font-bold text-do-accent">1</span>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-do-darker mb-1">Download het CSV template</h4>
                    <p class="text-xs text-do-mid mb-3">Open het template in Excel of Google Sheets, vul je producten in, en sla op als CSV.</p>
                    <a href="{{ route('client.products.template') }}" class="inline-flex items-center bg-do-dark hover:bg-do-darker text-white text-sm font-medium px-4 py-2 rounded-lg transition" data-testid="download-template-btn">
                        <i class="fa-solid fa-download mr-2"></i> Download template
                    </a>
                </div>
            </div>
        </div>

        {{-- Template format info --}}
        <div class="bg-do-surface rounded-lg p-4 mb-6 border border-gray-100">
            <h4 class="text-sm font-semibold text-do-darker mb-2"><i class="fa-solid fa-circle-info text-do-accent mr-1"></i> CSV formaat</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-2 px-2 text-do-mid font-medium">Kolom</th>
                            <th class="text-left py-2 px-2 text-do-mid font-medium">Verplicht</th>
                            <th class="text-left py-2 px-2 text-do-mid font-medium">Beschrijving</th>
                        </tr>
                    </thead>
                    <tbody class="text-do-darker">
                        <tr class="border-b border-gray-50">
                            <td class="py-2 px-2 font-mono font-medium">name</td>
                            <td class="py-2 px-2"><span class="text-red-500">Ja</span></td>
                            <td class="py-2 px-2">Productnaam</td>
                        </tr>
                        <tr class="border-b border-gray-50">
                            <td class="py-2 px-2 font-mono font-medium">description</td>
                            <td class="py-2 px-2"><span class="text-do-mid">Nee</span></td>
                            <td class="py-2 px-2">Productbeschrijving</td>
                        </tr>
                        <tr class="border-b border-gray-50">
                            <td class="py-2 px-2 font-mono font-medium">category</td>
                            <td class="py-2 px-2"><span class="text-do-mid">Nee</span></td>
                            <td class="py-2 px-2">Categorie naam</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-2 font-mono font-medium">image_name</td>
                            <td class="py-2 px-2"><span class="text-do-mid">Nee</span></td>
                            <td class="py-2 px-2">Bestandsnaam van de afbeelding (bijv. <code class="bg-gray-100 px-1 rounded">foto.jpg</code>). Wordt automatisch gekoppeld wanneer je het bestand uploadt naar Media.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="text-xs text-do-mid mt-2"><i class="fa-solid fa-triangle-exclamation text-do-cta mr-1"></i> Gebruik <strong>puntkomma (;)</strong> als scheidingsteken. Dit is standaard in Excel (NL). Google Sheets gebruikt een komma — sla in dat geval op als <em>CSV UTF-8</em>.</p>
        </div>

        {{-- Step 2: Upload --}}
        <div class="bg-do-surface rounded-lg p-4 mb-4 border border-gray-100">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-do-accent/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <span class="text-sm font-bold text-do-accent">2</span>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-do-darker mb-3">Upload je CSV bestand</h4>
                    <form method="POST" action="{{ route('client.products.bulk-import.preview') }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <input type="file" name="csv_file" required accept=".csv,.txt"
                               class="w-full text-sm text-do-mid file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-do-accent/10 file:text-do-accent hover:file:bg-do-accent/20 cursor-pointer"
                               data-testid="csv-file-input">
                        @error('csv_file') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        <button type="submit" class="bg-do-accent hover:bg-do-accent/90 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition" data-testid="preview-import-btn">
                            <i class="fa-solid fa-eye mr-1"></i> Voorbeeld bekijken
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <a href="{{ route('client.products.index') }}" class="text-sm text-do-mid hover:text-do-darker"><i class="fa-solid fa-arrow-left mr-1"></i> Terug naar producten</a>
        </div>
    </div>
</div>
@endsection
