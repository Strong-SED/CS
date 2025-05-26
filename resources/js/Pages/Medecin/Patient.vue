<template>
    <Head title="Gestion des Patients" />

    <div class="px-6 py-6">
        <div class="min-h-[calc(100vh-8rem)] bg-gradient-to-br from-indigo-50 to-white rounded-xl shadow-md border border-gray-200 p-10">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-indigo-800">Gestion des Patients</h1>
                    <p class="text-gray-600">Consulter les dossiers des patients</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
                <div class="relative">
                    <input
                        type="text"
                        v-model="filters.search"
                        placeholder="Rechercher par nom, prénom, ID..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500"
                    >
                    <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nom Complet
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date Naiss.
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Genre
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Téléphone
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="patient in patients.data" :key="patient.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ patient.id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <i class="fas fa-user text-indigo-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ patient.prenom }} {{ patient.nom }}</div>
                                            <div class="text-sm text-gray-500">{{ patient.email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatDate(patient.date_naissance) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                          :class="patient.genre === 'M' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800'">
                                        {{ patient.genre === 'M' ? 'Masculin' : 'Féminin' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ patient.telephone }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button
                                        @click="openPatientModal(patient)"
                                        class="text-indigo-600 hover:text-indigo-900"
                                    >
                                        <i class="fas fa-eye mr-1"></i> Voir
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="patients.data.length === 0">
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Aucun patient trouvé
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="bg-white px-6 py-3 flex items-center justify-between border-t border-gray-200">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <button
                            @click="prevPage"
                            :disabled="patients.current_page === 1"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                        >
                            Précédent
                        </button>
                        <button
                            @click="nextPage"
                            :disabled="patients.current_page === patients.last_page"
                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                        >
                            Suivant
                        </button>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Affichage de <span class="font-medium">{{ patients.from }}</span> à <span class="font-medium">{{ patients.to }}</span>
                                sur <span class="font-medium">{{ patients.total }}</span> patients
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                <button
                                    @click="changePage(1)"
                                    :disabled="patients.current_page === 1"
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                >
                                    <span class="sr-only">Premier</span>
                                    <i class="fas fa-angle-double-left"></i>
                                </button>
                                <button
                                    @click="prevPage"
                                    :disabled="patients.current_page === 1"
                                    class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                >
                                    <span class="sr-only">Précédent</span>
                                    <i class="fas fa-chevron-left"></i>
                                </button>

                                <template v-for="page in pagesRange" :key="page">
                                    <button
                                        @click="changePage(page)"
                                        :class="{ 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600': page === patients.current_page }"
                                        class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                    >
                                        {{ page }}
                                    </button>
                                </template>

                                <button
                                    @click="nextPage"
                                    :disabled="patients.current_page === patients.last_page"
                                    class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                >
                                    <span class="sr-only">Suivant</span>
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                                <button
                                    @click="changePage(patients.last_page)"
                                    :disabled="patients.current_page === patients.last_page"
                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                >
                                    <span class="sr-only">Dernier</span>
                                    <i class="fas fa-angle-double-right"></i>
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <TransitionRoot appear :show="showPatientModal" as="template">
            <Dialog as="div" class="relative z-50" @close="showPatientModal = false">
                <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                    <div class="fixed inset-0 bg-black/25 backdrop-blur-sm" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4 text-center">
                        <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                            <DialogPanel class="w-full max-w-4xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 flex items-center">
                                    <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100 mr-3">
                                        <i class="fas fa-user text-indigo-600"></i>
                                    </div>
                                    Dossier Patient: {{ selectedPatient?.prenom }} {{ selectedPatient?.nom }}
                                </DialogTitle>

                                <div class="mt-6">
                                    <div class="border-b border-gray-200">
                                        <nav class="-mb-px flex space-x-8">
                                            <button
                                                @click="activeTab = 'info'"
                                                :class="activeTab === 'info' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                                            >
                                                Informations
                                            </button>
                                            <button
                                                @click="activeTab = 'dossier'"
                                                :class="activeTab === 'dossier' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                                            >
                                                Dossier Médical
                                            </button>
                                        </nav>
                                    </div>

                                    <div v-if="activeTab === 'info'" class="mt-4 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                        <div class="sm:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700">Nom</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ selectedPatient?.nom }}</p>
                                        </div>
                                        <div class="sm:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700">Prénom</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ selectedPatient?.prenom }}</p>
                                        </div>
                                        <div class="sm:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700">Date de Naissance</label>
                                            <p class="mt-1 text-sm text-gray-900">
                                                {{ formatDate(selectedPatient?.date_naissance) }} ({{ calculateAge(selectedPatient?.date_naissance) }} ans)
                                            </p>
                                        </div>
                                        <div class="sm:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700">Genre</label>
                                            <p class="mt-1 text-sm text-gray-900">
                                                {{ selectedPatient?.genre === 'M' ? 'Masculin' : 'Féminin' }}
                                            </p>
                                        </div>
                                        <div class="sm:col-span-4">
                                            <label class="block text-sm font-medium text-gray-700">Email</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ selectedPatient?.email }}</p>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ selectedPatient?.telephone }}</p>
                                        </div>
                                        <div class="sm:col-span-6">
                                            <label class="block text-sm font-medium text-gray-700">Adresse</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ selectedPatient?.adresse }}</p>
                                        </div>
                                        <div class="sm:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700">NPI</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ selectedPatient?.npi || 'Non renseigné' }}</p>
                                        </div>
                                    </div>

                                    <div v-if="activeTab === 'dossier'" class="mt-4">
                                        <div v-if="selectedPatient?.dossier_medical" class="bg-white shadow overflow-hidden sm:rounded-lg">
                                            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                    Dossier Médical
                                                </h3>
                                            </div>

                                            <div class="px-4 py-5 sm:p-6">
                                                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                                    <div class="sm:col-span-3">
                                                        <label class="block text-sm font-medium text-gray-700">Groupe Sanguin</label>
                                                        <p class="mt-1 text-sm text-gray-900">
                                                            {{ selectedPatient.dossier_medical.groupe_sanguin || 'Non renseigné' }}
                                                        </p>
                                                    </div>
                                                    <div class="sm:col-span-6">
                                                        <label class="block text-sm font-medium text-gray-700">Allergies</label>
                                                        <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">
                                                            {{ selectedPatient.dossier_medical.allergies || 'Aucune allergie connue' }}
                                                        </p>
                                                    </div>
                                                    <div class="sm:col-span-6">
                                                        <label class="block text-sm font-medium text-gray-700">Antécédents Médicaux</label>
                                                        <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">
                                                            {{ selectedPatient.dossier_medical.antecedents_medicaux || 'Aucun antécédent médical enregistré' }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="mt-6">
                                                    <h4 class="text-md font-medium text-gray-900 mb-3">Consultations récentes</h4>
                                                    <div v-if="selectedPatient.dossier_medical.consultations && selectedPatient.dossier_medical.consultations.length > 0" class="space-y-3">
                                                        <div v-for="consultationItem in selectedPatient.dossier_medical.consultations.slice(0, 3)" :key="consultationItem.id" class="p-3 border border-gray-200 rounded-lg">
                                                            <div class="flex justify-between items-start">
                                                                <div>
                                                                    <p class="font-medium">{{ consultationItem.motif }}</p>
                                                                    <p class="text-sm text-gray-600">{{ formatDate(consultationItem.date_consultation) }}</p>
                                                                </div>
                                                                <span class="px-2 py-1 text-xs rounded-full"
                                                                      :class="consultationItem.status === 'en cours' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'">
                                                                    {{ consultationItem.status }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p v-else class="text-sm text-gray-500">Aucune consultation enregistrée</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-else class="text-center py-8 text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun dossier médical</h3>
                                            <p class="mt-1 text-sm text-gray-500">Ce patient n'a pas encore de dossier médical.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <button
                                        type="button"
                                        class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        @click="showPatientModal = false"
                                    >
                                        Fermer
                                    </button>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <TransitionRoot appear :show="showConsultationModal" as="template">
            <Dialog as="div" class="relative z-50" @close="showConsultationModal = false">
                <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                    <div class="fixed inset-0 bg-black/25 backdrop-blur-sm" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4 text-center">
                        <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                            <DialogPanel class="w-full max-w-3xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 flex items-center">
                                    <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100 mr-3">
                                        <i class="fas fa-stethoscope text-indigo-600"></i>
                                    </div>
                                    Nouvelle Consultation pour {{ selectedPatient?.prenom }} {{ selectedPatient?.nom }}
                                </DialogTitle>

                                <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                    <div class="sm:col-span-6">
                                        <label for="motif" class="block text-sm font-medium text-gray-700">Motif de la consultation *</label>
                                        <textarea v-model="consultation.motif" id="motif" rows="2" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3" required></textarea>
                                        <p v-if="consultationErrors.motif" class="mt-1 text-sm text-red-600">{{ consultationErrors.motif }}</p>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="date_consultation" class="block text-sm font-medium text-gray-700">Date et heure *</label>
                                        <input type="datetime-local" v-model="consultation.date_consultation" id="date_consultation" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3" required>
                                        <p v-if="consultationErrors.date_consultation" class="mt-1 text-sm text-red-600">{{ consultationErrors.date_consultation }}</p>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="medecin_id" class="block text-sm font-medium text-gray-700">Médecin *</label>
                                        <select v-model="consultation.medecin_id" id="medecin_id" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3" required>
                                            <option value="">Sélectionner un médecin</option>
                                            <option v-for="medecin in medecinsLibres" :key="medecin.id" :value="medecin.id">
                                                {{ medecin.nom }} {{ medecin.prenom }}
                                            </option>
                                        </select>
                                        <p v-if="consultationErrors.medecin_id" class="mt-1 text-sm text-red-600">{{ consultationErrors.medecin_id }}</p>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="poids" class="block text-sm font-medium text-gray-700">Poids (kg)</label>
                                        <input type="text" v-model="consultation.poids" id="poids" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3">
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="taille" class="block text-sm font-medium text-gray-700">Taille (cm)</label>
                                        <input type="text" v-model="consultation.taille" id="taille" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3">
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="temperature" class="block text-sm font-medium text-gray-700">Température (°C)</label>
                                        <input type="text" v-model="consultation.temperature" id="temperature" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3">
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="tension_arterielle" class="block text-sm font-medium text-gray-700">Tension artérielle</label>
                                        <input type="text" v-model="consultation.tension_arterielle" id="tension_arterielle" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3" placeholder="Ex: 120/80">
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end space-x-3">
                                    <button type="button" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500" @click="showConsultationModal = false">
                                        Annuler
                                    </button>
                                    <button type="button" :disabled="isCreatingConsultation" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed" @click="createConsultation">
                                        <span v-if="isCreatingConsultation">
                                            <i class="fas fa-spinner fa-spin mr-2"></i> Enregistrement...
                                        </span>
                                        <span v-else>
                                            <i class="fas fa-save mr-2"></i> Enregistrer
                                        </span>
                                    </button>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
    </div>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot
} from '@headlessui/vue'
import { debounce } from 'lodash'; // Importer debounce de lodash

// Props
const props = defineProps({
    patients: Object,
    filters: Object,
    medecinsLibres: Array // CHANGEMENT ICI: doit être Array
})

// State
const showPatientModal = ref(false)
const selectedPatient = ref(null)
const activeTab = ref('info')
const filters = ref({
    search: props.filters.search || ''
})

// Consultation
const showConsultationModal = ref(false)
const isCreatingConsultation = ref(false)
const consultation = ref({
    dossier_medical_id: null,
    medecin_id: null,
    date_consultation: '',
    motif: '',
    poids: '',
    taille: '',
    temperature: '',
    tension_arterielle: '',
    status: 'en cours'
})
const consultationErrors = ref({})
// medecinsLibres est déjà disponible via les props, pas besoin de le ref() ici
// const medecinsLibres = ref(props.medecinsLibres || []); // Cette ligne devient redondante si vous utilisez props.medecinsLibres directement.

// Computed
const pagesRange = computed(() => {
    const range = []
    const current = props.patients.current_page
    const last = props.patients.last_page
    const delta = 2

    for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) {
        range.push(i)
    }

    if (current - delta > 2) {
        range.unshift('...')
    }
    if (current + delta < last - 1) {
        range.push('...')
    }

    range.unshift(1)
    if (last !== 1) range.push(last)

    return range
})

