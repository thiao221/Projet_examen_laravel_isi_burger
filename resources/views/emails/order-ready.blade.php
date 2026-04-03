<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; }
        .header { background: #16a34a; color: white; padding: 20px; text-align: center; }
        .content { padding: 30px; }
        .badge { background: #dcfce7; color: #16a34a; padding: 10px 20px; border-radius: 20px; display: inline-block; font-weight: bold; }
        .footer { background: #f3f4f6; padding: 15px; text-align: center; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>

<div class="header">
    <h1>🍔 ISI Burger</h1>
    <p>Votre commande est prête !</p>
</div>

<div class="content">
    <p>Bonjour <strong>{{ $order->user->name }}</strong>,</p>

    <p>Bonne nouvelle ! Votre commande <strong>#{{ $order->id }}</strong> est prête.</p>

    <p><span class="badge">Commande prête</span></p>

    <p>Montant total : <strong>{{ number_format($order->total, 0, ',', ' ') }} FCFA</strong></p>

    <p>Votre facture est jointe à cet email en PDF.</p>

    <p>Merci de votre confiance et à bientôt !</p>
</div>

<div class="footer">
    <p>ISI Burger — Votre restaurant de burgers préféré</p>
</div>

</body>
</html>
