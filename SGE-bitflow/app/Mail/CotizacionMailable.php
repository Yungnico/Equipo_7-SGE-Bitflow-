<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class CotizacionMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $asunto, $mensaje, $id,$adjuntarPdf;
    public $copia, $cco;
    public function __construct($asunto, $mensaje, $id, $adjuntarPdf, $copia , $cco )
    {
        $this->asunto = $asunto;
        $this->mensaje = $mensaje;
        $this->id = $id;
        $this->adjuntarPdf = $adjuntarPdf;
        $this->copia = $copia; // Correo de copia
        $this->cco = $cco; // Correo de copia oculta
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->asunto,
            cc: [
                new \Illuminate\Mail\Mailables\Address('' . $this->copia, 'Copia de Bitflow') // Correo de copia,
            ],
            bcc: [
                new \Illuminate\Mail\Mailables\Address('' . $this->cco, 'Copia Oculta de Bitflow') // Correo de copia oculta
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        
        return new Content(
            markdown: 'emails.cotizacion',
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
