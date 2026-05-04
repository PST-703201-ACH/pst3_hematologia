<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UsuarioActualizado extends Mailable
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
            subject: 'Has sido actualizado en el sistema del servicio de Hematologia Dr. Walles Camarillo',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.emailActualizacion',
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
