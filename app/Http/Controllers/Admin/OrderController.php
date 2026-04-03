<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderReady;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
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

        // Si statut passe à "prete" → générer PDF et envoyer email
        if ($request->status === 'prete' && $ancienStatut !== 'prete') {

            $order->load(['user', 'items.product', 'payment']);

            // Générer le PDF
            $pdf = Pdf::loadView('pdf.invoice', compact('order'));
            $pdfPath = storage_path('app/public/factures/facture-' . $order->id . '.pdf');

            // Créer le dossier si inexistant
            if (!file_exists(dirname($pdfPath))) {
                mkdir(dirname($pdfPath), 0755, true);
            }

            // Sauvegarder le PDF
            $pdf->save($pdfPath);

            // Envoyer l'email avec le PDF en pièce jointe
            Mail::to($order->user->email)
                ->send(new OrderReady($order, $pdfPath));
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