// Methods
function formatDate(dateString) {
    if (!dateString) return ''
    const date = new Date(dateString)
    return date.toLocaleDateString('fr-FR')
}

function calculateAge(birthdate) {
    if (!birthdate) return ''
    const today = new Date()
    const birthDate = new Date(birthdate)
    let age = today.getFullYear() - birthDate.getFullYear()
    const monthDiff = today.getMonth() - birthDate.getMonth()

    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--
    }

    return age
}

function openPatientModal(patient) {
    selectedPatient.value = patient
    activeTab.value = 'info'
    showPatientModal.value = true
}

// Fonction pour ouvrir le modal de consultation
function initConsultation() {
    if (!selectedPatient.value || !selectedPatient.value.dossier_medical) return; // Assurez-vous que dossier_medical existe

    consultation.value = {
        dossier_medical_id: selectedPatient.value.dossier_medical.id,
        medecin_id: null, // Laissez null pour la sélection
        date_consultation: new Date().toISOString().slice(0, 16), // Date et heure actuelles
        motif: '',
        poids: '',
        taille: '',
        temperature: '',
        tension_arterielle: '',
        status: 'en cours'
    };

    showConsultationModal.value = true;
}

function createConsultation() {
    isCreatingConsultation.value = true
    consultationErrors.value = {}

    router.post(route('consultations.store'), consultation.value, {
        preserveScroll: true,
        onSuccess: () => {
            showConsultationModal.value = false
            // Vous pouvez recharger les données du patient pour voir la nouvelle consultation
            // ou simplement afficher un toast.
            // Si vous voulez recharger, il faudrait refaire une requête Inertia.get
            // ou s'assurer que les données du patient sont mises à jour après le succès.
            // Pour l'instant, un toast suffit comme feedback.
            showToast('Consultation créée avec succès', 'success');

            // Réinitialiser le formulaire après succès si vous ne fermez pas le modal
            // ou si vous voulez qu'il soit propre à la prochaine ouverture.
            // consultation.value = {}; // ou réinitialiser champ par champ
        },
        onError: (errors) => {
            consultationErrors.value = errors
        },
        onFinish: () => {
            isCreatingConsultation.value = false
        }
    })
}


