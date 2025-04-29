<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CentreDeSante extends Model
{
    public function adminCS()
    {
        return $this->belongsTo(AdminCS::class, 'created_by_user_id');
    }

    public function medecins()
    {
        return $this->hasMany(Medecin::class);
    }

    public function secretaires()
    {
        return $this->hasMany(Secretaire::class);
    }

    public function laborantins()
    {
        return $this->hasMany(Laborantin::class);
    }

    // Relation many-to-many avec les patients
    public function patients()
    {
        return $this->belongsToMany(Patient::class, 'centre_patient')
                    ->withPivot('created_by_user_id', 'date_enregistrement');
    }
}
