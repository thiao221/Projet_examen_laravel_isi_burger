<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Liste toutes les commandes
    public function index()
    {
        $orders = Order::with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    // Détail d'une commande
    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);// a ne pas oublier pour gerer les paryement
        return view('admin.orders.show', compact('order'));
    }

    // Change le statut d'une commande
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:en_attente,en_preparation,prete,payee,annulee',
        ]);

        $ancienStatut = $order->status;
        $order->update(['status' => $request->status]);

        // Si la commande passe à "prete" → envoyer email + PDF (Sprint 4)
        if ($request->status === 'prete' && $ancienStatut !== 'prete') {
            // envoyer email avec facture PDF
        }

        return back()->with('success', 'Statut mis à jour : ' . Order::STATUTS[$request->status]);
    }

    // Annuler une commande
    public function destroy(Order $order)
    {
        // Remettre le stock des produits
        foreach ($order->items as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        $order->update(['status' => 'annulee']);

        return back()->with('success', 'Commande annulée.');
    }
}
