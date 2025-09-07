<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('pages.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email:rfc,dns', // Validação mais rigorosa de email
                'max:255',
                'unique:users'
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters() // Deve conter letras
                    ->mixedCase() // Deve conter maiúsculas e minúsculas
                    ->numbers() // Deve conter números
                    ->symbols() // Deve conter símbolos
                    ->uncompromised() // Não deve estar em listas de senhas vazadas
            ],
        ], [
            'email.email' => 'Por favor, insira um endereço de email válido.',
            'email.unique' => 'Este email já está cadastrado no sistema.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.letters' => 'A senha deve conter pelo menos uma letra.',
            'password.mixed_case' => 'A senha deve conter pelo menos uma letra maiúscula e uma minúscula.',
            'password.numbers' => 'A senha deve conter pelo menos um número.',
            'password.symbols' => 'A senha deve conter pelo menos um caractere especial.',
            'password.uncompromised' => 'Esta senha foi encontrada em vazamentos de dados. Por favor, escolha uma senha mais segura.',
            'password.confirmed' => 'A confirmação da senha não confere.',
        ]);

        // Criar usuário sem fazer login automático
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_verified' => false,
        ]);
    
        Log::info('Usuário criado aguardando verificação: ', ['id' => $user->id, 'email' => $user->email]);

        // Gerar e enviar código de verificação
        $verificationCode = $user->generateVerificationCode();
        
        try {
            Mail::to($user->email)->send(new VerificationCodeMail($user, $verificationCode));
            
            // Armazenar o ID do usuário na sessão para a próxima etapa
            session(['pending_verification_user_id' => $user->id]);
            return redirect()->route('verification.show')
                ->with('success', 'Cadastro realizado! Verifique seu email e insira o código de verificação.');
                
        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de verificação: ' . $e->getMessage());
            
            // Se falhar o envio do email, deletar o usuário criado
            $user->delete();
            
            return back()->withErrors([
                'email' => 'Erro ao enviar email de verificação. Tente novamente.'
            ])->withInput();
        }
        
    }
    
    /**
     * Exibe o formulário de verificação de código
     */
    public function showVerificationForm()
    {
        if (!session('pending_verification_user_id')) {
            return redirect()->route('register')->withErrors([
                'error' => 'Sessão expirada. Por favor, faça o cadastro novamente.'
            ]);
        }
        
        return view('pages.auth.verify-code');
    }
    
    /**
     * Processa a verificação do código
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string|min:6|max:10',
        ], [
            'verification_code.required' => 'Por favor, insira o código de verificação.',
            'verification_code.min' => 'O código deve ter pelo menos 6 dígitos.',
            'verification_code.max' => 'O código não pode ter mais de 10 caracteres.',
        ]);
        
        $userId = session('pending_verification_user_id');
        
        if (!$userId) {
            return redirect()->route('register')->withErrors([
                'error' => 'Sessão expirada. Por favor, faça o cadastro novamente.'
            ]);
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('register')->withErrors([
                'error' => 'Usuário não encontrado. Por favor, faça o cadastro novamente.'
            ]);
        }
        
        // Verificar se o código expirou
        if ($user->isVerificationCodeExpired()) {
            return back()->withErrors([
                'verification_code' => 'Código de verificação expirado. Solicite um novo código.'
            ]);
        }
        
        // Verificar o código
        if ($user->verifyCode($request->verification_code)) {
            // Código válido - fazer login e limpar sessão
            session()->forget('pending_verification_user_id');
            
            // Regenerar sessão para prevenir problemas de CSRF
            request()->session()->regenerate();
            
            Auth::login($user);
            
            Log::info('Usuário verificado e logado com sucesso: ', ['id' => $user->id, 'email' => $user->email]);
            
            // Redirecionar para a página principal (raiz)
            return redirect('/')->with('success', 'Cadastro realizado com sucesso! Bem-vindo ao sistema.');
        } else {
            Log::warning('Código de verificação inválido tentado: ', [
                'user_id' => $user->id,
                'codigo_tentado' => $request->verification_code,
                'codigo_armazenado' => $user->email_verification_code
            ]);
            
            return back()->withErrors([
                'verification_code' => 'Código de verificação inválido. Verifique se digitou corretamente e tente novamente.'
            ]);
        }
    }
    
    /**
     * Reenvia o código de verificação
     */
    public function resendCode()
    {
        $userId = session('pending_verification_user_id');
        
        if (!$userId) {
            return redirect()->route('register')->withErrors([
                'error' => 'Sessão expirada. Por favor, faça o cadastro novamente.'
            ]);
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('register')->withErrors([
                'error' => 'Usuário não encontrado. Por favor, faça o cadastro novamente.'
            ]);
        }
        
        // Gerar novo código
        $verificationCode = $user->generateVerificationCode();
        
        try {
            Mail::to($user->email)->send(new VerificationCodeMail($user, $verificationCode));
            
            return back()->with('success', 'Novo código de verificação enviado para seu email.');
            
        } catch (\Exception $e) {
            Log::error('Erro ao reenviar código de verificação: ' . $e->getMessage());
            
            return back()->withErrors([
                'error' => 'Erro ao enviar novo código. Tente novamente.'
            ]);
        }
    }
}
