<template>
<Head title="Gestion des Analyses" />

<div class="px-6 py-6">
    <div class="min-h-[calc(100vh-8rem)] bg-gradient-to-br from-indigo-50 to-white rounded-xl shadow-md border border-gray-200 p-10">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-indigo-800">Tableau de Bord Laborantin</h1>
                <p class="text-gray-600">Gérez et saisissez les résultats des analyses médicales.</p>
            </div>
        </div>

        <TransitionRoot appear :show="showNotification" as="template">
            <div class="fixed inset-0 flex items-end justify-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end z-50">
                <TransitionChild enter="transform ease-out duration-300 transition" enter-from="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" enter-to="translate-y-0 opacity-100 sm:translate-x-0" leave="transition ease-in duration-100" leave-from="opacity-100" leave-to="opacity-0" class="w-full max-w-sm">
                    <div class="w-full h-20 border-l-4 bg-white rounded-xl shadow-lg pointer-events-auto" :class="{
                            'border-green-600': notificationType === 'success',
                            'border-red-600': notificationType === 'error',
                            'border-blue-600': notificationType === 'info' // Ajout pour le type 'info'
                        }">
                        <div class="w-full h-full p-3 grid grid-cols-5 items-center">
                            <div class="flex justify-center items-center col-span-1">
                                <i v-if="notificationType === 'success'" class="fas text-3xl text-green-600 fa-solid fa-circle-check"></i>
                                <i v-if="notificationType === 'error'" class="fas text-3xl text-red-600 fa-solid fa-circle-exclamation"></i>
                                <i v-if="notificationType === 'info'" class="fas text-3xl text-blue-600 fa-solid fa-circle-info"></i>
                            </div>
                            <p class="text-sm text-slate-900 w-full col-span-4">
                                {{ notificationMessage }}
                            </p>
                        </div>
                    </div>
                </TransitionChild>
            </div>
        </TransitionRoot>

        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8">
                <button @click="activeTab = 'en_cours'" :class="activeTab === 'en_cours' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    À Traiter
                    <span class="ml-2 bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                        {{ analysesEnCours.length }}
                    </span>
                </button>
                <button @click="activeTab = 'termine'" :class="activeTab === 'termine' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Terminées
                    <span class="ml-2 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                        {{ analysesTerminees.length }}
                    </span>
                </button>
                <button @click="activeTab = 'prescrit'" :class="activeTab === 'prescrit' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Prescrites
                    <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                        {{ analysesPrescrites.length }}
                    </span>
                </button>
            </nav>
        </div>

        <div v-if="analysesFiltrees.length > 0" class="space-y-4">
            <div v-for="analyse in analysesFiltrees" :key="analyse.id" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200 cursor-pointer">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">
                            Analyse: {{ getTypeAnalyse(analyse.type_analyse) }}
                        </h3>
                        <div class="mt-2 flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Date de la prescription: {{ formatDate(analyse.date_analyse) }}
                        </div>
                        <div class="mt-1 flex items-center text-sm text-gray-600">
                            <i class="fas fa-user mr-2"></i>
                            Patient: {{ analyse.consultation.dossier_medical.patient.prenom }} {{ analyse.consultation.dossier_medical.patient.nom }}
                        </div>
                        <div class="mt-1 flex items-center text-sm text-gray-600">
                            <i class="fas fa-hospital-alt mr-2"></i>
                            Centre: {{ analyse.centre.nom }}
                        </div>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="px-3 py-1 rounded-full text-xs font-medium mb-2" :class="{
                                'bg-yellow-100 text-yellow-800': analyse.statut === 'en_cours',
                                'bg-green-100 text-green-800': analyse.statut === 'termine',
                                'bg-blue-100 text-blue-800': analyse.statut === 'prescrit'
                            }">
                            {{ getStatutLabel(analyse.statut) }}
                        </span>
                        <button v-if="analyse.statut === 'en_cours'" @click.stop="openResultEntry(analyse)" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition mt-2">
                            <i class="fas fa-vial mr-2"></i> Saisir le Résultat
                        </button>
                        <button v-if="analyse.statut === 'termine'" @click.stop="openAnalyseDetails(analyse)" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-400 focus:ring focus:ring-gray-300 disabled:opacity-25 transition mt-2">
                            <i class="fas fa-eye mr-2"></i> Voir les Détails
                        </button>
                        <button v-if="analyse.statut === 'prescrit'" @click.stop="startAnalyse(analyse)" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition mt-2">
                            <i class="fas fa-play-circle mr-2"></i> Démarrer l'Analyse
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="text-center py-12 bg-white rounded-xl shadow-sm border border-gray-100">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">
                Aucune analyse {{ getStatutLabel(activeTab).toLowerCase() }}
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                Aucune analyse n'a été trouvée pour ce statut.
            </p>
        </div>
    </div>

    <TransitionRoot appear :show="showResultEntryModal" as="template">
        <Dialog as="div" class="relative z-50" @close="showResultEntryModal = false">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black/25 backdrop-blur-sm" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                        <DialogPanel class="w-full max-w-lg transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 flex items-center">
                                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100 mr-3">
                                    <i class="fas fa-flask text-indigo-600"></i>
                                </div>
                                Saisir le Résultat de l'Analyse
                            </DialogTitle>

                            <div class="mt-6 space-y-4">
                                <p class="text-sm text-gray-700">
                                    **Patient :** {{ selectedAnalyse?.consultation.dossier_medical.patient.prenom }} {{ selectedAnalyse?.consultation.dossier_medical.patient.nom }}
                                </p>
                                <p class="text-sm text-gray-700">
                                    **Type d'Analyse :** {{ getTypeAnalyse(selectedAnalyse?.type_analyse) }}
                                </p>
                                <div>
                                    <label for="resultat" class="block text-sm font-medium text-gray-700">Résultat de l'analyse</label>
                                    <textarea id="resultat" v-model="form.resultat" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Saisissez ici le résultat de l'analyse..."></textarea>
                                    <div v-if="form.errors.resultat" class="text-red-500 text-xs mt-1">{{ form.errors.resultat }}</div>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500" @click="closeResultEntryModal">
                                    Annuler
                                </button>
                                <button type="button" @click="submitResult" :disabled="form.processing" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                                    <i class="fas fa-save mr-2"></i> Enregistrer le Résultat
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>

    <TransitionRoot appear :show="showAnalyseDetailsModal" as="template">
        <Dialog as="div" class="relative z-50" @close="showAnalyseDetailsModal = false">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black/25 backdrop-blur-sm" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                        <DialogPanel class="w-full max-w-3xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 flex items-center">
                                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-blue-100 mr-3">
                                    <i class="fas fa-info-circle text-blue-600"></i>
                                </div>
                                Détails de l'Analyse
                            </DialogTitle>

                            <div class="mt-6 space-y-6">
                                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Type d'Analyse</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ getTypeAnalyse(selectedAnalyse?.type_analyse) }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Statut</label>
                                        <p class="mt-1">
                                            <span class="px-2 py-1 text-xs rounded-full font-medium" :class="{
                                                        'bg-yellow-100 text-yellow-800': selectedAnalyse?.statut === 'en_cours',
                                                        'bg-green-100 text-green-800': selectedAnalyse?.statut === 'termine',
                                                        'bg-blue-100 text-blue-800': selectedAnalyse?.statut === 'prescrit'
                                                    }">
                                                {{ getStatutLabel(selectedAnalyse?.statut) }}
                                            </span>
                                        </p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Patient</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ selectedAnalyse?.consultation.dossier_medical.patient.prenom }} {{ selectedAnalyse?.consultation.dossier_medical.patient.nom }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Date de prescription</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ formatDate(selectedAnalyse?.date_analyse) }}</p>
                                    </div>

                                    <div v-if="selectedAnalyse?.resultat">
                                        <label class="block text-sm font-medium text-gray-700">Résultat</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ selectedAnalyse?.resultat }}</p>
                                    </div>

                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500" @click="showAnalyseDetailsModal = false">
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
    usePage,
    useForm // Importer useForm pour gérer le formulaire de saisie
} from '@inertiajs/vue3'
import {
    ref,
    computed,
    watch
} from 'vue'
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot
} from '@headlessui/vue'
import {
    AnalyseMedicale
} from '@/Enums/AnalyseMedicale.js' // IMPORTANT : Ajuste le chemin si besoin

