<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Secretaire extends User
{
    protected $table = 'users';

    protected static function booted()
    {
        static::addGlobalScope('role', function (Builder $builder) {
            $builder->where('role', User::ROLE_SECRETAIRE);
        });
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->attributes['role'] = User::ROLE_SECRETAIRE;
    }

    // Relations spécifiques aux secrétaires
    public function centreDeSante()
    {
        return $this->belongsTo(CentreDeSante::class, 'centre_de_sante_id');
    }

    public function factures()
    {
        return $this->hasMany(Facture::class);
    }

    // Relation avec les enregistrements CentrePatient qu'il a créés
    public function enregistrementsPatients()
    {
        return $this->hasMany(Centre_patient::class, 'created_by_user_id');
    }

    public function patients()
    {
        return $this->belongsToMany(Patient::class, 'centre_patient', 'created_by_user_id', 'patient_id')
            ->withPivot('centre_de_sante_id', 'date_enregistrement');
    }
}
