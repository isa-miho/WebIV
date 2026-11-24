<?php

namespace App\Mail;

use App\Models\Venda;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// E-mail enviado ao cliente com os dados da venda
class VendaRealizada extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Venda $venda)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Sua compra {$this->venda->documento}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'email_venda',
        );
    }
}
