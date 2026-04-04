<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title', 'Accueil')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

{{-- Navigation --}}
<nav class="bg-blue-600 text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">

        {{-- Logo --}}
        <a href="{{ route('products.index') }}" class="text-2xl font-bold">
            🍔 ISI Burger
        </a>

        {{-- Menu --}}
        <div class="flex items-center gap-6">
            @auth
                @if(auth()->user()->isGestionnaire())
                    <a href="{{ route('admin.stats.index') }}" class="hover:underline">Dashboard</a>
                    <a href="{{ route('admin.products.index') }}" class="hover:underline">Produits</a>
                    <a href="{{ route('admin.orders.index') }}" class="hover:underline">Commandes</a>
                @else
                    <a href="{{ route('products.index') }}" class="hover:underline">Catalogue</a>
                    <a href="{{ route('orders.my') }}" class="hover:underline">Mes commandes</a>
                @endif

                {{-- Nom + Déconnexion --}}
                <span class="text-blue-200">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-white text-blue-600 px-3 py-1 rounded font-semibold hover:bg-blue-100">
                        Déconnexion
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>

{{-- Messages flash --}}
@if(session('success'))
    <div class="max-w-7xl mx-auto mt-4 px-4">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    </div>
@endif

@if(session('error'))
    <div class="max-w-7xl mx-auto mt-4 px-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    </div>
@endif

{{-- Contenu de la page --}}
<main class="max-w-7xl mx-auto px-4 py-8">
    @yield('content')

</main>
    @stack('scripts')

</body>
</html>
