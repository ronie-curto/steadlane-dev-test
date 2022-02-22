<?php

namespace App\Http\Controllers;

use App\Http\Resources\Medication as MedicationResource;
use App\Http\Resources\MedicationPatient as MedicationPatientResource;
use App\Repositories\MedicationRepository;
use App\Http\Requests\Medications\MedicationSearchRequest;
use App\Http\Requests\Medications\MedicationPatientStoreRequest;
use App\Http\Requests\Medications\MedicationPatientUpdateRequest;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    /**
     * Construc dependency injection
     *
     * @param  MedicationRepository $MedicationRepository
     * 
     */
    public function __construct(MedicationRepository $MedicationRepository) 
    {
        $this->MedicationRepository = $MedicationRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param  MedicationSearchRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(MedicationSearchRequest $request)
    {
        return MedicationResource::collection($this->MedicationRepository->search($request));
    }


    /**
     * Display a listing of the medications of a patient.
     *
     * @param  Request  $request
     * @param  int  $patientId
     * @return \Illuminate\Http\Response
     */
    public function listPatientMedication(Request $request, $patientId)
    {
        return MedicationPatientResource::collection($this->MedicationRepository->searchByPatient($patientId));
    }   


    /**
     * Store medication of a Patient
     *
     * @param  MedicationPatientStoreRequest  $request
     * @param  int  $patientId
     * @return \Illuminate\Http\Response
     */
    public function storePatientMedication(MedicationPatientStoreRequest $request, $patientId)
    {
        return new MedicationPatientResource($this->MedicationRepository->storePatientMedication($request, $patientId));
    }   


    /**
     * Update medication of a Patient
     *
     * @param  MedicationPatientUpdateRequest  $request
     * @param  int  $patientId
     * @param  int  $medicationId
     * @return \Illuminate\Http\Response
     */
    public function updatePatientMedication(MedicationPatientUpdateRequest $request, $patientId, $medicationId)
    {
        $medicationPatient = $this->MedicationRepository->updatePatientMedication($request, $patientId, $medicationId);

        return new MedicationPatientResource($medicationPatient);
    }
}
