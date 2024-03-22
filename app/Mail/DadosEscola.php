<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Message;


class DadosEscola extends Mailable
{
    use Queueable, SerializesModels;

    public $dados;
    /**
     * Create a new message instance.
     */
    public function __construct($dados)
    {
        $this->dados = $dados;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Dados Escola',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.DadosEscola',
        );
    }

    public function build()
    {
        $image_path = public_path('mail/logo3.png');
        $base64Image = base64_encode(file_get_contents($image_path));
        return $this->view('mail.DadosEscola')
        ->subject('Cadastro - I Olimpíadas Científicas do Sertão Produtivo')
        ->with([
            'logo' => $base64Image
        ]);
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
