<?php

namespace App\Http\Controllers;


use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Client passe une commande
    public function store(Request $request)
    {
        $request->validate([
            'items'                => 'required|array|min:1',
            'items.*.product_id'   => 'required|exists:products,id',
            'items.*.quantity'     => 'required|integer|min:1',
        ]);

        // DB::transaction garantit que tout s'enregistre
        // ou rien si une erreur survient
        DB::transaction(function () use ($request) {

            $total = 0;
            $orderItems = [];

            // Vérifie le stock et calcule le total
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Vérifier que le produit est disponible
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock insuffisant pour {$product->name}");
                }

                $total += $product->price * $item['quantity'];

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $product->price, // prix figé au moment de la commande
                ];
            }

            // Créer la commande
            $order = Order::create([
                'user_id' => auth()->id(),
                'status'  => 'en_attente',
                'total'   => $total,
            ]);

            // Créer les lignes de commande
            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            // Décrémenter le stock de chaque produit
            foreach ($request->items as $item) {
                Product::where('id', $item['product_id'])
                    ->decrement('stock', $item['quantity']);
            }
        });

        return redirect()->route('orders.my')
            ->with('success', 'Commande passée avec succès !');
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

    // Client télécharge sa facture (Sprint 4)
    public function invoice(Order $order)
    {
        // Vérifie que la commande appartient au client connecté
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Sera complété au Sprint 4 avec DomPDF
        return back()->with('error', 'Facture disponible au Sprint 4.');
    }
}
