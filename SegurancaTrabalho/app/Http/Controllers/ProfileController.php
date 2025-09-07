<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Exibe o perfil do usuário autenticado
     */
    public function show()
    {
        $user = Auth::user();
        return view('pages.general.profile', compact('user'));
    }

    /**
     * Atualiza as informações do perfil do usuário
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Atualiza a senha do usuário
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'updatePassword')
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Senha atualizada com sucesso!');
    }

    /**
     * Faz upload da foto de perfil
     */
    public function uploadPhoto(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'profile_photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Remove a foto anterior se existir
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Faz upload da nova foto
        $path = $request->file('profile_photo')->store('profile_photos', 'public');

        $user->update([
            'profile_photo' => $path,
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Foto de perfil atualizada com sucesso!');
    }

    /**
     * Remove a foto de perfil
     */
    public function removePhoto()
    {
        $user = Auth::user();

        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $user->update([
            'profile_photo' => null,
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Foto de perfil removida com sucesso!');
    }
}