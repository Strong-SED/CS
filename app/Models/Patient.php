<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{

    protected $fillable = [
        'nom',
        'prenom',
        'date_naissance',
        'genre',
        'adresse',
        'telephone',
        'email',
        'npi',
        'status'
    ];

    protected $casts = [
        'date_naissance' => 'date'
    ];


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

    public function centres()
    {
        return $this->belongsToMany(CentreDeSante::class, 'centre_patient', 'patient_id', 'centre_de_sante_id')
            ->withPivot('created_by_user_id', 'date_enregistrement');
    }

    public function secretaires()
    {
        return $this->belongsToMany(User::class, 'centre_patient', 'patient_id', 'created_by_user_id')
            ->where('role', User::ROLE_SECRETAIRE);
    }
}
