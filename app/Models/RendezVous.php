<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RendezVous extends Model
{
    protected $table = 'rendez_vous';

    protected $casts = [
        'date_heure' => 'datetime',
        'reporté_le' => 'datetime',
    ];

    protected $fillable = [
        'patient_id',
        'medecin_id',
        'centre_de_sante_id',
        'date_heure',
        'motif',
        'etat',
        'motif_report',
        'reporté_le',
    ];

    public function medecin()
    {
        return $this->belongsTo(Medecin::class, 'medecin_id');
    }

    // Relation avec le patient
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
