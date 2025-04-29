<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class SuperAdmin extends User
{
    protected $table = 'users';

    protected static function booted()
    {
        static::addGlobalScope('role', function (Builder $builder) {
            $builder->where('role', User::ROLE_SUPER_ADMIN);
        });
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->attributes['role'] = User::ROLE_SUPER_ADMIN;
    }

    public function adminCS(){
        return $this->hasMany(AdminCS::class);
    }
}
