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

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
