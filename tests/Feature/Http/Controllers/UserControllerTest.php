<?php

namespace Tests\Feature\Http\Controllers;

use App\Patient;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;


    /**
     * Test user creation with a matching patient
     *
     * @return void
     */
    public function test_it_creates_a_user()
    {
        $patient = factory(Patient::class)->create();

        $response = $this->json('POST', '/users/sign-up', [
                'email' => $patient->email,
                'password' => 'password',
                'password_confirmation' => 'password'
            ]);

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'access_token',
                'token_type'
    	    ]);
    }

    /**
     * Test user creation with no matching patient
     *
     * @return void
     */
    public function test_it_doesnt_create_user_missing_patient()
    {
        $patient = factory(Patient::class)->create();

        $response = $this->json('POST', '/users/sign-up', [
                'email' => 'testx@gmail.com',
                'password' => 'password',
                'password_confirmation' => 'password'
            ]);

        $response->assertNotFound();
    }


    /**
     * Test user creation with empty fields
     *
     * @return void
     */
    public function test_it_prevents_empty_fields()
    {
        $patient = factory(Patient::class)->create();

        $response = $this->json('POST', '/users/sign-up', [
            'email' => '',
            'password' => '',
            'password_confirmation' => ''
        ]);


        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('email');
    }


    /**
     * Test user creation with passwords not matching
     *
     * @return void
     */
    public function test_it_prevents_unmatching_passwords()
    {
        $patient = factory(Patient::class)->create();

        $response = $this->json('POST', '/users/sign-up', [
            'email' => $patient->email,
            'password' => 'password',
            'password_confirmation' => 'passwordx'
        ]);


        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('password');
    }


    /**
     * Test repeated user creation to avoid duplicates if patient signs up again
     *
     * @return void
     */
    public function test_it_prevents_repetead_user()
    {
        $patient = factory(Patient::class)->create();

        $firstUserResponse = $this->json('POST', '/users/sign-up', [
            'email' => $patient->email,
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $firstUserResponse
            ->assertCreated()
            ->assertJsonStructure([
                'access_token',
                'token_type'
    	    ]);

        $secondUserResponse = $this->json('POST', '/users/sign-up', [
            'email' => $patient->email,
            'password' => 'password-x',
            'password_confirmation' => 'password-x'
        ]);

        $secondUserResponse
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('email');
    }

}
