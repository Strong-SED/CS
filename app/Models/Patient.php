<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    public function dossierMedical()
    {
        return $this->hasOne(DossierMedical::class);
    }

    public function rendezVous()
    {
        return $this->hasMany(RendezVous::class);
    }

    public function factures()
    {
        return $this->hasMany(Facture::class);
    }

    // Relation many-to-many avec les centres
    public function centres()
    {
        return $this->belongsToMany(CentreDeSante::class, 'centre_patient')
                    ->withPivot('created_by_user_id', 'date_enregistrement')
                    ->withTimestamps();
    }

    // Relation vers les secrétaires qui ont enregistré le patient
    public function secretaires()
    {
        return $this->hasManyThrough(
            User::class,
            'centre_patient',
            'patient_id',       // Clé étrangère sur centre_patient
            'id',              // Clé primaire de users
            'id',              // Clé primaire de patients
            'created_by_user_id' // Clé étrangère sur centre_patient
        )->where('role', User::ROLE_SECRETAIRE);
    }
}
