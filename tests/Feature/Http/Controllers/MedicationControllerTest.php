<?php

namespace Tests\Feature\Http\Controllers;

use App\Medication;
use App\Patient;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
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


    /**
     * Store medication for a Patient
     *
     * @return void
     */
    public function test_it_stores_medication_for_patient()
    {
        $user = factory(User::class)->create();
        $patient = factory(Patient::class)->create();
        $medication = factory(Medication::class)->create();

        $response = $this
            ->actingAs($user)
            ->json('POST', "/patients/{$patient->id}/medications/store", [
                'dosage' => 'dose',
                'startDate' => date('Y-m-d'),
                'completionDate' => date('Y-m-d'),
                'medicationId' => $medication->id,
            ]);

        $response
            ->assertOk()
            ->assertJson([
                'data' => [
                    'dosage' => 'dose',
                    'startDate' => date('Y-m-d'),
                    'completionDate' => date('Y-m-d'),
                    'code' => $medication->id,
                    'name' => $medication->name,
                ]
            ]);
    }


    /**
     * Check if list all medications of a patient
     *
     * @return void
     */
    public function test_it_lists_medications_of_patient()
    {
        $user = factory(User::class)->create();
        $patient = factory(Patient::class)->create();
        $medication = factory(Medication::class)->create();

        $responseStoreMedication = $this
            ->actingAs($user)
            ->json('POST', "/patients/{$patient->id}/medications/store", [
                'dosage' => 'dose',
                'startDate' => date('Y-m-d'),
                'completionDate' => date('Y-m-d'),
                'medicationId' => $medication->id,
            ]);

        $responseStoreMedication->assertOk();

        $responseListPatientMedication = $this
            ->actingAs($user)
            ->json('GET', "patients/{$patient->id}/medications");


        $responseListPatientMedication
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                'data' => [
                    [
                        'code',
                        'name',
                        'startDate',
                        'completionDate',
                        'dosage'
                    ]
                ]
            ]);
    }


    /**
     * Updates medication of a Patient
     *
     * @return void
     */
    public function test_it_updates_medication_of_patient()
    {
        $user = factory(User::class)->create();
        $patient = factory(Patient::class)->create();
        $medication = factory(Medication::class)->create();

        $responseStoreMedication = $this
            ->actingAs($user)
            ->json('POST', "/patients/{$patient->id}/medications/store", [
                'dosage' => 'dose',
                'startDate' => date('Y-m-d'),
                'completionDate' => date('Y-m-d'),
                'medicationId' => $medication->id,
            ]);

        $responseStoreMedication->assertOk();

        $responseUpdateMedication = $this
        ->actingAs($user)
        ->json('PATCH', "/patients/{$patient->id}/medications/{$medication->id}", [
            'dosage' => 'dosex',
            'startDate' => date('Y-m-d'),
            'completionDate' => date('Y-m-d')
        ]);

        $responseUpdateMedication
            ->assertOk()
            ->assertJson([
                'data' => [
                    'dosage' => 'dosex',
                    'startDate' => date('Y-m-d'),
                    'completionDate' => date('Y-m-d'),
                    'code' => $medication->id,
                    'name' => $medication->name,
                ]
            ]);

    }


    /**
     * Prevent empty fields upon creation of medication of a Patient
     *
     * @return void
     */
    public function test_it_prevents_empty_fields_store_medication_for_patient()
    {
        $user = factory(User::class)->create();
        $patient = factory(Patient::class)->create();
        $medication = factory(Medication::class)->create();

        $response = $this
            ->actingAs($user)
            ->json('POST', "/patients/{$patient->id}/medications/store", [
                'completionDate' => date('Y-m-d'),
                'medicationId' => $medication->id,
            ]);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('dosage');
    }


    /**
     * Prevent empty fields upon update of medication of a Patient
     *
     * @return void
     */
    public function test_it_prevents_empty_fields_update_medication_for_patient()
    {
        $user = factory(User::class)->create();
        $patient = factory(Patient::class)->create();
        $medication = factory(Medication::class)->create();

        $responseStoreMedication = $this
            ->actingAs($user)
            ->json('POST', "/patients/{$patient->id}/medications/store", [
                'dosage' => 'dose',
                'startDate' => date('Y-m-d'),
                'completionDate' => date('Y-m-d'),
                'medicationId' => $medication->id,
            ]);

        $responseStoreMedication->assertOk();

        $responseUpdateMedication = $this
        ->actingAs($user)
        ->json('PATCH', "/patients/{$patient->id}/medications/{$medication->id}", [
            'completionDate' => date('Y-m-d')
        ]);

        $responseUpdateMedication
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('dosage');
    }
}
