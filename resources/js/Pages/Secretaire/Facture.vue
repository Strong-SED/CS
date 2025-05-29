<template>
<Head title="Gestion des Factures" />

<div class="px-6 py-6">
    <div class="min-h-[calc(100vh-8rem)] bg-gradient-to-br from-indigo-50 to-white rounded-xl shadow-md border border-gray-200 p-10">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-indigo-800">Historique des Factures</h1>
                <p class="text-gray-600">Factures impayées et payées</p>
            </div>
        </div>

                <!-- Notification Toast (inchangé) -->
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

        <!-- Onglets pour les statuts -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8">
                <button @click="activeTab = 'impaye'" :class="activeTab === 'impaye' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Impayées
                    <span class="ml-2 bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                        {{ facturesImpayees.length }}
                    </span>
                </button>
                <button @click="activeTab = 'paye'" :class="activeTab === 'paye' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Payées
                    <span class="ml-2 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                        {{ facturesPayees.length }}
                    </span>
                </button>
            </nav>
        </div>

        <!-- Liste des factures -->
        <div v-if="facturesFiltrees.length > 0" class="space-y-4">
            <div v-for="facture in facturesFiltrees" :key="facture.id" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200 cursor-pointer" @click="openFactureDetails(facture)">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">
                            Facture #{{ facture.numero_facture }}
                        </h3>
                        <div class="mt-2 flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            {{ formatDate(facture.date_emission) }}
                        </div>
                        <div class="mt-1 flex items-center text-sm text-gray-600">
                            <i class="fas fa-user mr-2"></i>
                            {{ facture.patient.prenom }} {{ facture.patient.nom }}
                        </div>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="px-3 py-1 rounded-full text-xs font-medium mb-2" :class="facture.statut === 'paye' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                            {{ facture.statut === 'paye' ? 'Payée' : 'Impayée' }}
                        </span>
                        <span class="text-lg font-bold text-indigo-800">
                            {{ formatPrice(facture.montant) }} FCFA
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="text-center py-12 bg-white rounded-xl shadow-sm border border-gray-100">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune facture {{ activeTab === 'impaye' ? 'impayée' : 'payée' }}</h3>
            <p class="mt-1 text-sm text-gray-500">Aucune facture n'a été trouvée pour ce statut.</p>
        </div>
    </div>

    <!-- Modal de détails de facture -->
    <TransitionRoot appear :show="showFactureDetailsModal" as="template">
        <Dialog as="div" class="relative z-50" @close="showFactureDetailsModal = false">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black/25 backdrop-blur-sm" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                        <DialogPanel class="w-full max-w-3xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 flex items-center">
                                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100 mr-3">
                                    <i class="fas fa-file-invoice-dollar text-indigo-600"></i>
                                </div>
                                Détails de la facture #{{ selectedFacture?.numero_facture }}
                            </DialogTitle>

                            <div class="mt-6 space-y-6">
                                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Date d'émission</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ formatDate(selectedFacture?.date_emission) }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Statut</label>
                                        <p class="mt-1">
                                            <span class="px-2 py-1 text-xs rounded-full font-medium"
                                                :class="selectedFacture?.statut === 'paye' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                                                {{ selectedFacture?.statut === 'paye' ? 'Payée' : 'Impayée' }}
                                            </span>
                                        </p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Patient</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ selectedFacture?.patient.prenom }} {{ selectedFacture?.patient.nom }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Montant total</label>
                                        <p class="mt-1 text-lg font-bold text-indigo-800">{{ formatPrice(selectedFacture?.montant) }} FCFA</p>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Détails des prestations</label>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <div v-if="selectedFacture?.details">
                                            <div v-if="selectedFacture.details.consultation_id" class="mb-2">
                                                <p class="text-sm font-medium text-gray-900">Consultation ID: {{ selectedFacture.details.consultation_id }}</p>
                                            </div>

                                            <div v-if="selectedFacture.details.diagnostic" class="mb-2">
                                                <p class="text-sm font-medium text-gray-900">Diagnostic:</p>
                                                <p class="text-sm text-gray-600">{{ selectedFacture.details.diagnostic }}</p>
                                            </div>

                                            <div v-if="formattedAnalyses.length > 0">
                                                <p class="text-sm font-medium text-gray-900 mb-2">Analyses:</p>
                                                <div v-for="(analyse, index) in formattedAnalyses" :key="index" class="flex justify-between border-b border-gray-200 pb-2 last:border-b-0 last:pb-0">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">{{ analyse.libelle }}</p>
                                                        </div>
                                                    <p class="text-sm font-medium">{{ formatPrice(analyse.montant) }} FCFA</p>
                                                </div>
                                            </div>

                                            <div v-else-if="!selectedFacture.details.consultation_id && !selectedFacture.details.diagnostic" class="text-center py-4 text-gray-500">
                                                Aucun détail disponible pour cette facture
                                            </div>
                                        </div>
                                        <div v-else class="text-center py-4 text-gray-500">
                                            Aucun détail disponible pour cette facture
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button
                                    v-if="selectedFacture?.statut === 'impaye'"
                                    @click="updateFactureStatus"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition"
                                >
                                    <i class="fas fa-check-circle mr-2"></i> Marquer comme payée
                                </button>
                                <button
                                    type="button"
                                    class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    @click="showFactureDetailsModal = false"
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
</div>
</template>

<script setup>
import {
    Head,
    router,
    usePage // Importer usePage pour accéder aux props d'Inertia
} from '@inertiajs/vue3'
import {
    ref,
    computed,
    watch // Importer watch pour surveiller les props flash
} from 'vue'
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot
} from '@headlessui/vue'
import { AnalyseMedicale } from '@/Enums/AnalyseMedicale.js' // IMPORTANT : Ajuste le chemin si besoin

