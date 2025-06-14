<template>
<Head title="Historique des Consultations" />

<div class="px-6 py-6">
    <div class="min-h-[calc(100vh-8rem)] bg-gradient-to-br from-indigo-50 to-white rounded-xl shadow-md border border-gray-200 p-10">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-indigo-800">Historique des Consultations</h1>
                <p class="text-gray-600">Consultations passées et en cours</p>
            </div>
        </div>

        <!-- Notification Toast -->
        <TransitionRoot appear :show="showNotification" as="template">
            <div class="fixed inset-0 flex items-end justify-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end z-50">
                <TransitionChild enter="transform ease-out duration-300 transition" enter-from="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" enter-to="translate-y-0 opacity-100 sm:translate-x-0" leave="transition ease-in duration-100" leave-from="opacity-100" leave-to="opacity-0" class="w-full max-w-sm">
                    <div class="w-full h-20 border-l-4 bg-white rounded-xl shadow-lg pointer-events-auto" :class="{
        'border-green-600': notificationType === 'success',
        'border-red-600': notificationType === 'error'
      }">
                        <div class="w-full h-full p-3 grid grid-cols-5 items-center">
                            <div class="flex justify-center items-center col-span-1">
                                <i v-if="notificationType === 'success'" class="fas text-3xl text-green-600 fa-solid fa-circle-check"></i>
                                <i v-if="notificationType === 'error'" class="fas text-3xl text-red-600 fa-solid fa-circle-exclamation"></i>
                            </div>
                            <p class="text-sm text-slate-900 w-full col-span-4">
                                {{ notificationMessage }}
                            </p>
                        </div>
                    </div>
                </TransitionChild>
            </div>
        </TransitionRoot>

        <!-- Consultation en cours (highlighted) -->
        <div v-if="consultationsEnCours.length > 0" class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <span class="w-3 h-3 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                Consultation en cours
            </h2>

            <div class="space-y-4">
                <div v-for="consultation in consultationsEnCours" :key="consultation.id" class="bg-white p-6 rounded-xl shadow-md border-l-4 border-green-500 hover:shadow-lg transition-shadow duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ consultation.motif }}</h3>
                            <div class="mt-2 flex items-center text-sm text-gray-600">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                {{ formatDateTime(consultation.date_consultation) }}
                            </div>
                            <div class="mt-1 flex items-center text-sm text-gray-600">
                                <i class="fas fa-solid fa-bed mr-2"></i>
                                {{ consultation.patient.prenom }} {{ consultation.patient.nom }}
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            En cours
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div v-if="consultation.poids" class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-xs text-gray-500">Poids</p>
                            <p class="font-medium">{{ consultation.poids }} kg</p>
                        </div>
                        <div v-if="consultation.taille" class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-xs text-gray-500">Taille</p>
                            <p class="font-medium">{{ consultation.taille }} cm</p>
                        </div>
                        <div v-if="consultation.temperature" class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-xs text-gray-500">Température</p>
                            <p class="font-medium">{{ consultation.temperature }} °C</p>
                        </div>
                        <div v-if="consultation.tension_arterielle" class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-xs text-gray-500">Tension</p>
                            <p class="font-medium">{{ consultation.tension_arterielle }}</p>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end space-x-3">
                        <button @click="openConsultationDetails(consultation)" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            <i class="fas fa-eye mr-1"></i> Détails
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Consultations terminées -->
        <div>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Historique des consultations</h2>

            <div v-if="consultationsTerminees.length > 0" class="space-y-4">
                <div v-for="consultation in consultationsTerminees" :key="consultation.id" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ consultation.motif }}</h3>
                            <div class="mt-2 flex items-center text-sm text-gray-600">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                {{ formatDateTime(consultation.date_consultation) }}
                            </div>
                            <div class="mt-1 flex items-center text-sm text-gray-600">
                                <i class="fas fa-solid fa-bed mr-2"></i>
                                 {{ consultation.patient.prenom }} {{ consultation.patient.nom }}
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Terminée
                        </span>
                    </div>

                    <!-- <div v-if="consultation.diagnostic" class="mt-3">
                        <pre class="text-sm text-gray-700 line-clamp-2">{{ consultation.diagnostic }}</pre>
                    </div> -->

                    <div class="mt-4 flex justify-end space-x-3">
                        <button @click="openConsultationDetails(consultation)" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            <i class="fas fa-eye mr-1"></i> Détails
                        </button>
                    </div>
                </div>
            </div>

            <div v-else class="text-center py-12 bg-white rounded-xl shadow-sm border border-gray-100">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune consultation terminée</h3>
                <p class="mt-1 text-sm text-gray-500">Aucune consultation n'a été marquée comme terminée pour le moment.</p>
            </div>
        </div>
    </div>

    <!-- Modal de détails de consultation -->
    <TransitionRoot appear :show="showConsultationDetailsModal" as="template">
        <Dialog as="div" class="relative z-50" @close="showConsultationDetailsModal = false">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black/25 backdrop-blur-sm" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                        <DialogPanel class="w-full max-w-3xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 flex items-center">
                                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100 mr-3">
                                    <i class="fas fa-file-medical text-indigo-600"></i>
                                </div>
                                Détails de la consultation
                            </DialogTitle>

                            <div class="mt-6">
                                <div class="border-b border-gray-200">
                                    <nav class="-mb-px flex space-x-8">
                                        <button @click="activeDetailsTab = 'info'" :class="activeDetailsTab === 'info' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                            Informations
                                        </button>
                                        <button @click="activeDetailsTab = 'diagnostic'" :class="activeDetailsTab === 'diagnostic' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                            Diagnostic
                                        </button>
                                        <!-- <button
                                                @click="activeDetailsTab = 'ordonnance'"
                                                :class="activeDetailsTab === 'ordonnance' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                                            >
                                                Ordonnance
                                            </button> -->
                                    </nav>
                                </div>

                                <!-- Informations de base -->
                                <div v-if="activeDetailsTab === 'info'" class="mt-4 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                    <div class="sm:col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Motif</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ selectedConsultation?.motif }}</p>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Statut</label>
                                        <p class="mt-1">
                                            <span class="px-2 py-1 text-xs rounded-full font-medium" :class="selectedConsultation?.status === 'en cours' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'">
                                                {{ selectedConsultation?.status === 'en cours' ? 'En cours' : 'Terminé' }}
                                            </span>
                                        </p>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Date et heure</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ formatDateTime(selectedConsultation?.date_consultation) }}</p>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Patient</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ selectedConsultation?.patient.prenom }} {{ selectedConsultation?.patient.nom }}</p>
                                    </div>

                                    <div v-if="selectedConsultation?.poids" class="sm:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Poids</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ selectedConsultation.poids }} kg</p>
                                    </div>

                                    <div v-if="selectedConsultation?.taille" class="sm:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Taille</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ selectedConsultation.taille }} cm</p>
                                    </div>

                                    <div v-if="selectedConsultation?.temperature" class="sm:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Température</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ selectedConsultation.temperature }} °C</p>
                                    </div>

                                    <div v-if="selectedConsultation?.tension_arterielle" class="sm:col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Tension artérielle</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ selectedConsultation.tension_arterielle }}</p>
                                    </div>

                                </div>

                                <!-- Diagnostic -->
                                <div v-if="activeDetailsTab === 'diagnostic'" class="mt-4">
                                    <div v-if="selectedConsultation?.status === 'en cours'" class="mt-6">
                                        <button @click="openCompleteConsultationModal(selectedConsultation)" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                                            <i class="fas fa-check-circle mr-2"></i> Terminer la consultation
                                        </button>
                                    </div>
                                    <div v-if="selectedConsultation?.motif" class="bg-white p-4 rounded-lg border border-gray-200 my-3">
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Motif</h4>
                                        <p class="text-sm text-gray-900 whitespace-pre-line">{{ selectedConsultation.motif }}</p>
                                    </div>

                                    <div v-if="selectedConsultation?.diagnostic" class="bg-white p-4 rounded-lg border border-gray-200">
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Diagnostic</h4>
                                        <p class="text-sm text-gray-900 whitespace-pre-line">{{ selectedConsultation.diagnostic }}</p>
                                    </div>
                                    <div v-else class="text-center py-8 text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun diagnostic enregistré</h3>
                                        <p class="mt-1 text-sm text-gray-500">Le médecin n'a pas encore saisi de diagnostic pour cette consultation.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500" @click="showConsultationDetailsModal = false">
                                    Fermer
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>

    <!-- Modal pour terminer la consultation -->
    <TransitionRoot appear :show="showCompleteConsultationModal" as="template">
        <Dialog as="div" class="relative z-50" @close="showCompleteConsultationModal = false">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black/25 backdrop-blur-sm" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                        <DialogPanel class="w-full max-w-5xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 flex items-center">
                                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100 mr-3">
                                    <i class="fas fa-check-circle text-indigo-600"></i>
                                </div>
                                Terminer la consultation
                            </DialogTitle>

                            <div class="mt-6 space-y-6">
                                <!-- Diagnostic -->
                                <div>
                                    <label for="diagnostic" class="block text-sm font-medium text-gray-700">Diagnostic *</label>
                                    <textarea v-model="consultationToComplete.diagnostic" id="diagnostic" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border" required></textarea>
                                </div>

                                <!-- Traitement prescrit -->
                                <div>
                                    <label for="traitement_prescrit" class="block text-sm font-medium text-gray-700">Traitement prescrit</label>
                                    <textarea v-model="consultationToComplete.traitement_prescrit" id="traitement_prescrit" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border"></textarea>
                                </div>

                                <!-- Observations -->
                                <div>
                                    <label for="observations" class="block text-sm font-medium text-gray-700">Observations</label>
                                    <textarea v-model="consultationToComplete.observations" id="observations" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border"></textarea>
                                </div>

                                <div v-if="laborantins.length > 0">
                                    <label for="laborantin" class="block text-sm font-medium text-gray-700">Laborantin assigné</label>
                                    <select v-model="consultationToComplete.laborantin_id" id="laborantin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                                        <option value="">Sélectionnez un laborantin</option>
                                        <option v-for="laborantin in laborantins" :key="laborantin.id" :value="laborantin.id">
                                            {{ laborantin.nom }} {{ laborantin.prenom }}
                                        </option>
                                    </select>
                                </div>

                                <!-- Analyses médicales -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Analyses médicales</label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div v-for="(analyse, key) in allAnalyses" :key="key" class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input :id="'analyse_' + key" type="checkbox" v-model="consultationToComplete.analyses" :value="key" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label :for="'analyse_' + key" class="font-medium text-gray-700">{{ analyse.name }}</label>
                                                <p class="text-gray-500">{{ formatPrice(analyse.price) }} FCFA</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Récapitulatif facture -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="text-sm font-medium text-gray-700 mb-3">Récapitulatif de la facture</h4>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Consultation médicale</span>
                                            <span class="text-sm font-medium">5 000 FCFA</span>
                                        </div>
                                        <div v-for="analyse in selectedAnalysesWithPrices" :key="analyse.name" class="flex justify-between">
                                            <span class="text-sm text-gray-600">{{ analyse.name }}</span>
                                            <span class="text-sm font-medium">{{ formatPrice(analyse.price) }} FCFA</span>
                                        </div>
                                        <div class="border-t border-gray-200 pt-2 mt-2 flex justify-between">
                                            <span class="text-sm font-medium">Total</span>
                                            <span class="text-sm font-medium text-indigo-600">{{ formatPrice(totalAmount) }} FCFA</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500" @click="showCompleteConsultationModal = false">
                                    Annuler
                                </button>
                                <button type="button" :disabled="isCompletingConsultation" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed" @click="completeConsultation">
                                    <span v-if="isCompletingConsultation">
                                        <i class="fas fa-spinner fa-spin mr-2"></i> Enregistrement...
                                    </span>
                                    <span v-else>
                                        <i class="fas fa-check-circle mr-2"></i> Terminer et facturer
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
import { ref, computed } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { AnalyseMedicale } from '@/Enums/AnalyseMedicale'

