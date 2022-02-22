<?php

namespace App\Repositories;

use App\Medication;

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
}
