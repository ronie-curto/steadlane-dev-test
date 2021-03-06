<?php

namespace App\Http\Requests\Medications;

use Illuminate\Foundation\Http\FormRequest;

class MedicationPatientStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'medicationId' => 'required|integer',
            'dosage' => 'required|string',
            'startDate' => 'required|date',
            'completionDate' => 'nullable|date'
        ];
    }
}
