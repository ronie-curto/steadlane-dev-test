<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicationPatient extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'code' => $this->id,
            'name' => $this->name,
            'startDate' => $this->pivot->startDate,
            'completionDate' => $this->pivot->completionDate,
            'dosage' => $this->pivot->dosage
        ];
    }
}
