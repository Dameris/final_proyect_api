<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'email' => ["required", "email:rfc"],
            'password' => ["required", "min:8", "regex:/[A-Z]/", "regex:/[*, +, -, ., @, #, %, &, _, ~, ^, \/ ,<, >]/"],
            'first_name' => ["required", "min:1", "regex:/^[A-Za-zÀ-ÿ\s'-]+$/"],
            'last_name' => ["required", "min:1", "regex:/^[A-Za-zÀ-ÿ\s'-]+$/"],
            'gender' => ["required", "in:Male, Female"],
            'profile_photo_path' => ["nullable", "mimes:jpg,jpeg,png,gif"]
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if (
            $input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail && $user instanceof User
        ) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'email' => $input['email'],
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'gender' => $input['gender'],
                'profile_photo_path' => null,
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(MustVerifyEmail&User $user, array $input): void
    {
        $user->forceFill([
            'email' => $input['email'],
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'gender' => $input['gender'],
            'profile_photo_path' => null,
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
