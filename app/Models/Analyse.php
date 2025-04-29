<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Analyse extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'consultation_id',
        'laborantin_id',
        'centre_de_sante_id',
        'type_analyse',
        'resultat',
        'date_analyse',
        'statut',
    ];

    // Relation avec la consultation
    public function consultation()
    {
        return $this->belongsTo(Consultation::class , 'consultation_id');
    }

    // Relation avec le laborantin
    public function laborantin()
    {
        return $this->belongsTo(Laborantin::class, 'laborantin_id');
    }

    // Relation avec le centre de santé
    public function centre()
    {
        return $this->belongsTo(CentreDeSante::class , 'centre_de_sante_id');
    }

}
