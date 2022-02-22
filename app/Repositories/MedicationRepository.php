<?php

namespace App\Repositories;

use App\Medication;
use App\Patient;

class MedicationRepository 
{
    /**
     * Get a collection of Medication and filter if search string is provided
     *
     * @param  MedicationSearchRequest  $request
     * @return collection
     */
    public function search($searchData)
    {
        if(is_null($searchData->search))
            return Medication::all();

        return Medication::where('name', 'like', '%' . $searchData->search . '%')->get();
    }


    /**
     * Get a collection of Medication of a patient
     *
     * @param  int  $patientId
     * @return collection
     */
    public function searchByPatient($patientId)
    {
        return Patient::where('id', $patientId)->with('medications')->first()->medications;
    }


    /**
     * Store medication of a patient with additional info.
     *
     * @param  MedicationPatientStoreRequest  $request
     * @param  int  $patientId
     * @return Medication + Pivot
     */
    public function storePatientMedication($request, $patientId)
    {
        $patient = Patient::findOrFail($patientId);

        $patient->medications()->attach($request->medicationId, [
            'dosage' => $request->dosage,
            'startDate' => $request->startDate, //can be treated as carbon dates
            'completionDate' => $request->completionDate //can be treated as carbon dates
        ]);

        return $patient->medications()->where('medications.id', $request->medicationId)->first();
    }


    /**
     * Update medication data of a patient.
     *
     * @param  MedicationPatientUpdateRequest  $request
     * @param  int  $patientId
     * @return Medication + Pivot
     */
    public function updatePatientMedication($request, $patientId, $medicationId)
    {
        $patient = Patient::findOrFail($patientId);

        //updates all records with same Medication ID. Careful about this and check business requirements
        $patient->medications()->updateExistingPivot($medicationId, [
            'dosage' => $request->dosage,
            'startDate' => $request->startDate, //can be treated as carbon dates
            'completionDate' => $request->completionDate //can be treated as carbon dates
        ]);

        return $patient->medications()->where('medications.id', $medicationId)->first();
    }
}
