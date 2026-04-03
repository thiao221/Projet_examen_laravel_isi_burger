<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachments;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderReady extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public string $pdfPath // chemin vers le PDF généré
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre commande #' . $this->order->id . ' est prête !',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-ready',
        );
    }

    public function attachments(): array
    {
        return [
            // Attache le PDF à l'email
            Attachments::fromPath($this->pdfPath)
                ->as('facture-' . $this->order->id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
