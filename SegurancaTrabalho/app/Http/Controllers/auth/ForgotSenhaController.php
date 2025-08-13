<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotSenhaController extends Controller
{
    /**
     * Mostra o formulário de "Esqueci minha senha"
     */
    public function showLinkRequestForm()
    {
        return view('pages.auth.forgot-password');
    }

    /**
     * Envia o e-mail com o link de redefinição
     */
    public function sendResetLinkEmail(Request $request)
    {
        // validação do e-mail
        $request->validate([
            'email' => 'required|email',
        ]);

        // tenta enviar o link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // retorna mensagem de sucesso ou erro
        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }
}