function changePage(page) {
    if (page === '...') return
    fetchPatients(page)
}

function prevPage() {
    if (props.patients.current_page > 1) {
        fetchPatients(props.patients.current_page - 1)
    }
}

function nextPage() {
    if (props.patients.current_page < props.patients.last_page) {
        fetchPatients(props.patients.current_page + 1)
    }
}

// Définition de la fonction de recherche déBouncée
const debouncedFetchPatients = debounce(() => {
    fetchPatients(1); // Retourne à la première page lors d'une nouvelle recherche
}, 300); // 300ms de délai

function fetchPatients(page = 1) {
    router.get(route('medecin.patients'), {
        search: filters.value.search,
        page: page
    }, {
        preserveState: true,
        replace: true
    })
}

// Watch
// CHANGEMENT ICI: Surveiller spécifiquement `filters.value.search`
watch(() => filters.value.search, () => {
    debouncedFetchPatients();
});

// Fonction utilitaire pour les toasts (si non définie ailleurs)
function showToast(message, type) {
    // Implémentation simple pour un toast, à adapter à votre système de notification
    console.log(`Toast (${type}): ${message}`);
    // Ex: alert(message);
    // Ou utilisez une bibliothèque de notification comme vue-toastification, sweetalert2, etc.
}
</script>
