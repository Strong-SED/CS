<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DossierMedical extends Model
{
    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class , 'patient_id');
    }
}
