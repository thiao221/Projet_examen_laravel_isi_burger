<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; font-size: 14px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #2563eb; padding-bottom: 15px; }
        .header h1 { color: #2563eb; font-size: 28px; margin: 0; }
        .header p { color: #666; margin: 5px 0; }
        .info { display: flex; justify-content: space-between; margin-bottom: 25px; }
        .info-block { flex: 1; }
        .info-block h3 { color: #2563eb; font-size: 14px; margin-bottom: 5px; }
        .badge { background: #2563eb; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        thead { background: #2563eb; color: white; }
        th { padding: 10px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) { background: #fff7ed; }
        .total-row { background: #fff7ed; font-weight: bold; font-size: 16px; }
        .footer { margin-top: 40px; text-align: center; color: #9ca3af; font-size: 12px; border-top: 1px solid #e5e7eb; padding-top: 15px; }
    </style>
</head>
<body>

{{-- En-tête --}}
<div class="header">
    <h1>🍔 ISI Burger</h1>
    <p>Restaurant de Burgers — Dakar, Sénégal</p>
    <p>Email : contact@isiburger.com</p>
</div>

{{-- Infos commande et client --}}
<div class="info">
    <div class="info-block">
        <h3>FACTURE</h3>
        <p><strong>N° :</strong> #{{ $order->id }}</p>
        <p><strong>Date :</strong> {{ $order->created_at->format('d/m/Y à H:i') }}</p>
        <p><strong>Statut :</strong> <span class="badge">Payée</span></p>
    </div>
    <div class="info-block">
        <h3>CLIENT</h3>
        <p><strong>Nom :</strong> {{ $order->user->name }}</p>
        <p><strong>Email :</strong> {{ $order->user->email }}</p>
    </div>
</div>

{{-- Tableau des articles --}}
<table>
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

    {{-- Total --}}
    <tr class="total-row">
        <td colspan="3" style="text-align:right;">TOTAL</td>
        <td>{{ number_format($order->total, 0, ',', ' ') }} FCFA</td>
    </tr>
    </tbody>
</table>

{{-- Paiement --}}
@if($order->payment)
    <p><strong>Paiement :</strong> Espèces — {{ $order->payment->paid_at->format('d/m/Y à H:i') }}</p>
@endif

{{-- Footer --}}
<div class="footer">
    <p>Merci pour votre confiance ! — ISI Burger</p>
    <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
</div>

</body>
</html>
