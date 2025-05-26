<?php

namespace App\Enums;

enum AnalyseMedicale: string
{
    // Analyses biologiques
    case NFS = 'Numération Formule Sanguine';
    case GLYCEMIE = 'Glycémie';
    case CREATININE = 'Créatininémie';
    case BILIRUBINE = 'Bilirubine totale et conjuguée';
    case TRANSAMINASES = 'Transaminases (ASAT/ALAT)';
    case TROPO = 'Troponine';
    case CRP = 'CRP ultrasensible';

    // Analyses parasitologiques
    case GOUTTE_EP = 'Goutte épaisse';
    case FROTTIS = 'Frottis sanguin';
    case EXAMEN_SELLES = 'Examen parasitologique des selles';
    case EXAMEN_URINES = 'Examen cytobactériologique des urines';

    // Sérologies
    case VIH = 'Test VIH';
    case HEPATITE_B = 'Ag HBs';
    case HEPATITE_C = 'Anti-HCV';
    case SYPHILIS = 'TPHA/VDRL';
    case PALUDISME = 'Test rapide paludisme';

    // Autres
    case ELECTROPHORESE = 'Electrophorèse de l\'hémoglobine';
    case GROUPAGE = 'Groupage sanguin';
    case COAGULOGRAMME = 'Taux de prothrombine';
    case FER_SERIQUE = 'Fer sérique';

    public function getPrice(): float
    {
        return match ($this) {
            self::NFS => 5000.00,
            self::GLYCEMIE => 3000.00,
            self::CREATININE => 4000.00,
            self::BILIRUBINE => 5000.00,
            self::TRANSAMINASES => 4500.00,
            self::TROPO => 9000.00,
            self::CRP => 6000.00,

            self::GOUTTE_EP => 2000.00,
            self::FROTTIS => 2500.00,
            self::EXAMEN_SELLES => 3500.00,
            self::EXAMEN_URINES => 3500.00,

            self::VIH => 3500.00,
            self::HEPATITE_B => 5000.00,
            self::HEPATITE_C => 6000.00,
            self::SYPHILIS => 4000.00,
            self::PALUDISME => 3000.00,

            self::ELECTROPHORESE => 7000.00,
            self::GROUPAGE => 3000.00,
            self::COAGULOGRAMME => 5000.00,
            self::FER_SERIQUE => 5000.00,
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getAnalysesWithPrices(): array
    {
        $analysesWithPrices = [];
        foreach (self::cases() as $case) {
            $analysesWithPrices[$case->name] = [
                'name' => $case->value,
                'price' => $case->getPrice(),
            ];
        }
        return $analysesWithPrices;
    }
}
