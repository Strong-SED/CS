<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Laborantin extends User
{
    protected $table = 'users';

    protected static function booted()
    {
        static::addGlobalScope('role', function (Builder $builder) {
            $builder->where('role', User::ROLE_LABORANTIN);
        });
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->attributes['role'] = User::ROLE_LABORANTIN;
    }

    // Relations spécifiques aux laborantins
    public function centreDeSante(){
        return $this->belongsTo(CentreDeSante::class , 'centre_de_sante_id');
    }

    public function analyses()
    {
        return $this->hasMany(Analyse::class);
    }
}
