<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Centre_patient extends Model
{
    use SoftDeletes;
    protected $table = 'centre_patient';

    protected $fillable = [
        'centre_de_sante_id',
        'patient_id',
        'created_by_user_id',
        'status',
        'date_enregistrement',
    ];

    protected $casts = [
        'date_enregistrement' => 'datetime',
    ];

    public function centreDeSante(): BelongsTo
    {
        return $this->belongsTo(CentreDeSante::class, 'centre_de_sante_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(Secretaire::class, 'created_by_user_id');
    }



}
