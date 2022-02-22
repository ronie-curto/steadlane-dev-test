<?php

namespace App\Http\Controllers;

use App\Http\Resources\Medication as MedicationResource;
use App\Repositories\MedicationRepository;
use App\Http\Requests\Medications\MedicationSearchRequest;
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
     * @return \Illuminate\Http\Response
     */
    public function index(MedicationSearchRequest $request)
    {
        return MedicationResource::collection($this->MedicationRepository->search($request));
    }
}
