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
use Illuminate\Mail\Mailables\Address;

class CotizacionMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $asunto, $mensaje, $id,$adjuntarPdf;

    public function __construct($asunto, $mensaje, $id, $adjuntarPdf)
    {
        $this->asunto = $asunto;
        $this->mensaje = $mensaje;
        $this->id = $id;
        $this->adjuntarPdf = $adjuntarPdf;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: $this->asunto,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        
        return new Content(
            view: 'emails.cotizacion',
            with: [
                'mensaje' => $this->mensaje,
                'id' => $this->id,
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
        if ($this->adjuntarPdf == 0) {
            return []; // No adjuntar nada si no se solicitó
        }
        $filePath = storage_path('app/public/' . $this->id . '.pdf'); // Ruta correcta del archivo
        if (!file_exists($filePath)) {
            throw new \Exception('El archivo no existe: ' . $filePath); // Manejo de error si el archivo no existe
        }
        return [Attachment::fromPath($filePath)];
    }
}
