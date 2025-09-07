<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo',
        'email_verification_code',
        'email_verification_code_expires_at',
        'is_verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'email_verification_code_expires_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
        ];
    }

    /**
     * Gera um código de verificação de 6 dígitos
     */
    public function generateVerificationCode(): string
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $this->update([
            'email_verification_code' => $code,
            'email_verification_code_expires_at' => now()->addMinutes(15), // Expira em 15 minutos
            'is_verified' => false,
        ]);
        
        return $code;
    }

    /**
     * Verifica se o código de verificação é válido
     */
    public function verifyCode(string $code): bool
    {
        // Limpar espaços e caracteres especiais do código recebido
        $cleanCode = trim(preg_replace('/[^0-9]/', '', $code));
        $storedCode = trim($this->email_verification_code);
        
        // Debug: Log dos valores para comparação
        \Log::info('Verificação de código:', [
            'codigo_original' => $code,
            'codigo_limpo' => $cleanCode,
            'codigo_armazenado' => $storedCode,
            'expira_em' => $this->email_verification_code_expires_at,
            'ainda_valido' => $this->email_verification_code_expires_at ? $this->email_verification_code_expires_at->isFuture() : false,
            'codigos_iguais' => $storedCode === $cleanCode
        ]);
        
        if ($storedCode === $cleanCode && 
            $this->email_verification_code_expires_at && 
            $this->email_verification_code_expires_at->isFuture()) {
            
            $this->update([
                'email_verified_at' => now(),
                'email_verification_code' => null,
                'email_verification_code_expires_at' => null,
                'is_verified' => true,
            ]);
            
            return true;
        }
        
        return false;
    }

    /**
     * Verifica se o código de verificação expirou
     */
    public function isVerificationCodeExpired(): bool
    {
        return $this->email_verification_code_expires_at && 
               $this->email_verification_code_expires_at->isPast();
    }

    public function sendPasswordResetNotification($token): void
    {
        $url = route('password.reset', [
            'token' => $token,
            'email' => $this->email,
        ]);

        $notification = new ResetPasswordNotification($token);
        // Força a URL a ser a sua (algumas versões permitem setUrl; se não, crie notification custom)
        if (method_exists($notification, 'createUrlUsing')) {
            ResetPasswordNotification::createUrlUsing(function() use ($url) { return $url; });
        }

        $this->notify($notification);
    }
}
