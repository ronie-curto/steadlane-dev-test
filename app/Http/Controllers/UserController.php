<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Users\SignUpRequest;
use App\Notifications\WelcomeNotification;
use App\Repositories\UserRepository;

class UserController extends Controller
{

    /**
     * Construc dependency injection
     *
     * @param  UserRepository $UserRepository
     * 
     */
    public function __construct(UserRepository $UserRepository) 
    {
        $this->UserRepository = $UserRepository;
    }

    /**
     * Create new user upon patient sign up
     *
     * @param  SignUpRequest  $request
     * @return json
     */
    public function signUp(SignUpRequest $request)
    {
        //sending data to custom repository to keep controller clean and 
        $user = $this->UserRepository->store($request);

        //generating token for newly created user and return it
        $token = $user->createToken('auth_token')->plainTextToken;

        //notify user
        $user->notify(new WelcomeNotification);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 201);
    }
}
