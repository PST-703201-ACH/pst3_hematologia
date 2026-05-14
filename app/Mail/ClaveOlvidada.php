<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClaveOlvidada extends Mailable
{
    use Queueable, SerializesModels;

    public $datos;


    public function __construct($datosRecibidos)
    {
        $this->datos = $datosRecibidos;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu contraseña ha sido restablecida en el sistema del servicio de Hematologia Dr. Walles Camarillo',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.emailClave',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
