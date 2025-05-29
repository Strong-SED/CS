<?php

namespace App\Models;

use App\Enums\AnalyseMedicale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consultation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'dossier_medical_id',
        'medecin_id',
        'date_consultation',
        'motif',
        'diagnostic',
        'traitement_prescrit',
        'observations',
        'poids',
        'taille',
        'temperature',
        'tension_arterielle',
        'status'    
    ];

    protected $casts = [
        'analyses' => 'array',
    ];

    public function getAnalysesPrescritesAttribute(): array
    {
        return array_map(
            fn ($code) => AnalyseMedicale::from($code)->value,
            $this->analyses ?? []
        );
    }

    public function setAnalysesPrescritesAttribute(array $analyses): void
    {
        $this->attributes['analyses'] = json_encode(
            array_map(
                fn ($analyse) => is_string($analyse)
                    ? AnalyseMedicale::from($analyse)->name
                    : $analyse->name,
                $analyses
            )
        );
    }

    public function dossierMedical()
    {
        return $this->belongsTo(DossierMedical::class , 'dossier_medical_id');
    }

    public function medecin()
    {
        return $this->belongsTo(Medecin::class , 'medecin_id');
    }

    public function analyses()
    {
        return $this->hasMany(Analyse::class);
    }
}
