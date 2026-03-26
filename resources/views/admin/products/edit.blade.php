@extends('layouts.app')

@section('title', 'Modifier ' . $product->name)

@section('content')

    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Modifier : {{ $product->name }}</h1>

        <form method="POST" action="{{ route('admin.products.update', $product) }}"
              enctype="multipart/form-data"
              class="bg-white rounded-lg shadow p-6 space-y-4">
            @csrf
            @method('PUT')

            {{-- Nom --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nom</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}"
                       class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror">
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Catégorie --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Catégorie</label>
                <select name="category_id" class="w-full border rounded px-3 py-2">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Prix --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Prix (FCFA)</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0"
                       class="w-full border rounded px-3 py-2">
            </div>

            {{-- Stock --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Stock</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0"
                       class="w-full border rounded px-3 py-2">
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3"
                          class="w-full border rounded px-3 py-2">{{ old('description', $product->description) }}</textarea>
            </div>

            {{-- Image actuelle --}}
            @if($product->image)
                <div>
                    <p class="text-sm text-gray-600 mb-2">Image actuelle :</p>
                    <img src="{{ asset('storage/'.$product->image) }}"
                         class="w-32 h-32 object-cover rounded">
                </div>
            @endif

            {{-- Nouvelle image --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Nouvelle image (optionnel)
                </label>
                <input type="file" name="image" accept="image/*"
                       class="w-full border rounded px-3 py-2">
            </div>

            {{-- Boutons --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-orange-600 text-white px-6 py-2 rounded hover:bg-orange-700">
                    Enregistrer
                </button>
                <a href="{{ route('admin.products.index') }}"
                   class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">
                    Annuler
                </a>
            </div>
        </form>
    </div>

@endsection
