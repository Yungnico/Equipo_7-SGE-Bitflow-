<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Storage;

class ContactanosMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $codigo_cotizacion, $cotizacion;

    public function __construct($cotizacion)
    {
        $this->codigo_cotizacion = $cotizacion->codigo_cotizacion;
        $this->cotizacion = $cotizacion;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Prueba',
            from: new \Illuminate\Mail\Mailables\Address('hola@gmail.com', 'Bitflow')
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        
        return new Content(
            view: 'cotizaciones.pdfmail',
            with: [
                'cotizacion' => $this->cotizacion,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $filePath = storage_path('app/public/' . $this->codigo_cotizacion . '.pdf'); // Ruta correcta del archivo
        if (!file_exists($filePath)) {
            throw new \Exception('El archivo no existe: ' . $filePath); // Manejo de error si el archivo no existe
        }
        return [Attachment::fromPath($filePath)];
    }
}
