<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'regex:/^(05\d{8}|\+9715\d{8})$/', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $normalizedPhone = $this->normalizePhoneNumber($input['phone']);


        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $normalizedPhone ?? null,
            'password' => Hash::make($input['password']),
        ]);
    }

    function normalizePhoneNumber($phone)
    {
        // Remove all non-numeric characters except the '+' sign
        $phone = preg_replace('/[^\d+]/', '', $phone);
        
        // If the phone number starts with '05', replace it with '+9715'
        if (strpos($phone, '05') === 0) {
            $phone = '+971' . substr($phone, 1);
        }
        // If the phone number starts with '+971' but not '+9715', adjust it
        elseif (strpos($phone, '+971') === 0 && strpos($phone, '+9715') !== 0) {
            $phone = '+9715' . substr($phone, 4);
        }
        // If the phone number doesn't start with '+971' or '05', prepend '+9715'
        elseif (strpos($phone, '+9715') !== 0) {
            $phone = '+971' . ltrim($phone, '+');
        }
        
        return $phone;
    }


    
    
}
