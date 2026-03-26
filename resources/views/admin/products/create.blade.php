@extends('layouts.app')

@section('title', 'Nouveau Burger')

@section('content')

    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Nouveau Burger</h1>

        <form method="POST" action="{{ route('admin.products.store') }}"
              enctype="multipart/form-data"
              class="bg-white rounded-lg shadow p-6 space-y-4">
            @csrf

            {{-- Nom --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nom du burger</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror">
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Catégorie --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Catégorie</label>
                <select name="category_id"
                        class="w-full border rounded px-3 py-2 @error('category_id') border-red-500 @enderror">
                    <option value="">Choisir une catégorie</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Prix --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Prix (FCFA)</label>
                <input type="number" name="price" value="{{ old('price') }}" min="0"
                       class="w-full border rounded px-3 py-2 @error('price') border-red-500 @enderror">
                @error('price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Stock --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Stock initial</label>
                <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0"
                       class="w-full border rounded px-3 py-2 @error('stock') border-red-500 @enderror">
                @error('stock') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3"
                          class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
            </div>

            {{-- Image --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Image</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full border rounded px-3 py-2">
                @error('image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Boutons --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-orange-600 text-white px-6 py-2 rounded hover:bg-orange-700">
                    Créer le burger
                </button>
                <a href="{{ route('admin.products.index') }}"
                   class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">
                    Annuler
                </a>
            </div>
        </form>
    </div>

@endsection
