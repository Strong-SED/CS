<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RendezVous extends Model
{
    public function medecin()
    {
        return $this->belongsTo(Medecin::class, 'medecin_id');
    }

    // Relation avec le patient
    public function patient()
    {
        return $this->belongsTo(Patient::class , 'patient_id');
    }
}
