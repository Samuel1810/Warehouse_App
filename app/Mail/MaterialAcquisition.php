<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MaterialAcquisition extends Mailable
{
    use Queueable, SerializesModels;

    public $material;
    public $quantidadeMovimentada;
    public $tipoMovimento;
    public $dataMovimento;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($material, $quantidadeMovimentada, $tipoMovimento, $dataMovimento, $user)
    {
        $this->material = $material;
        $this->quantidadeMovimentada = $quantidadeMovimentada;
        $this->tipoMovimento = $tipoMovimento;
        $this->dataMovimento = $dataMovimento;
        $this->user = $user;
    }

    public function build()
    {
        return $this->view('emails.material-movement');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Aquisição de Material',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.material-movement',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
