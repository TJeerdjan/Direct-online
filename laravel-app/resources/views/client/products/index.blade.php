@extends('layouts.app')
@section('page_title', 'Producten')
@section('header_actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('client.products.bulk-import') }}" class="bg-do-dark hover:bg-do-darker text-white text-sm font-medium px-4 py-2 rounded-lg transition" data-testid="bulk-import-btn">
            <i class="fa-solid fa-file-import mr-1"></i> Bulk importeren
        </a>
        <a href="{{ route('client.products.create') }}" class="bg-do-accent hover:bg-do-accent/90 text-white text-sm font-medium px-4 py-2 rounded-lg transition" data-testid="add-product-btn">
            <i class="fa-solid fa-plus mr-1"></i> Nieuw product
        </a>
    </div>
@endsection

@section('content')
<div data-testid="products-list">
    @if($products->count())
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($products as $product)
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden group" data-testid="product-card-{{ $product->id }}">
            @if($product->image)
            <div class="h-48 bg-gray-100 overflow-hidden">
                <img src="{{ $product->image->url }}" alt="{{ $product->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
            </div>
            @else
            <div class="h-48 bg-gradient-to-br from-do-dark to-do-darker flex items-center justify-center">
                <i class="fa-solid fa-box text-3xl text-white/20"></i>
            </div>
            @endif
            <div class="p-4">
                <div class="flex items-start justify-between mb-2">
                    <div>
                        <h3 class="text-sm font-semibold text-do-darker">{{ $product->title }}</h3>
                        @if($product->category)
                        <span class="text-xs text-do-accent">{{ $product->category }}</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full {{ $product->is_visible ? 'bg-emerald-400' : 'bg-gray-300' }}" title="{{ $product->is_visible ? 'Zichtbaar' : 'Verborgen' }}"></span>
                        <span class="w-2 h-2 rounded-full {{ $product->is_available ? 'bg-blue-400' : 'bg-red-300' }}" title="{{ $product->is_available ? 'Beschikbaar' : 'Niet beschikbaar' }}"></span>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-2">
                    <span class="text-lg font-bold text-do-darker">{{ $product->currency }} {{ number_format($product->price, 2, ',', '.') }}</span>
                    @if($product->sku)
                    <span class="text-xs text-do-mid font-mono">{{ $product->sku }}</span>
                    @endif
                </div>

                @if($product->stock_quantity !== null)
                <div class="mb-3">
                    <span class="text-xs px-2 py-0.5 rounded {{ $product->stock_quantity > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-500' }}">
                        {{ $product->stock_quantity > 0 ? $product->stock_quantity . ' op voorraad' : 'Uitverkocht' }}
                    </span>
                </div>
                @endif

                <div class="flex items-center gap-2 pt-2 border-t border-gray-50">
                    <a href="{{ route('client.products.edit', $product) }}" class="text-xs text-do-accent hover:underline" data-testid="edit-product-{{ $product->id }}">
                        <i class="fa-solid fa-pen-to-square mr-1"></i> Bewerken
                    </a>
                    <form method="POST" action="{{ route('client.products.destroy', $product) }}" class="inline" onsubmit="return confirm('Weet je zeker dat je dit product wilt verwijderen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-400 hover:text-red-600" data-testid="delete-product-{{ $product->id }}">
                            <i class="fa-solid fa-trash mr-1"></i> Verwijderen
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-xl border border-gray-100 p-12 text-center" data-testid="products-empty">
        <i class="fa-solid fa-store text-4xl text-gray-200 mb-3"></i>
        <h3 class="text-base font-semibold text-do-darker mb-1">Nog geen producten</h3>
        <p class="text-sm text-do-mid mb-4">Voeg je eerste product toe aan je webshop.</p>
        <a href="{{ route('client.products.create') }}" class="inline-flex items-center bg-do-accent hover:bg-do-accent/90 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <i class="fa-solid fa-plus mr-2"></i> Product toevoegen
        </a>
    </div>
    @endif
</div>
@endsection
