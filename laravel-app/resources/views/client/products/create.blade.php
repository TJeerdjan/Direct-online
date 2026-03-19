@extends('layouts.app')
@section('page_title', 'Nieuw product')

@section('content')
<div class="max-w-3xl" data-testid="create-product-form">
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <form method="POST" action="{{ route('client.products.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <h3 class="text-base font-semibold text-do-darker border-b border-gray-100 pb-3">Productgegevens</h3>

            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Productnaam *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                       data-testid="product-title-input">
                @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Beschrijving</label>
                <textarea name="description" rows="4"
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                          data-testid="product-description-input">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Prijs *</label>
                    <input type="number" name="price" value="{{ old('price', '0.00') }}" required step="0.01" min="0"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="product-price-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Valuta *</label>
                    <select name="currency" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none" data-testid="product-currency-select">
                        <option value="EUR" {{ old('currency', 'EUR') === 'EUR' ? 'selected' : '' }}>EUR</option>
                        <option value="USD" {{ old('currency') === 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="GBP" {{ old('currency') === 'GBP' ? 'selected' : '' }}>GBP</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Gewicht (kg)</label>
                    <input type="number" name="weight" value="{{ old('weight') }}" step="0.01" min="0"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="product-weight-input">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Categorie</label>
                    <input type="text" name="category" value="{{ old('category') }}" placeholder="bijv. Elektronica"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="product-category-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Merk</label>
                    <input type="text" name="brand" value="{{ old('brand') }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="product-brand-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku') }}" placeholder="bijv. PROD-001"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="product-sku-input">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Voorraad</label>
                <input type="number" name="stock_quantity" value="{{ old('stock_quantity') }}" min="0"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                       placeholder="Laat leeg als voorraad niet van toepassing is"
                       data-testid="product-stock-input">
            </div>

            <h3 class="text-base font-semibold text-do-darker border-b border-gray-100 pb-3 pt-2">Productafbeelding</h3>

            {{-- Upload new image --}}
            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Upload nieuwe afbeelding</label>
                <input type="file" name="new_image" accept="image/*"
                       class="w-full text-sm text-do-mid file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-do-accent/10 file:text-do-accent hover:file:bg-do-accent/20 cursor-pointer"
                       data-testid="product-image-upload">
                <p class="text-xs text-do-mid mt-1">Of kies een bestaande afbeelding uit je media bibliotheek:</p>
            </div>

            {{-- Pick from media library --}}
            @if($media->count())
            <div class="grid grid-cols-6 gap-2 max-h-48 overflow-y-auto p-2 border border-gray-100 rounded-lg" data-testid="media-picker" x-data="{ selected: null }">
                @foreach($media as $item)
                <label class="cursor-pointer relative">
                    <input type="radio" name="image_id" value="{{ $item->id }}" class="sr-only peer" x-model="selected">
                    <div class="aspect-square rounded-lg overflow-hidden border-2 peer-checked:border-do-accent border-transparent transition">
                        <img src="{{ $item->url }}" alt="{{ $item->alt_text }}" class="w-full h-full object-cover">
                    </div>
                </label>
                @endforeach
            </div>
            @else
            <p class="text-xs text-do-mid">Nog geen afbeeldingen in je media bibliotheek. <a href="{{ route('client.media.index') }}" class="text-do-accent hover:underline">Upload er een</a></p>
            @endif

            <div class="flex items-center gap-3 pt-3">
                <button type="submit" class="bg-do-accent hover:bg-do-accent/90 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition" data-testid="save-product-btn">
                    Product opslaan
                </button>
                <a href="{{ route('client.products.index') }}" class="text-sm text-do-mid hover:text-do-darker">Annuleren</a>
            </div>
        </form>
    </div>
</div>
@endsection
