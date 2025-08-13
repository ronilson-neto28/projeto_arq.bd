<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class ResetPasswordController extends Controller
{
    public function showResetForm(string $token)
    {
        // Mostra o form com token e email (email virá via query string no link)
        $email = request('email');
        return view('pages.auth.reset-password', compact('token', 'email'));
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token'                => ['required'],
            'email'                => ['required', 'email'],
            'password'             => ['required', 'confirmed', 'min:6'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', __($status));
        }

        return back()->with('error', __($status));
    }
}