// Accéder aux props d'Inertia, y compris les messages flash
const page = usePage();

// Props reçues du contrôleur Laravel
const props = defineProps({
    analyses: {
        type: Array,
        required: true
    }
})

// État local de la vue
const activeTab = ref('en_cours') // Onglet par défaut : "À Traiter"
const showResultEntryModal = ref(false) // Pour le modal de saisie de résultat
const showAnalyseDetailsModal = ref(false) // Pour le modal de détails d'analyse
const selectedAnalyse = ref(null) // L'analyse sélectionnée pour les modaux

// Formulaire pour la saisie des résultats
const form = useForm({
    resultat: '',
});

// --- Variables d'état pour la notification ---
const showNotification = ref(false);
const notificationMessage = ref('');
const notificationType = ref('success'); // 'success', 'error', 'info'
let notificationTimeout = null;
// --- Fin des variables de notification ---

// Propriétés calculées pour filtrer les analyses par statut
const analysesEnCours = computed(() => {
    return props.analyses.filter(a => a.statut === 'en_cours')
        .sort((a, b) => new Date(a.date_analyse) - new Date(b.date_analyse)); // Trier par date ascendante
})

const analysesTerminees = computed(() => {
    return props.analyses.filter(a => a.statut === 'termine')
        .sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at)); // Trier par date de mise à jour descendante
})

