<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; }
        .header { background: #ea580c; color: white; padding: 20px; text-align: center; }
        .content { padding: 30px; }
        .table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .table th { background: #f3f4f6; padding: 10px; text-align: left; }
        .table td { padding: 10px; border-bottom: 1px solid #e5e7eb; }
        .total { font-size: 18px; font-weight: bold; color: #ea580c; }
        .footer { background: #f3f4f6; padding: 15px; text-align: center; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>

<div class="header">
    <h1>🍔 ISI Burger</h1>
    <p>Confirmation de commande</p>
</div>

<div class="content">
    <p>Bonjour <strong>{{ $order->user->name }}</strong>,</p>
    <p>Votre commande <strong>#{{ $order->id }}</strong> a bien été reçue et est en cours de traitement.</p>

    <table class="table">
        <thead>
        <tr>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Prix unitaire</th>
            <th>Sous-total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</td>
                <td>{{ number_format($item->subtotal, 0, ',', ' ') }} FCFA</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <p class="total">Total : {{ number_format($order->total, 0, ',', ' ') }} FCFA</p>

    <p>Nous vous informerons dès que votre commande sera prête.</p>
    <p>Merci de votre confiance !</p>
</div>

<div class="footer">
    <p>ISI Burger — Votre restaurant de burgers préféré</p>
</div>

</body>
</html>
