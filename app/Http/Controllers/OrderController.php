<?php

namespace App\Http\Controllers;


use App\Mail\OrderConfirmed;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    // Client passe une commande
    public function store(Request $request)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        try {
            $order = DB::transaction(function () use ($request) {

                $total = 0;
                $orderItems = [];

                foreach ($request->items as $item) {
                    $product = Product::findOrFail($item['product_id']);

                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stock insuffisant pour {$product->name}");
                    }

                    $total += $product->price * $item['quantity'];

                    $orderItems[] = [
                        'product_id' => $product->id,
                        'quantity'   => $item['quantity'],
                        'unit_price' => $product->price,
                    ];
                }

                $order = Order::create([
                    'user_id' => auth()->id(),
                    'status'  => 'en_attente',
                    'total'   => $total,
                ]);

                foreach ($orderItems as $item) {
                    $order->items()->create($item);
                }

                foreach ($request->items as $item) {
                    Product::where('id', $item['product_id'])
                        ->decrement('stock', $item['quantity']);
                }

                return $order;
            });

            // Charger les relations pour l'email
            $order->load(['user', 'items.product']);

            // Envoyer email de confirmation au client
//            Mail::to($order->user->email)->send(new OrderConfirmed($order));

            return redirect()->route('orders.my')
                ->with('success', 'Commande passée avec succès !');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Client voit ses commandes
    public function myOrders()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.my', compact('orders'));
    }

    // Télécharger la facture PDF
    public function invoice(Order $order)
    {
        // Vérifie que la commande appartient au client connecté
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Vérifie que la commande est prête ou payée
        if (!in_array($order->status, ['prete', 'payee'])) {
            return back()->with('error', 'La facture n\'est pas encore disponible.');
        }

        $order->load(['user', 'items.product', 'payment']);

        // Générer le PDF depuis la vue pdf/invoice.blade.php
        $pdf = Pdf::loadView('pdf.invoice', compact('order'));
        return $pdf->download('facture-'.$order->id.'.pdf');

        // Télécharger le PDF
        return $pdf->download('facture-' . $order->id . '.pdf');
    }
}
