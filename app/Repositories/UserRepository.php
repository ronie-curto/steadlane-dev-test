<?php

namespace App\Repositories;

use App\Patient;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserRepository 
{
    /**
     * Create new user upon patient sign up
     *
     * @param  SignUpRequest  $request
     * @return User
     */
    public function store($requestData)
    {
        //searching for patient with same e-mail. cannot create user without a patient first
        $patient = Patient::where('email', $requestData->email)->first();


        //if patient not found, throw http error 404 to the frontend or it's possible to send back custom json reporting failure 
        if (is_null($patient))
            abort(404, 'Patient not found.');
    

        return User::create([
            'name' => $patient->getFullName(),
            'email' => $patient->email,
            'password' => Hash::make($requestData->password)
        ]);
    }
}