// Props
const props = defineProps({
    consultations: {
        type: Array,
        required: true
    },
    laborantins: {
        type: Array,
        required: true
    }
})

// État pour le modal des détails de consultation
const showConsultationDetailsModal = ref(false)
const selectedConsultation = ref(null)
const activeDetailsTab = ref('info')

// État pour le modal de fin de consultation
const showCompleteConsultationModal = ref(false)
const isCompletingConsultation = ref(false)
const consultationToComplete = ref({
    id: null,
    diagnostic: '',
    traitement_prescrit: '',
    observations: '',
    analyses: [],
    laborantin_id: null,
    statut: 'terminé'
})

// Récupérer toutes les analyses médicales disponibles
const allAnalyses = ref(AnalyseMedicale.getAnalysesWithPrices())

// Propriétés calculées
const consultationsEnCours = computed(() => {
    return props.consultations.filter(c => c.status === 'en cours')
})

const consultationsTerminees = computed(() => {
    return props.consultations.filter(c => c.status === 'terminé').sort((a, b) => {
        return new Date(b.date_consultation) - new Date(a.date_consultation)
    })
})

const selectedAnalysesWithPrices = computed(() => {
    return consultationToComplete.value.analyses
        .map(analyseKey => allAnalyses.value[analyseKey])
        .filter(Boolean)
})

