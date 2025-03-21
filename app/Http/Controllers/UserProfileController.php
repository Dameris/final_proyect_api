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
    public function update(Request $request)
    {
        $user = $request->user();

        $user->update($request->only('first_name', 'last_name', 'email', 'gender'));

        if ($request->hasFile('profile_photo_path')) {
            $user->updateProfilePhoto($request->file('profile_photo_path'));
        }

        return inertia('UserProfile', [
            'user' => $user,
        ]);
    }
}
