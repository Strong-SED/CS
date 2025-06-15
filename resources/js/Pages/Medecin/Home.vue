<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3'

// Définition des props que ce composant recevra de Laravel
const props = defineProps({
    user: Object, // L'objet utilisateur authentifié
    stats: Object, // Les statistiques (patients_aujourdhui, rdv_programmes, consultations_effectuees, rdv_en_attente)
    rendezVousDuJour: Array, // La liste des rendez-vous du jour
});

// Accès direct aux props pour la réactivité et la clarté
const user = props.user;
const stats = props.stats;
const rendezVousDuJour = props.rendezVousDuJour;

// Fonction pour gérer le démarrage d'une consultation (à implémenter)
const startConsultation = (rdvId) => {
    // Ici, vous naviguerez vers la page de création/détail de consultation
    // avec l'ID du RDV ou du patient.
    // Exemple : router.visit(route('consultations.create', { rdv_id: rdvId }));
    console.log('Démarrer consultation pour RDV ID:', rdvId);
    // Ajoutez ici la logique de navigation réelle, par exemple avec Inertia.js router.
    // router.visit(route('consultations.start', { rdv: rdvId }));
};
</script>

<template>
    <Head title="Tableau de Bord Médecin" />

    <div class="px-6 py-6">
        <div
            class="min-h-[calc(100vh-8rem)] bg-gradient-to-br from-blue-50 to-white rounded-xl shadow-md border border-gray-200 p-10">
            <!-- En-tête -->
            <div class="mb-10 text-center">
                <h1 class="text-4xl font-bold text-blue-800 mb-2">Tableau de Bord Médical</h1>
                <!-- Utilisation de l'opérateur de chaînage optionnel (?) pour user.nom et user.prenom -->
                <p v-if="user" class="text-xl text-gray-600">Bienvenue Dr. {{ user.nom ?? 'Inconnu' }} {{ user.prenom ?? 'Inconnu' }}, voici votre activité du jour</p>
                <!-- Vérification pour centre_de_sante et utilisation de l'opérateur de chaînage optionnel -->
                <p v-if="user && user.centre_de_sante" class="text-lg text-gray-600 mt-2">Centre de santé : {{ user.centre_de_sante.nom ?? 'Non défini' }}</p>
                <p v-else-if="user" class="text-lg text-gray-600 mt-2">Centre de santé : Non attribué</p>
                <p v-else class="text-lg text-gray-600 mt-2">Veuillez vous connecter.</p>
            </div>

            <!-- Statistiques rapides - Assurez-vous que 'stats' est bien défini -->
            <div v-if="stats" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10 animous">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                    <div class="bg-blue-100 p-4 rounded-full mr-4">
                        <i class="fas fa-user-injured text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Patients vus aujourd'hui</p>
                        <p class="text-2xl font-bold">{{ stats.patients_aujourdhui ?? 0 }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                    <div class="bg-green-100 p-4 rounded-full mr-4">
                        <i class="fas fa-calendar-check text-2xl text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">RDV programmés</p>
                        <p class="text-2xl font-bold">{{ stats.rdv_programmes ?? 0 }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                    <div class="bg-purple-100 p-4 rounded-full mr-4">
                        <i class="fas fa-file-medical text-2xl text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Consultations effectuées</p>
                        <p class="text-2xl font-bold">{{ stats.consultations_effectuees ?? 0 }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                    <div class="bg-yellow-100 p-4 rounded-full mr-4">
                        <i class="fas fa-clock text-2xl text-yellow-600"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">RDV en attente</p>
                        <p class="text-2xl font-bold">{{ stats.rdv_en_attente ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div v-else class="text-center py-8 text-gray-500">
                <p>Chargement des statistiques...</p>
            </div>

            <!-- Actions principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animous2">
                <!-- Dossiers patients -->
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-folder-open text-xl text-blue-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Dossiers patients</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Accéder à l'historique médical complet des patients.</p>
                    <!-- <Link :href="route('patients.index')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm inline-flex items-center">
                        Rechercher <i class="fas fa-search ml-2"></i>
                    </Link> -->
                </div>

                <!-- Nouvelle consultation -->
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 p-3 rounded-full mr-4">
                            <i class="fas fa-stethoscope text-xl text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Consultation</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Accéder à l'onglet consultation pour achever consultation médicale en cours.</p>
                    <!-- <Link :href="route('consultations.create')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm inline-flex items-center">
                        Démarrer <i class="fas fa-play ml-2"></i>
                    </Link> -->
                </div>

                <!-- Planifier RDV -->
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 p-3 rounded-full mr-4">
                            <i class="fas fa-calendar-plus text-xl text-purple-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Planifier RDV</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Programmer un rendez-vous pour un patient ayant effectué une consultation.</p>
                    <!-- <Link :href="route('rendezvous.create')" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm inline-flex items-center">
                        Nouveau RDV <i class="fas fa-arrow-right ml-2"></i>
                    </Link> -->
                </div>

                <!-- Mon agenda -->
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-indigo-100 p-3 rounded-full mr-4">
                            <i class="fas fa-calendar-alt text-xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Mon agenda</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Consulter et gérer votre emploi du temps.</p>
                    <!-- <Link :href="route('agenda.show')" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm inline-flex items-center">
                        Voir agenda <i class="fas fa-calendar-day ml-2"></i>
                    </Link> -->
                </div>
            </div>

            <!-- Section Patients en attente / Rendez-vous du jour -->
            <!-- Assurez-vous que 'rendezVousDuJour' est un tableau avant de vérifier sa longueur -->
            <div class="mt-10 bg-white rounded-xl shadow-md border border-gray-200 p-6 animous3">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Rendez-vous du jour</h3>
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                        {{ rendezVousDuJour?.length ?? 0 }} RDV
                    </span>
                </div>

                <div v-if="rendezVousDuJour && rendezVousDuJour.length > 0" class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-gray-500 border-b">
                                <th class="pb-3">Heure</th>
                                <th class="pb-3">Patient</th>
                                <th class="pb-3">ID Patient</th>
                                <th class="pb-3">Motif</th>
                                <th class="pb-3">Statut</th>
                                <!-- <th class="pb-3">Actions</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="rdv in rendezVousDuJour" :key="rdv.id" class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-4">{{ rdv.heure ?? 'N/A' }}</td>
                                <td class="py-4">{{ rdv.patient_nom_complet ?? 'N/A' }}</td>
                                <td class="py-4">{{ rdv.patient_id ?? 'N/A' }}</td>
                                <td class="py-4">{{ rdv.motif ?? 'N/A' }}</td>
                                <td class="py-4">
                                    <span :class="{
                                        // Le statut est basé sur le champ 'etat' du RDV, mis à jour par le contrôleur
                                        'bg-green-100 text-green-800': rdv.has_consultation, // Si consultation existe
                                        'bg-yellow-100 text-yellow-800': !rdv.has_consultation && rdv.statut !== 'annulé',
                                        'bg-red-100 text-red-800': rdv.statut === 'annulé'
                                    }" class="px-2 py-1 rounded-full text-xs font-medium capitalize">
                                        {{ rdv.has_consultation ? 'Consulté' : (rdv.statut ?? 'Inconnu') }}
                                    </span>
                                </td>
                                <!-- <td class="py-4">
                                    <Link :href="route('patients.show', rdv.patient_id)" class="text-blue-600 hover:text-blue-800 mr-3">
                                        <i class="fas fa-eye"></i> Voir Dossier
                                    </Link>

                                    <button v-if="!rdv.has_consultation" @click="startConsultation(rdv.id)" class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-play"></i> Commencer Consultation
                                    </button>
                                </td> -->
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="text-center py-8 text-gray-500">
                    <i class="fas fa-calendar-times text-4xl mb-3 text-gray-300"></i>
                    <p>Aucun rendez-vous programmé pour aujourd'hui.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.animous {
    opacity: 0;
    animation: appear 1s ease-in-out forwards;
}

.animous2 {
    opacity: 0;
    animation: appear 1s ease-in-out 0.5s forwards;
}

.animous3 {
    opacity: 0;
    animation: appear 1s ease-in-out 1s forwards;
}

@keyframes appear {
    0% {
        opacity: 0;
        transform: translateY(-30px);
    }

    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Style pour les boutons d'action */
button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>
