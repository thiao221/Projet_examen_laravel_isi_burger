@extends('layouts.app')

@section('title', $product->name)

@section('content')

    <div class="bg-white rounded-lg shadow p-8 max-w-2xl mx-auto">

        {{-- Image --}}
        @if($product->image)
            <img src="{{ asset('storage/'.$product->image) }}"
                 alt="{{ $product->name }}"
                 class="w-full h-64 object-cover rounded-lg mb-6">
        @else
            <div class="w-full h-64 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
                <span class="text-8xl">🍔</span>
            </div>
        @endif

        {{-- Catégorie --}}
        <span class="text-sm text-blue-500 -600 font-semibold uppercase">
            {{ $product->category->name }}
        </span>

        {{-- Nom + Prix --}}
        <h1 class="text-3xl font-bold text-gray-800 mt-2">{{ $product->name }}</h1>
        <p class="text-2xl text-blue-600 font-bold mt-2">
            {{ number_format($product->price, 0, ',', ' ') }} FCFA
        </p>

        {{-- Description --}}
        <p class="text-gray-600 mt-4 leading-relaxed">{{ $product->description }}</p>

        {{-- Stock --}}
        <p class="text-sm text-gray-400 mt-2">Stock disponible : {{ $product->stock }}</p>

        {{-- Bouton Commander --}}
        <form method="POST" action="{{ route('orders.store') }}">
            @csrf
            <input type="hidden" name="items[0][product_id]" value="{{ $product->id }}">
            <div class="flex items-center gap-4">
                <input type="number" name="items[0][quantity]"
                       value="1" min="1" max="{{ $product->stock }}"
                       class="border rounded px-3 py-2 w-20 text-center">
                <button type="submit"
                        class="flex-1 bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700">
                    Commander maintenant
                </button>
            </div>
        </form>

        <a href="{{ route('products.index') }}"
           class="block text-center mt-4 text-gray-500 hover:text-gray-700">
            ← Retour au catalogue
        </a>
    </div>

@endsection
