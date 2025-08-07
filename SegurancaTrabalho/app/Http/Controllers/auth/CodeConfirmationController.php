<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CodeConfirmationController extends Controller
{
    // Tela de confirmação do código
    public function showConfirmForm()
    {
        return view('pages.auth.confirm-codigo');
    }

    // Verifica o código e redefine a senha
    public function resetPassword(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric',
            'password' => 'required|confirmed|min:6'
        ]);

        $sessionCode = Session::get('reset_code');
        $email = Session::get('reset_email');

        if (!$sessionCode || !$email) {
            return redirect()->route('password.request')->withErrors(['code' => 'Código expirado ou inválido.']);
        }

        if ($request->code != $sessionCode) {
            return back()->withErrors(['code' => 'Código incorreto.']);
        }

        $user = User::where('email', $email)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            // Limpa sessão após uso
            Session::forget(['reset_code', 'reset_email']);

            return redirect()->route('login')->with('success', 'Senha redefinida com sucesso!');
        }

        return back()->withErrors(['email' => 'Usuário não encontrado.']);
    }
}
