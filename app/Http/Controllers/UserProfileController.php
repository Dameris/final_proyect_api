<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function showUserProfile()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'No estás autenticado');
        }

        return Inertia::render('UserProfile', [
            'user' => [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'gender' => $user->gender,
                'profile_photo_path' => $user->profile_photo_path ? Storage::url($user->profile_photo_path) : null,
            ],
            'errors' => []
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return inertia("Users/Edit", ["user" => $user]);
    }

    /**
     * Update a User.
     */
    public function update(UpdateProfileRequest $request, User $user)
    {
        // Primero, validamos los datos del formulario.
        $validated = $request->validated();

        // Si se ha subido una nueva foto de perfil, la procesamos.
        if ($request->hasFile('profile_photo_path')) {
            // Eliminamos la foto anterior si existe.
            if ($user->profile_photo_path) {
                Storage::disk('public/img')->delete($user->profile_photo_path);
            }

            // Almacenamos la nueva foto y guardamos su ruta.
            $path = $request->file('profile_photo_path')->store('profile-photos', 'public/img');
            $validated['profile_photo_path'] = $path;
        }

        // Actualizamos los datos del usuario con los datos validados, incluyendo la foto si fue cargada.
        $user->update($validated);

        return redirect()->back()->with('success', 'User data updated successfully');
    }
}
