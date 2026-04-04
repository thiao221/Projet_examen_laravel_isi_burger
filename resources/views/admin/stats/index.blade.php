@extends('layouts.app')

@section('title', 'Tableau de Bord')

@section('content')

    <h1 class="text-3xl font-bold text-gray-800 mb-8">Tableau de Bord</h1>

    {{-- KPIs du jour --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        {{-- Commandes en cours --}}
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500 uppercase font-semibold">Commandes en cours</p>
            <p class="text-4xl font-bold text-yellow-600 mt-2">{{ $commandesEnCours }}</p>
            <p class="text-xs text-gray-400 mt-1">Aujourd'hui</p>
        </div>

        {{-- Commandes validées --}}
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <p class="text-sm text-gray-500 uppercase font-semibold">Commandes validées</p>
            <p class="text-4xl font-bold text-green-600 mt-2">{{ $commandesValidees }}</p>
            <p class="text-xs text-gray-400 mt-1">Aujourd'hui</p>
        </div>

        {{-- Recettes --}}
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
            <p class="text-sm text-gray-500 uppercase font-semibold">Recettes du jour</p>
            <p class="text-4xl font-bold text-orange-600 mt-2">
                {{ number_format($recettesJour, 0, ',', ' ') }}
            </p>
            <p class="text-xs text-gray-400 mt-1">FCFA</p>
        </div>
    </div>

    {{-- Graphiques --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Commandes par mois --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-700 mb-4">Commandes par mois</h2>
            <canvas id="commandesChart" height="120"></canvas>
        </div>

        {{-- Produits par catégorie --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-700 mb-4">Produits par catégorie</h2>
            <canvas id="categoriesChart" height="120"></canvas>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Récupère les données depuis l'API JSON
        fetch('{{ route("admin.stats.chart") }}')
            .then(res => res.json())
            .then(data => {

                // Graphique 1 : Commandes par mois (courbe)
                new Chart(document.getElementById('commandesChart'), {
                    type: 'line',
                    data: {
                        labels: data.commandes.labels,
                        datasets: [{
                            label: 'Commandes',
                            data: data.commandes.data,
                            borderColor: '#ea580c',
                            backgroundColor: 'rgba(234, 88, 12, 0.1)',
                            tension: 0.4,
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });

                // Graphique 2 : Produits par catégorie (barres)
                new Chart(document.getElementById('categoriesChart'), {
                    type: 'bar',
                    data: {
                        labels: data.categories.labels,
                        datasets: [{
                            label: 'Produits',
                            data: data.categories.data,
                            backgroundColor: [
                                '#ea580c', '#f97316', '#fb923c',
                                '#fdba74', '#fed7aa'
                            ],
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            });
    </script>
@endpush
