export const AnalyseMedicale = {
    // Analyses biologiques
    NFS: { name: 'Numération Formule Sanguine', price: 5000.00 },
    GLYCEMIE: { name: 'Glycémie', price: 3000.00 },
    CREATININE: { name: 'Créatininémie', price: 4000.00 },
    BILIRUBINE: { name: 'Bilirubine totale et conjuguée', price: 5000.00 },
    TRANSAMINASES: { name: 'Transaminases (ASAT/ALAT)', price: 4500.00 },
    TROPO: { name: 'Troponine', price: 9000.00 },
    CRP: { name: 'CRP ultrasensible', price: 6000.00 },

    // Analyses parasitologiques
    GOUTTE_EP: { name: 'Goutte épaisse', price: 2000.00 },
    FROTTIS: { name: 'Frottis sanguin', price: 2500.00 },
    EXAMEN_SELLES: { name: 'Examen parasitologique des selles', price: 3500.00 },
    EXAMEN_URINES: { name: 'Examen cytobactériologique des urines', price: 3500.00 },

    // Sérologies
    VIH: { name: 'Test VIH', price: 3500.00 },
    HEPATITE_B: { name: 'Ag HBs', price: 5000.00 },
    HEPATITE_C: { name: 'Anti-HCV', price: 6000.00 },
    SYPHILIS: { name: 'TPHA/VDRL', price: 4000.00 },
    PALUDISME: { name: 'Test rapide paludisme', price: 3000.00 },

    // Autres
    ELECTROPHORESE: { name: 'Electrophorèse de l\'hémoglobine', price: 7000.00 },
    GROUPAGE: { name: 'Groupage sanguin', price: 3000.00 },
    COAGULOGRAMME: { name: 'Taux de prothrombine', price: 5000.00 },
    FER_SERIQUE: { name: 'Fer sérique', price: 5000.00 },

    getAllAnalyses() {
        return Object.entries(this)
            .filter(([key]) => key !== 'getAllAnalyses')
            .map(([key, value]) => ({
                key,
                name: value.name,
                price: value.price
            }));
    },

    // Ajoutez cette méthode à votre objet AnalyseMedicale
    getAnalysesWithPrices() {
        const result = {};
        for (const [key, value] of Object.entries(this)) {
            if (key !== 'getAllAnalyses' && key !== 'getAnalysesWithPrices') {
                result[key] = {
                    name: value.name,
                    price: value.price
                };
            }
        }
        return result;
    }
};
