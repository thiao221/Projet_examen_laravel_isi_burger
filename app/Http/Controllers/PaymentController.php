<?php

namespace App\Http\Controllers;

use App\Mail\OrderReady;
use App\Models\Order;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    // Enregistrer un paiement en espèces
    public function store(Request $request, Order $order)
    {
        // Vérifier que la commande n'est pas déjà payée
        if ($order->payment) {
            return back()->with('error', 'Cette commande est déjà payée.');
        }

        // Vérifier que la commande est dans le bon statut
        if (!in_array($order->status, ['prete', 'en_preparation', 'en_attente'])) {
            return back()->with('error', 'Impossible d\'enregistrer le paiement pour cette commande.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        // Enregistrer le paiement
        Payment::create([
            'order_id' => $order->id,
            'amount'   => $request->amount,
            'method'   => 'cash',
            'paid_at'  => now(),
        ]);

        // Passer la commande au statut "payee"
        $order->update(['status' => 'payee']);

        return back()->with('success', 'Paiement enregistré avec succès !');
    }
}
