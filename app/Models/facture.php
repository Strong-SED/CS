<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facture extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'patient_id',
        'secretaire_id',
        'centre_de_sante_id',
        'numero_facture',
        'date_emission',
        'montant_total',
        'statut',
        'details'
    ];

    // Relation avec le patient
    public function patient()
    {
        return $this->belongsTo(Patient::class , 'patient_id');
    }

    // Relation avec le secrétaire
    public function secretaire()
    {
        return $this->belongsTo(User::class, 'secretaire_id');
    }

    // Relation avec le centre de santé
    public function centre()
    {
        return $this->belongsTo(CentreDeSante::class);
    }

    // Scope pour les factures impayées
    public function scopeImpayees($query)
    {
        return $query->where('statut', '!=', 'paye');
    }
}
