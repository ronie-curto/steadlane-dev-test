<?php

namespace Tests\Feature\Http\Controllers;

use App\Medication;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicationControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Check auth requirement
     *
     * @return void
     */
    public function test_it_requires_authentication()
    {
        $response = $this->json('GET', '/medications');

        $response->assertUnauthorized();
    }


    /**
     * Check if list all medications
     *
     * @return void
     */
    public function test_it_lists_medications_correct_structure()
    {
        $user = factory(User::class)->create();
        factory(Medication::class, 10)->create();

        $response = $this
            ->actingAs($user)
            ->json('GET', '/medications');

        $response
            ->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data' => [
                    [
                        'code',
                        'name'
                    ]
                ]
            ]);
    }


    /**
     * Check if list all medications filtered
     *
     * @return void
     */
    public function test_it_lists_medications_filtered()
    {
        $user = factory(User::class)->create();
        factory(Medication::class, 10)->create();

        //creating unique medication with custom name to search later
        $medicationUnique = factory(Medication::class)->create();

        $response = $this
            ->actingAs($user)
            ->json('GET', '/medications?search=' . $medicationUnique->name);

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                'data' => [
                    [
                        'code',
                        'name'
                    ]
                ]
            ]);
    }
}
