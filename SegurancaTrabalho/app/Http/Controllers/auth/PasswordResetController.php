<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\CodigoRecuperacaoEmail;

class PasswordResetController extends Controller
{
    // Tela de solicitação de recuperação
    public function showForgotForm()
    {
        return view('pages.auth.forgot-senha');
    }

    // Envia o código para o e-mail
    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $code = rand(100000, 999999);

        // Salva o código na sessão temporariamente
        Session::put('reset_email', $request->email);
        Session::put('reset_code', $code);

        // Aqui você pode enviar o código por e-mail de verdade.
        // Exemplo de simulação (sem envio real):
        Mail::to($request->email)->send(new CodigoRecuperacaoEmail($code));

        
        return redirect()->route('password.confirm')->with('success', 'Código enviado para seu e-mail!');
    }
}