// Accéder aux props d'Inertia, y compris les messages flash
const page = usePage();

// Props (inchangé)
const props = defineProps({
    factures: {
        type: Array,
        required: true
    }
})

// État (ajouter les variables pour la notification)
const activeTab = ref('impaye')
const showFactureDetailsModal = ref(false)
const selectedFacture = ref(null)

// --- Nouvelles variables d'état pour la notification ---
const showNotification = ref(false);
const notificationMessage = ref('');
const notificationType = ref('success'); // 'success' ou 'error'
let notificationTimeout = null; // Pour stocker le timer de masquage automatique
// --- Fin des nouvelles variables ---

// Propriétés calculées (inchangé)
const facturesImpayees = computed(() => {
    return props.factures.filter(f => f.statut === 'impaye')
        .sort((a, b) => new Date(b.date_emission) - new Date(a.date_emission))
})

const facturesPayees = computed(() => {
    return props.factures.filter(f => f.statut === 'paye')
        .sort((a, b) => new Date(b.date_emission) - new Date(a.date_emission))
})

const facturesFiltrees = computed(() => {
    return activeTab.value === 'impaye' ? facturesImpayees.value : facturesPayees.value
})

// Propriété calculée pour les analyses formatées (inchangé)
const formattedAnalyses = computed(() => {
    if (selectedFacture.value?.details?.analyses && Array.isArray(selectedFacture.value.details.analyses)) {
        return selectedFacture.value.details.analyses.map(analyseCode => {
            if (AnalyseMedicale[analyseCode]) {
                const analyseInfo = AnalyseMedicale[analyseCode];
                return {
                    libelle: analyseInfo.name,
                    montant: analyseInfo.price,
                };
            }
            return { libelle: `Analyse inconnue (${analyseCode})`, montant: 0 };
        });
    }
    return [];
});


// Méthodes (ajouter la fonction pour les notifications et modifier updateFactureStatus)
function formatDate(dateString) {
    if (!dateString) return ''
    const date = new Date(dateString)
    return date.toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    })
}

function formatPrice(price) {
    return new Intl.NumberFormat('fr-FR').format(price)
}

function openFactureDetails(facture) {
    selectedFacture.value = facture
    showFactureDetailsModal.value = true
}

// --- Nouvelle fonction pour afficher la notification ---
function showToast(message, type = 'success') {
    // Efface le timer précédent si une notification est déjà affichée
    if (notificationTimeout) {
        clearTimeout(notificationTimeout);
    }

    notificationMessage.value = message;
    notificationType.value = type;
    showNotification.value = true;

    // Masque la notification après 3 secondes (3000 ms)
    notificationTimeout = setTimeout(() => {
        showNotification.value = false;
        notificationMessage.value = ''; // Réinitialise le message
    }, 3000);
}
// --- Fin de la nouvelle fonction ---


function updateFactureStatus() {
    if (!selectedFacture.value) return

    router.put(route('factures.updateStatus', selectedFacture.value.id), {
        statut: 'paye'
    }, {
        preserveScroll: true,
        onSuccess: () => {
            showFactureDetailsModal.value = false
            router.reload({
                preserveScroll: true,
                onSuccess: (page) => {
                    // Vérifie si un message flash 'success' est présent après le rechargement
                    if (page.props.flash.success) {
                        showToast(page.props.flash.success, 'success');
                    } else if (page.props.flash.info) { // Gérer le cas où la facture est déjà payée
                        showToast(page.props.flash.info, 'info'); // Tu devras ajouter un style 'info' à ton modal si besoin
                    }
                }
            })
        },
        onError: (errors) => {
            // Afficher l'erreur générale ou la première erreur spécifique
            const errorMessage = Object.values(errors).flat()[0] || "Une erreur est survenue lors de la mise à jour.";
            showToast(errorMessage, 'error');
            console.error("Erreur lors de la mise à jour du statut:", errors)
        }
    })
}

// --- Watcher pour les messages flash d'Inertia ---
// Ce watcher est utile si des messages flash peuvent venir d'autres actions
// ou du premier chargement de la page.
watch(() => page.props.flash.success, (message) => {
    if (message) {
        showToast(message, 'success');
        page.props.flash.success = null; // Efface le message après affichage
    }
}, { immediate: true }); // immediate: true exécute le watcher au premier chargement

watch(() => page.props.flash.error, (message) => {
    if (message) {
        showToast(message, 'error');
        page.props.flash.error = null; // Efface le message après affichage
    }
}, { immediate: true });

watch(() => page.props.flash.info, (message) => {
    if (message) {
        showToast(message, 'info'); // Assure-toi que ton modal gère le type 'info' si tu l'utilises
        page.props.flash.info = null;
    }
}, { immediate: true });
// --- Fin du watcher ---
</script>

<style scoped>
</style>
