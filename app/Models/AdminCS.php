<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class AdminCS extends User
{
    protected $table = 'users';

    protected static function booted()
    {
        static::addGlobalScope('role', function (Builder $builder) {
            $builder->where('role', User::ROLE_ADMIN_CS);
        });
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->attributes['role'] = User::ROLE_ADMIN_CS;
    }

    // Relation spécifique à l'admin CS
    public function centreDeSante()
    {
        return $this->hasOne(CentreDeSante::class);
    }

    public function superAdmin(){
        return $this->belongsTo(SuperAdmin::class);
    }

}
