<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;

class CodigoRecuperacaoEmail extends Mailable
{
    public int $code;

    public function __construct(int $code)
    {
        $this->code = $code;
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Código de Recuperação de Senha');
    }

    public function content(): Content
{
    return new Content(
            view: 'pages.emails.codigo_recuperacao', // <- caminho da sua view
            with: ['codigo' => $this->code]
        );
    }
    /*public function content(): Content
    {
        return new Content(view: 'pages.emails.codigo_recuperacao', with: ['code' => $this->code]);
        /*return new Content(
            markdown: 'emails.codigo-recuperacao', // gerado pelo --markdown
            with: ['code' => $this->code]
        );
    }*/
}
