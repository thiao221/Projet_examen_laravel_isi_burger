@extends('layouts.app')

@section('title', 'Catalogue')

@section('content')

    <h1 class="text-3xl font-bold text-gray-800 mb-6">🍔 Nos Burgers</h1>

    {{-- Filtres --}}
    <form method="GET" action="{{ route('products.index') }}"
          class="bg-white p-4 rounded-lg shadow mb-6 flex flex-wrap gap-4">

        {{-- Recherche par nom --}}
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Rechercher un burger..."
               class="border rounded px-3 py-2 flex-1 min-w-48">

        {{-- Filtre par catégorie --}}
        <select name="category" class="border rounded px-3 py-2">
            <option value="">Toutes les catégories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        {{-- Tri par prix --}}
        <select name="sort" class="border rounded px-3 py-2">
            <option value="">Trier par prix</option>
            <option value="asc"  {{ request('sort') == 'asc'  ? 'selected' : '' }}>Prix croissant</option>
            <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Prix décroissant</option>
        </select>

        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Filtrer
        </button>

        <a href="{{ route('products.index') }}"
           class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
            Réinitialiser
        </a>
    </form>

    {{-- Grille des produits --}}
    @if($products->isEmpty())
        <p class="text-gray-500 text-center py-12">Aucun produit disponible.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition">

                    {{-- Image --}}
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}"
                             alt="{{ $product->name }}"
                             class="w-full h-48 object-cover rounded-t-lg">
                    @else
                        <div class="w-full h-48 bg-blue-100 rounded-t-lg flex items-center justify-center">
                            <span class="text-6xl">🍔</span>
                        </div>
                    @endif

                    <div class="p-4">
                        {{-- Catégorie --}}
                        <span class="text-xs text-blue-600 font-semibold uppercase">
                            {{ $product->category->name }}
                        </span>

                        {{-- Nom --}}
                        <h3 class="text-lg font-bold text-gray-800 mt-1">
                            {{ $product->name }}
                        </h3>

                        {{-- Description --}}
                        <p class="text-gray-500 text-sm mt-1 line-clamp-2">
                            {{ $product->description }}
                        </p>

                        {{-- Prix + Stock --}}
                        <div class="flex justify-between items-center mt-3">
                            <span class="text-blue-600 font-bold text-lg">
                                {{ number_format($product->price, 0, ',', ' ') }} FCFA
                            </span>
                            <span class="text-xs text-gray-400">
                                Stock : {{ $product->stock }}
                            </span>
                        </div>

                        {{-- Bouton --}}
                        <a href="{{ route('products.show', $product->slug) }}"
                           class="mt-4 block text-center bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                            Voir le détail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @endif

@endsection
