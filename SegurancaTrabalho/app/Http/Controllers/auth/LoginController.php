<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        // Validação básica
        $request->validate([
            'email' => 'required|email:rfc,dns',
            'password' => 'required|min:8',
        ]);

        // Rate limiting - máximo 5 tentativas por minuto por IP
        $key = 'login.' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            Log::warning('Rate limit exceeded for login', [
                'ip' => $request->ip(),
                'email' => $request->email,
                'user_agent' => $request->userAgent(),
                'seconds_remaining' => $seconds
            ]);
            
            throw ValidationException::withMessages([
                'email' => "Muitas tentativas de login. Tente novamente em {$seconds} segundos.",
            ]);
        }

        $credentials = $request->only('email', 'password');
        
        // Verificar se o usuário existe
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            // Incrementar rate limit mesmo para emails inexistentes
            RateLimiter::hit($key, 60);
            
            Log::warning('Login attempt with non-existent email', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            return back()->withErrors([
                'email' => 'E-mail ou senha inválidos.',
            ])->onlyInput('email');
        }
        
        // Verificar se o email foi verificado
        if (!$user->is_verified) {
            Log::info('Login attempt with unverified email', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            return back()->withErrors([
                'email' => 'Você precisa verificar seu email antes de fazer login. Verifique sua caixa de entrada.',
            ])->onlyInput('email');
        }

        // Tentar autenticar
        if (Auth::attempt($credentials, $request->remember)) {
            // Limpar rate limit em caso de sucesso
            RateLimiter::clear($key);
            
            // Regenerar sessão para prevenir session fixation
            $request->session()->regenerate();
            
            // Log de login bem-sucedido
            Log::info('Successful login', [
                'user_id' => Auth::id(),
                'email' => $request->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            return redirect()->intended('/');
        }

        // Incrementar rate limit em caso de falha
        RateLimiter::hit($key, 60);
        
        // Log de tentativa de login falhada
        Log::warning('Failed login attempt', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->withErrors([
            'email' => 'E-mail ou senha inválidos.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Log de logout
        Log::info('User logout', [
            'user_id' => Auth::id(),
            'email' => Auth::user()->email ?? 'unknown',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        Auth::logout();
        
        // Invalidar sessão e regenerar token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/auth/login')->with('success', 'Logout realizado com sucesso.');
    }
}
