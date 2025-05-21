<template>
    <Head title="Tableau de Bord Médecin" />

    <div class="px-6 py-6">
        <div
            class="min-h-[calc(100vh-8rem)] bg-gradient-to-br from-blue-50 to-white rounded-xl shadow-md border border-gray-200 p-10">
            <!-- En-tête -->
            <div class="mb-10 text-center">
                <h1 class="text-4xl font-bold text-blue-800 mb-2">Tableau de Bord Médical</h1>
                <p class="text-xl text-gray-600">Bienvenue Dr. [Nom], voici votre activité du jour</p>
            </div>

            <!-- Statistiques rapides -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10 animous">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                    <div class="bg-blue-100 p-4 rounded-full mr-4">
                        <i class="fas fa-user-injured text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Patients aujourd'hui</p>
                        <p class="text-2xl font-bold">18</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                    <div class="bg-green-100 p-4 rounded-full mr-4">
                        <i class="fas fa-calendar-check text-2xl text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">RDV programmés</p>
                        <p class="text-2xl font-bold">12</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                    <div class="bg-purple-100 p-4 rounded-full mr-4">
                        <i class="fas fa-file-medical text-2xl text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Consultations</p>
                        <p class="text-2xl font-bold">6</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                    <div class="bg-yellow-100 p-4 rounded-full mr-4">
                        <i class="fas fa-clock text-2xl text-yellow-600"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">En attente</p>
                        <p class="text-2xl font-bold">3</p>
                    </div>
                </div>
            </div>

            <!-- Section consultation en cours -->
            <div class="mb-10 bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden animous2">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Consultation en cours</h3>
                    <div v-if="currentPatient" class="flex items-center justify-between bg-blue-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-3 rounded-full mr-4">
                                <i class="fas fa-user-md text-xl text-blue-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg">{{ currentPatient.name }}</h4>
                                <p class="text-gray-600">{{ currentPatient.age }} ans • {{ currentPatient.gender }} • {{ currentPatient.id }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-file-medical mr-2"></i>Dossier
                            </button>
                            <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-notes-medical mr-2"></i>Rédiger ordonnance
                            </button>
                            <button class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                <i class="fas fa-times mr-2"></i>Terminer
                            </button>
                        </div>
                    </div>
                    <div v-else class="text-center py-8 text-gray-500">
                        <i class="fas fa-user-md text-4xl mb-3 text-gray-300"></i>
                        <p>Aucune consultation en cours</p>
                        <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-play mr-2"></i>Commencer une consultation
                        </button>
                    </div>
                </div>
            </div>

            <!-- Actions principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animous3">
                <!-- Dossiers patients -->
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-folder-open text-xl text-blue-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Dossiers patients</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Accéder à l'historique médical complet des patients.</p>
                    <button
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                        Rechercher <i class="fas fa-search ml-2"></i>
                    </button>
                </div>

                <!-- Nouvelle consultation -->
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 p-3 rounded-full mr-4">
                            <i class="fas fa-stethoscope text-xl text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Nouvelle consultation</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Commencer une nouvelle consultation médicale.</p>
                    <button
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                        Démarrer <i class="fas fa-play ml-2"></i>
                    </button>
                </div>

                <!-- Planifier RDV -->
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 p-3 rounded-full mr-4">
                            <i class="fas fa-calendar-plus text-xl text-purple-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Planifier RDV</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Programmer un rendez-vous pour un patient.</p>
                    <button
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm">
                        Nouveau RDV <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>

                <!-- Ordonnances -->
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-yellow-100 p-3 rounded-full mr-4">
                            <i class="fas fa-prescription text-xl text-yellow-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Ordonnances</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Gérer les ordonnances et prescriptions.</p>
                    <button
                        class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm">
                        Voir historique <i class="fas fa-history ml-2"></i>
                    </button>
                </div>

                <!-- Examens médicaux -->
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-red-100 p-3 rounded-full mr-4">
                            <i class="fas fa-vial text-xl text-red-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Examens médicaux</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Demander des examens ou consulter les résultats.</p>
                    <button
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                        Gérer examens <i class="fas fa-flask ml-2"></i>
                    </button>
                </div>

                <!-- Agenda -->
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-indigo-100 p-3 rounded-full mr-4">
                            <i class="fas fa-calendar-alt text-xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Mon agenda</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Consulter et gérer votre emploi du temps.</p>
                    <button
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm">
                        Voir agenda <i class="fas fa-calendar-day ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- Section patients en attente -->
            <div class="mt-10 bg-white rounded-xl shadow-md border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Patients en attente</h3>
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">5 patients</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-gray-500 border-b">
                                <th class="pb-3">Nom</th>
                                <th class="pb-3">ID</th>
                                <th class="pb-3">Motif</th>
                                <th class="pb-3">Arrivée</th>
                                <th class="pb-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="patient in waitingPatients" :key="patient.id" class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-4">{{ patient.name }}</td>
                                <td class="py-4">{{ patient.id }}</td>
                                <td class="py-4">{{ patient.reason }}</td>
                                <td class="py-4">{{ patient.arrivalTime }}</td>
                                <td class="py-4">
                                    <button class="text-blue-600 hover:text-blue-800 mr-3">
                                        <i class="fas fa-eye"></i> Voir
                                    </button>
                                    <button class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-play"></i> Commencer
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

// Données simulées pour l'exemple
const currentPatient = ref(null)

const waitingPatients = ref([
    { id: 'P12345', name: 'Jean Dupont', reason: 'Contrôle annuel', arrivalTime: '09:15' },
    { id: 'P12346', name: 'Marie Lambert', reason: 'Douleurs abdominales', arrivalTime: '09:30' },
    { id: 'P12347', name: 'Paul Martin', reason: 'Prescription renouvellement', arrivalTime: '09:45' },
    { id: 'P12348', name: 'Sophie Bernard', reason: 'Suivi traitement', arrivalTime: '10:00' },
    { id: 'P12349', name: 'Lucie Petit', reason: 'Première consultation', arrivalTime: '10:15' }
])
</script>

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