const analysesPrescrites = computed(() => {
    return props.analyses.filter(a => a.statut === 'prescrit')
        .sort((a, b) => new Date(a.date_analyse) - new Date(b.date_analyse)); // Trier par date ascendante
})

const analysesFiltrees = computed(() => {
    if (activeTab.value === 'en_cours') {
        return analysesEnCours.value
    } else if (activeTab.value === 'termine') {
        return analysesTerminees.value
    } else if (activeTab.value === 'prescrit') {
        return analysesPrescrites.value
    }
    return []
})

// Méthodes utilitaires
function formatDate(dateString) {
    if (!dateString) return ''
    const date = new Date(dateString)
    return date.toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

function getTypeAnalyse(typeCode) {
    return AnalyseMedicale[typeCode]?.name || typeCode; // Retourne le nom lisible ou le code si inconnu
}

function getStatutLabel(statut) {
    switch (statut) {
        case 'prescrit':
            return 'Prescrite';
        case 'en_cours':
            return 'À Traiter';
        case 'termine':
            return 'Terminée';
        default:
            return statut;
    }
}

// Gestion des modaux
function openResultEntry(analyse) {
    selectedAnalyse.value = analyse;
    form.resultat = analyse.resultat || ''; // Pré-remplir si un résultat existe déjà
    showResultEntryModal.value = true;
}

function closeResultEntryModal() {
    showResultEntryModal.value = false;
    selectedAnalyse.value = null;
    form.reset(); // Réinitialiser le formulaire
    form.clearErrors(); // Effacer les erreurs
}

function openAnalyseDetails(analyse) {
    selectedAnalyse.value = analyse;
    showAnalyseDetailsModal.value = true;
}

// Soumission du résultat de l'analyse
function submitResult() {
    if (!selectedAnalyse.value) return;

    form.put(route('laborantin.analyses.updateResult', selectedAnalyse.value.id), {
        onSuccess: () => {
            closeResultEntryModal();
            router.reload({
                preserveScroll: true,
                onSuccess: (page) => {
                    if (page.props.flash.success) {
                        showToast(page.props.flash.success, 'success');
                    }
                }
            });
        },
        onError: (errors) => {
            const errorMessage = Object.values(errors).flat()[0] || "Une erreur est survenue lors de l'enregistrement du résultat.";
            showToast(errorMessage, 'error');
            console.error("Erreur lors de l'enregistrement du résultat:", errors);
        },
    });
}

// Démarrer une analyse (passer de 'prescrit' à 'en_cours')
function startAnalyse(analyse) {
    if (!analyse) return;

    router.put(route('laborantin.analyses.start', analyse.id), {}, {
        preserveScroll: true,
        onSuccess: (page) => {
            if (page.props.flash.success) {
                showToast(page.props.flash.success, 'success');
            }
            router.reload(); // Recharger pour mettre à jour la liste des analyses
        },
        onError: (errors) => {
            const errorMessage = Object.values(errors).flat()[0] || "Une erreur est survenue lors du démarrage de l'analyse.";
            showToast(errorMessage, 'error');
            console.error("Erreur lors du démarrage de l'analyse:", errors);
        },
    });
}

// Fonction pour afficher la notification
function showToast(message, type = 'success') {
    if (notificationTimeout) {
        clearTimeout(notificationTimeout);
    }
    notificationMessage.value = message;
    notificationType.value = type;
    showNotification.value = true;
    notificationTimeout = setTimeout(() => {
        showNotification.value = false;
        notificationMessage.value = '';
    }, 3000);
}

// Watchers pour les messages flash d'Inertia
watch(() => page.props.flash.success, (message) => {
    if (message) {
        showToast(message, 'success');
        page.props.flash.success = null;
    }
}, {
    immediate: true
});

watch(() => page.props.flash.error, (message) => {
    if (message) {
        showToast(message, 'error');
        page.props.flash.error = null;
    }
}, {
    immediate: true
});

watch(() => page.props.flash.info, (message) => {
    if (message) {
        showToast(message, 'info');
        page.props.flash.info = null;
    }
}, {
    immediate: true
});
</script>

<style scoped>
/* Vos styles TailwindCSS ou personnalisés ici */
</style>
