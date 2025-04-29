<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Medecin extends User
{
    protected $table = 'users';

    protected static function booted()
    {
        static::addGlobalScope('role', function (Builder $builder) {
            $builder->where('role', User::ROLE_MEDECIN);
        });
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->attributes['role'] = User::ROLE_MEDECIN;
    }

    // Relations spécifiques aux médecins
    public function rendezVous()
    {
        return $this->hasMany(RendezVous::class);
    }

    public function centreDeSante(){
        return $this->belongsTo(CentreDeSante::class , 'centre_de_sante_id');
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'medecin_id');
    }
}
