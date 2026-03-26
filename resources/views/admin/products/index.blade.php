@extends('layouts.app')

@section('title', 'Gestion des Produits')

@section('content')

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Gestion des Produits</h1>
        <a href="{{ route('admin.products.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Nouveau Burger
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Produit</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Catégorie</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Prix</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Stock</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Statut</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @forelse($products as $product)
                <tr class="{{ $product->archived ? 'bg-gray-50 opacity-60' : '' }}">

                    {{-- Nom + Image --}}
                    <td class="px-6 py-4 flex items-center gap-3">
                        @if($product->image)
                            <img src="{{ asset('storage/'.$product->image) }}"
                                 class="w-12 h-12 object-cover rounded">
                        @else
                            <div class="w-12 h-12 bg-orange-100 rounded flex items-center justify-center text-2xl">🍔</div>
                        @endif
                        <span class="font-semibold text-gray-800">{{ $product->name }}</span>
                    </td>

                    <td class="px-6 py-4 text-gray-600">{{ $product->category->name }}</td>

                    <td class="px-6 py-4 font-bold text-blue-600">
                        {{ number_format($product->price, 0, ',', ' ') }} FCFA
                    </td>

                    <td class="px-6 py-4">
                            <span class="{{ $product->stock == 0 ? 'text-red-600 font-bold' : 'text-gray-700' }}">
                                {{ $product->stock }}
                            </span>
                    </td>

                    {{-- Statut --}}
                    <td class="px-6 py-4">
                        @if($product->archived)
                            <span class="bg-gray-200 text-gray-600 px-2 py-1 rounded text-xs">Archivé</span>
                        @elseif($product->stock == 0)
                            <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-xs">Rupture</span>
                        @else
                            <span class="bg-green-100 text-green-600 px-2 py-1 rounded text-xs">Disponible</span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td class="px-6 py-4 flex gap-2">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="bg-blue-100 text-blue-600 px-3 py-1 rounded text-sm hover:bg-blue-200">
                            Modifier
                        </a>

                        <form method="POST" action="{{ route('admin.products.archive', $product) }}">
                            @csrf @method('PATCH')
                            <button class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded text-sm hover:bg-yellow-200">
                                {{ $product->archived ? 'Réactiver' : 'Archiver' }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                              onsubmit="return confirm('Supprimer ce produit ?')">
                            @csrf @method('DELETE')
                            <button class="bg-red-100 text-blue-600 px-3 py-1 rounded text-sm hover:bg-blue-200">
                                Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        Aucun produit. <a href="{{ route('admin.products.create') }}" class="text-blue-600">Créer le premier</a>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div class="p-4">{{ $products->links() }}</div>
    </div>

@endsection
