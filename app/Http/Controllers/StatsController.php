<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function index()
    {
        // Commandes en cours du jour
        $commandesEnCours = Order::duJour()->enCours()->count();

        // Commandes validées du jour
        $commandesValidees = Order::duJour()->validees()->count();

        // Recettes journalières
        $recettesJour = Payment::whereDate('paid_at', today())->sum('amount');

        // Données pour Chart.js : commandes par mois (12 derniers mois)
        $commandesParMois = Order::selectRaw("TO_CHAR(created_at, 'Mon') as mois, COUNT(*) as total")
            ->whereYear('created_at', now()->year)
            ->groupByRaw("TO_CHAR(created_at, 'Mon'), EXTRACT(MONTH FROM created_at)")
            ->orderByRaw("EXTRACT(MONTH FROM created_at)")
            ->get();

        // Données pour Chart.js : produits par catégorie
        $produitsParCategorie = Product::with('category')
            ->selectRaw('category_id, COUNT(*) as total')
            ->groupBy('category_id')
            ->get();

        return view('admin.stats.index', compact(
            'commandesEnCours',
            'commandesValidees',
            'recettesJour',
            'commandesParMois',
            'produitsParCategorie'
        ));
    }

    // Endpoint JSON pour Chart.js
    public function chartData()
    {
        $commandesParMois = Order::selectRaw("TO_CHAR(created_at, 'Mon') as mois, COUNT(*) as total")
            ->whereYear('created_at', now()->year)
            ->groupByRaw("TO_CHAR(created_at, 'Mon'), EXTRACT(MONTH FROM created_at)")
            ->orderByRaw("EXTRACT(MONTH FROM created_at)")
            ->get();

        $produitsParCategorie = Product::with('category')
            ->selectRaw('category_id, COUNT(*) as total')
            ->groupBy('category_id')
            ->get();

        return response()->json([
            'commandes' => [
                'labels' => $commandesParMois->pluck('mois'),
                'data'   => $commandesParMois->pluck('total'),
            ],
            'categories' => [
                'labels' => $produitsParCategorie->pluck('category.name'),
                'data'   => $produitsParCategorie->pluck('total'),
            ],
        ]);
    }
}
