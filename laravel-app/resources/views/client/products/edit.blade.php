@extends('layouts.app')
@section('page_title', 'Product bewerken')

@section('content')
<div class="max-w-3xl" data-testid="edit-product-form">
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <form method="POST" action="{{ route('client.products.update', $product) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <h3 class="text-base font-semibold text-do-darker border-b border-gray-100 pb-3">Productgegevens</h3>

            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Productnaam *</label>
                <input type="text" name="title" value="{{ old('title', $product->title) }}" required
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                       data-testid="product-title-input">
            </div>

            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Beschrijving</label>
                <textarea name="description" rows="4"
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                          data-testid="product-description-input">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Prijs *</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" required step="0.01" min="0"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="product-price-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Valuta *</label>
                    <select name="currency" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none" data-testid="product-currency-select">
                        @foreach(['EUR', 'USD', 'GBP'] as $cur)
                        <option value="{{ $cur }}" {{ old('currency', $product->currency) === $cur ? 'selected' : '' }}>{{ $cur }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Gewicht (kg)</label>
                    <input type="number" name="weight" value="{{ old('weight', $product->weight) }}" step="0.01" min="0"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="product-weight-input">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Categorie</label>
                    <input type="text" name="category" value="{{ old('category', $product->category) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="product-category-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">Merk</label>
                    <input type="text" name="brand" value="{{ old('brand', $product->brand) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="product-brand-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-do-mid mb-1">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                           data-testid="product-sku-input">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Voorraad</label>
                <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none"
                       placeholder="Laat leeg als voorraad niet van toepassing is"
                       data-testid="product-stock-input">
            </div>

            <h3 class="text-base font-semibold text-do-darker border-b border-gray-100 pb-3 pt-2">Productafbeelding</h3>

            {{-- Current image --}}
            @if($product->image)
            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Huidige afbeelding</label>
                <img src="{{ $product->image->url }}" alt="{{ $product->title }}" class="h-32 rounded-lg object-cover">
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-do-mid mb-1">Nieuwe afbeelding uploaden</label>
                <input type="file" name="new_image" accept="image/*"
                       class="w-full text-sm text-do-mid file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-do-accent/10 file:text-do-accent hover:file:bg-do-accent/20 cursor-pointer"
                       data-testid="product-image-upload">
                <p class="text-xs text-do-mid mt-1">Of kies uit je media bibliotheek:</p>
            </div>

            @if($media->count())
            <div class="grid grid-cols-6 gap-2 max-h-48 overflow-y-auto p-2 border border-gray-100 rounded-lg" data-testid="media-picker">
                @foreach($media as $item)
                <label class="cursor-pointer relative">
                    <input type="radio" name="image_id" value="{{ $item->id }}" class="sr-only peer"
                           {{ $product->image_id == $item->id ? 'checked' : '' }}>
                    <div class="aspect-square rounded-lg overflow-hidden border-2 peer-checked:border-do-accent border-transparent transition">
                        <img src="{{ $item->url }}" alt="{{ $item->alt_text }}" class="w-full h-full object-cover">
                    </div>
                </label>
                @endforeach
            </div>
            @endif

            <h3 class="text-base font-semibold text-do-darker border-b border-gray-100 pb-3 pt-2">Status</h3>

            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 text-sm text-do-mid cursor-pointer">
                    <input type="hidden" name="is_visible" value="0">
                    <input type="checkbox" name="is_visible" value="1" {{ $product->is_visible ? 'checked' : '' }}
                           class="rounded border-gray-300 text-do-accent focus:ring-do-accent" data-testid="product-visible-toggle">
                    Zichtbaar op website
                </label>
                <label class="flex items-center gap-2 text-sm text-do-mid cursor-pointer">
                    <input type="hidden" name="is_available" value="0">
                    <input type="checkbox" name="is_available" value="1" {{ $product->is_available ? 'checked' : '' }}
                           class="rounded border-gray-300 text-do-accent focus:ring-do-accent" data-testid="product-available-toggle">
                    Beschikbaar voor bestelling
                </label>
            </div>

            <div class="flex items-center gap-3 pt-3">
                <button type="submit" class="bg-do-accent hover:bg-do-accent/90 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition" data-testid="update-product-btn">
                    Opslaan
                </button>
                <a href="{{ route('client.products.index') }}" class="text-sm text-do-mid hover:text-do-darker">Annuleren</a>
            </div>
        </form>
    </div>
</div>
@endsection
