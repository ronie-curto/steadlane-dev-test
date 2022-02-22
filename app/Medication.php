<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    /**
     * Relationship - The patients assigned to a medication
     */
    public function patients()
    {
        return $this->belongsToMany('App\Patient')->withPivot('dosage', 'startDate', 'completionDate')->withTimestamps();
    }

}