const totalAmount = computed(() => {
    const consultationPrice = 5000
    const analysesTotal = selectedAnalysesWithPrices.value.reduce((sum, analyse) => sum + analyse.price, 0)
    return consultationPrice + analysesTotal
})

// Méthodes
function formatDateTime(dateString) {
    if (!dateString) return ''
    const date = new Date(dateString)
    return date.toLocaleString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

function formatPrice(price) {
    return new Intl.NumberFormat('fr-FR').format(price)
}

function openConsultationDetails(consultation) {
    selectedConsultation.value = consultation
    activeDetailsTab.value = 'info'
    showConsultationDetailsModal.value = true
}

function openCompleteConsultationModal(consultation) {
    showConsultationDetailsModal.value = false;

    setTimeout(() => {
        consultationToComplete.value = {
            id: consultation.id,
            diagnostic: consultation.diagnostic || '',
            traitement_prescrit: consultation.traitement_prescrit || '',
            observations: consultation.observations || '',
            analyses: consultation.analyses || [],
            laborantin_id: consultation.laborantin_id || null
        };
        showCompleteConsultationModal.value = true;
    }, 300);
}

async function completeConsultation() {
    isCompletingConsultation.value = true;

    const payload = {
        ...consultationToComplete.value,
        montant: totalAmount.value
    }

    router.post(route('consultations.complete'), payload, {
        preserveScroll: true,
        onSuccess: () => {
            showToast('Consultation terminée et facturée avec succès', 'success');
            showCompleteConsultationModal.value = false;
            router.reload({ preserveScroll: true });
        },
        onError: (errors) => {
            showToast('Une erreur est survenue lors de la finalisation de la consultation.', 'error');
            console.error("Erreur lors de la complétion de la consultation:", errors);
        },
        onFinish: () => {
            isCompletingConsultation.value = false;
        }
    });
}

const showNotification = ref(false)
const notificationMessage = ref('')
const notificationType = ref('success')

function showToast(message, type = 'success') {
    notificationMessage.value = message
    notificationType.value = type
    showNotification.value = true
    setTimeout(() => {
        showNotification.value = false
    }, 4000)
}
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

