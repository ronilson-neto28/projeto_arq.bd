<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use MailerSend\LaravelDriver\MailerSendTrait;

class CodigoRecuperacaoEmail extends Mailable
{
    use Queueable, SerializesModels, MailerSendTrait;

    public $codigo;

    /**
     * Create a new message instance.
     */
    public function __construct($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Código de Recuperação de Senha')
                    ->view('pages.emails.codigo_recuperacao');
    }
}
