<template>
<Head title="Gestion des Rendez-vous" />

<div class="px-6 py-6">
    <div class="min-h-[calc(100vh-8rem)] bg-gradient-to-br from-indigo-50 to-white rounded-xl shadow-md border border-gray-200 p-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-indigo-800">Gestion des Rendez-vous</h1>
                <p class="text-gray-600">Calendrier et historique des consultations</p>
            </div>
            <div class="flex items-center space-x-2">
                <button @click="showAddModal = true" class="flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Nouveau RDV
                </button>
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

        <!-- Stats rapides -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div v-for="stat in stats" :key="stat.label" class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-full mr-4" :class="stat.bgColor">
                        <i :class="stat.icon" class="text-white"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ stat.label }}</p>
                        <p class="text-2xl font-bold">{{ stat.value }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendrier et Liste -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Calendrier -->
            <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Calendrier des RDV</h2>
                    <div class="flex space-x-2">
                        <button @click="prevMonth" class="p-2 rounded-full hover:bg-gray-100">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span class="font-medium">{{ currentMonthName }} {{ currentYear }}</span>
                        <button @click="nextMonth" class="p-2 rounded-full hover:bg-gray-100">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-7 gap-1 mb-2">
                    <div v-for="day in daysOfWeek" :key="day" class="text-center text-sm font-medium text-gray-500 py-2">
                        {{ day }}
                    </div>
                </div>

                <div class="grid grid-cols-7 gap-1">
                    <div v-for="day in calendarDays" :key="day.date" @click="selectDate(day.date)" :class="{
                   'bg-gray-100': !day.isCurrentMonth,
                   'border-indigo-500 border-2': isToday(day.date),
                   'cursor-pointer': true
                 }" class="h-24 p-1 border rounded-md overflow-hidden relative">
                        <div class="flex justify-between items-start">
                            <span :class="{
                  'text-gray-400': !day.isCurrentMonth,
                  'font-bold text-indigo-600': isToday(day.date)
                }">{{ day.day }}</span>
                            <span v-if="hasAppointments(day.date)" class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        </div>

                        <div class="absolute inset-0 mt-6 overflow-y-auto">
                            <div v-for="rdv in getAppointmentsForDay(day.date)" :key="rdv.id" @click.stop="openRdvDetails(rdv)" class="text-xs p-1 mb-1 rounded truncate" :class="getRdvStatusClass(rdv.etat)">
                                {{ formatTime(rdv.date_rdv) }} - {{ rdv.patient.nom }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des RDV -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Historique des RDV</h2>
                    <div class="relative">
                        <select v-model="filterEtat" class="appearance-none bg-gray-50 border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-indigo-500 text-sm">
                            <option value="tous">Tous les états</option>
                            <option v-for="etat in etats" :key="etat.value" :value="etat.value">{{ etat.label }}</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <div v-for="rdv in filteredRdvs" :key="rdv.id" @click="openRdvDetails(rdv)" class="p-3 border rounded-lg cursor-pointer hover:shadow-md transition-shadow" :class="getRdvStatusBorderClass(rdv.etat)">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-medium">{{ rdv.patient.nom }} {{ rdv.patient.prenom }}</h3>
                                <p class="text-sm text-gray-600">{{ formatDateTime(rdv.date_rdv) }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full font-medium" :class="getRdvStatusClass(rdv.etat)">
                                {{ getEtatLabel(rdv.etat) }}
                            </span>
                        </div>
                        <p v-if="rdv.motif" class="text-sm mt-1 text-gray-700 truncate">
                            <i class="fas fa-comment-medical mr-1 text-indigo-500"></i> {{ rdv.motif }}
                        </p>
                    </div>

                    <div v-if="filteredRdvs.length === 0" class="text-center py-8 text-gray-500">
                        <i class="fas fa-calendar-times text-3xl mb-2"></i>
                        <p>Aucun rendez-vous trouvé</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal d'ajout de rdv -->
    <TransitionRoot appear :show="showAddModal" as="template">
        <Dialog as="div" class="relative z-50" @close="showAddModal = false">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black/25 backdrop-blur-sm" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                        <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100 mr-3">
                                        <i class="fas fa-calendar-plus text-indigo-600"></i>
                                    </div>
                                    Programmer un nouveau rendez-vous
                                </div>
                                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-500">
                                    <i class="fas fa-times"></i>
                                </button>
                            </DialogTitle>

                            <form @submit.prevent="submitAddRdv" class="mt-6 space-y-4">
                                <div>
                                    <label for="patient" class="block text-sm font-medium text-gray-700">Patient *</label>
                                    <select v-model="form.patient_id" id="patient" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                                        <option value="" disabled>Sélectionnez un patient</option>
                                        <option v-for="patient in patients" :key="patient.id" :value="patient.id">
                                            {{ patient.nom }} {{ patient.prenom }}
                                        </option>
                                    </select>
                                    <p v-if="form.errors.patient_id" class="mt-1 text-sm text-red-600">{{ form.errors.patient_id }}</p>
                                </div>

                                <div>
                                    <label for="addDate" class="block text-sm font-medium text-gray-700">Date et heure *</label>
                                    <input v-model="form.date_heure" type="datetime-local" id="addDate" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                                    <p v-if="form.errors.date_heure" class="mt-1 text-sm text-red-600">{{ form.errors.date_heure }}</p>
                                </div>

                                <div>
                                    <label for="motif" class="block text-sm font-medium text-gray-700">Motif *</label>
                                    <textarea v-model="form.motif" id="motif" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border"></textarea>
                                    <p v-if="form.errors.motif" class="mt-1 text-sm text-red-600">{{ form.errors.motif }}</p>
                                </div>

                                <div class="mt-6 flex justify-end space-x-3">
                                    <button type="button" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500" @click="showAddModal = false">
                                        Annuler
                                    </button>
                                    <button type="submit" :disabled="form.processing" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <i v-if="form.processing" class="fas fa-spinner fa-spin mr-2"></i>
                                        Enregistrer le RDV
                                    </button>
                                </div>
                            </form>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>

    <!-- Modal Détails RDV -->
    <TransitionRoot appear :show="showRdvModal" as="template">
        <Dialog as="div" class="relative z-50" @close="showRdvModal = false">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black/25 backdrop-blur-sm" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                        <DialogPanel class="w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100 mr-3">
                                        <i class="fas fa-calendar-alt text-indigo-600"></i>
                                    </div>
                                    Détails du rendez-vous
                                </div>
                                <button @click="showRdvModal = false" class="text-gray-400 hover:text-gray-500">
                                    <i class="fas fa-times"></i>
                                </button>
                            </DialogTitle>

                            <div class="mt-6 grid grid-cols-1 gap-y-4 gap-x-6 sm:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Patient</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ selectedRdv?.patient.nom }} {{ selectedRdv?.patient.prenom }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Date et heure</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ formatDateTime(selectedRdv?.date_heure) }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Motif</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ selectedRdv?.motif || 'Non spécifié' }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Statut</label>
                                    <p class="mt-1">
                                        <span class="px-2 py-1 text-xs rounded-full font-medium" :class="getRdvStatusClass(selectedRdv?.etat)">
                                            {{ getEtatLabel(selectedRdv?.etat) }}
                                        </span>
                                    </p>
                                </div>

                                <div v-if="selectedRdv?.notes" class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Notes</label>
                                    <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ selectedRdv.notes }}</p>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button v-if="selectedRdv?.etat === 'planifié' || 'reporté'" @click="updateRdvStatus('annulé')" class="inline-flex items-center px-4 py-2 bg-red-100 border border-transparent rounded-md font-medium text-xs text-red-700 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <i class="fas fa-times-circle mr-2"></i> Annuler
                                </button>
                                <button v-if="selectedRdv?.etat === 'planifié' || 'reporté'" @click="showRescheduleModal = true" class="inline-flex items-center px-4 py-2 bg-yellow-100 border border-transparent rounded-md font-medium text-xs text-yellow-700 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                    <i class="fas fa-calendar-week mr-2"></i> Reporter
                                </button>
                                <button v-if="selectedRdv?.etat === 'planifié' || 'reporté'" @click="updateRdvStatus('respecté')" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <i class="fas fa-check-circle mr-2"></i> Marquer comme respecté
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>

    <!-- Modal Reporter RDV -->
    <TransitionRoot appear :show="showRescheduleModal" as="template">
        <Dialog as="div" class="relative z-50" @close="showRescheduleModal = false">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black/25 backdrop-blur-sm" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                        <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 flex items-center">
                                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100 mr-3">
                                    <i class="fas fa-calendar-plus text-indigo-600"></i>
                                </div>
                                Reporter le rendez-vous
                            </DialogTitle>

                            <div class="mt-6 space-y-4">
                                <div>
                                    <label for="newDate" class="block text-sm font-medium text-gray-700">Nouvelle date *</label>
                                    <input v-model="newRdvDate" type="datetime-local" id="newDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                                </div>

                                <div>
                                    <label for="rescheduleReason" class="block text-sm font-medium text-gray-700">Raison du report</label>
                                    <textarea v-model="rescheduleReason" id="rescheduleReason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border"></textarea>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500" @click="showRescheduleModal = false">
                                    Annuler
                                </button>
                                <button type="button" @click="rescheduleRdv" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    Confirmer le report
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
    useForm
} from '@inertiajs/vue3' // Importez useForm
import {
    ref,
    computed,
    onMounted,
    watch
} from 'vue'
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot
} from '@headlessui/vue'

// Props
const props = defineProps({
    rdvs: {
        type: Array,
        required: true
    },
    patients: {
        type: Array,
        required: true
    },
    auth: { // Ajoutez la prop auth pour accéder à l'utilisateur connecté
        type: Object,
        required: true
    },

    flash: { // Assurez-vous que cette prop est bien passée par votre contrôleur
        type: Object,
        default: () => ({
            success: null,
            error: null
        }),
    },
})

// États
const showRdvModal = ref(false)
const showRescheduleModal = ref(false)
const showAddModal = ref(false) // Nouveau: État pour la modale d'ajout
const selectedRdv = ref(null)
const filterEtat = ref('tous')
const currentDate = ref(new Date())
const newRdvDate = ref('')
const rescheduleReason = ref('')



// Nouveau: Formulaire pour ajouter un RDV
const form = useForm({
    patient_id: '',
    date_heure: '',
    motif: '',
    medecin_id: props.auth.user.id, // Assurez-vous que le médecin connecté est passé
    centre_de_sante_id: 1 // TODO: Récupérer dynamiquement l'ID du centre de santé si applicable
})

// Données statiques
const daysOfWeek = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim']
const etats = [{
        value: 'planifié',
        label: 'Planifié'
    },
    {
        value: 'respecté',
        label: 'Respecté'
    },
    {
        value: 'annulé',
        label: 'Annulé'
    },
    {
        value: 'reporté',
        label: 'Reporté'
    }
]

// Stats calculées
const stats = computed(() => [{
        label: 'RDV planifiés',
        value: props.rdvs.filter(r => r.etat === 'planifié').length,
        icon: 'fas fa-calendar-check',
        bgColor: 'bg-blue-500'
    },
    {
        label: 'RDV respectés',
        value: props.rdvs.filter(r => r.etat === 'respecté').length,
        icon: 'fas fa-check-circle',
        bgColor: 'bg-green-500'
    },
    {
        label: 'RDV annulés',
        value: props.rdvs.filter(r => r.etat === 'annulé').length,
        icon: 'fas fa-times-circle',
        bgColor: 'bg-red-500'
    },
    {
        label: 'RDV reportés',
        value: props.rdvs.filter(r => r.etat === 'reporté').length,
        icon: 'fas fa-calendar-week',
        bgColor: 'bg-yellow-500'
    }
])

// Données du calendrier
const currentMonthName = computed(() => {
    return currentDate.value.toLocaleString('fr-FR', {
        month: 'long'
    })
})

const currentYear = computed(() => {
    return currentDate.value.getFullYear()
})

const calendarDays = computed(() => {
    const year = currentDate.value.getFullYear()
    const month = currentDate.value.getMonth()

    const firstDay = new Date(year, month, 1)
    const lastDay = new Date(year, month + 1, 0)

    const daysInMonth = lastDay.getDate()
    const startDay = firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1 // Lundi = 0

    const days = []

    // Jours du mois précédent
    const prevMonthLastDay = new Date(year, month, 0).getDate()
    for (let i = startDay - 1; i >= 0; i--) {
        const day = prevMonthLastDay - i
        days.push({
            day,
            date: new Date(year, month - 1, day),
            isCurrentMonth: false
        })
    }

    // Jours du mois courant
    for (let i = 1; i <= daysInMonth; i++) {
        days.push({
            day: i,
            date: new Date(year, month, i),
            isCurrentMonth: true
        })
    }

    // Jours du mois suivant
    const totalDays = days.length
    const remainingDays = 42 - totalDays // 6 semaines complètes pour le calendrier
    for (let i = 1; i <= remainingDays; i++) {
        days.push({
            day: i,
            date: new Date(year, month + 1, i),
            isCurrentMonth: false
        })
    }

    return days
})

// RDV filtrés
const filteredRdvs = computed(() => {
    let result = [...props.rdvs].sort((a, b) => new Date(a.date_rdv) - new Date(b.date_rdv))

    if (filterEtat.value !== 'tous') {
        result = result.filter(rdv => rdv.etat === filterEtat.value)
    }

    return result
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

function formatTime(dateString) {
    if (!dateString) return ''
    const date = new Date(dateString)
    return date.toLocaleString('fr-FR', {
        hour: '2-digit',
        minute: '2-digit'
    })
}

function isToday(date) {
    const today = new Date()
    return date.getDate() === today.getDate() &&
        date.getMonth() === today.getMonth() &&
        date.getFullYear() === today.getFullYear()
}

function hasAppointments(date) {
    return props.rdvs.some(rdv => {
        const rdvDate = new Date(rdv.date_heure) // Utilisez date_heure ici
        return rdvDate.getDate() === date.getDate() &&
            rdvDate.getMonth() === date.getMonth() &&
            rdvDate.getFullYear() === date.getFullYear()
    })
}

function getAppointmentsForDay(date) {
    return props.rdvs.filter(rdv => {
        const rdvDate = new Date(rdv.date_heure) // Utilisez date_heure ici
        return rdvDate.getDate() === date.getDate() &&
            rdvDate.getMonth() === date.getMonth() &&
            rdvDate.getFullYear() === date.getFullYear()
    })
}

function getRdvStatusClass(etat) {
    switch (etat) {
        case 'planifié':
            return 'bg-blue-100 text-blue-800'
        case 'respecté':
            return 'bg-green-100 text-green-800'
        case 'annulé':
            return 'bg-red-100 text-red-800'
        case 'reporté':
            return 'bg-yellow-100 text-yellow-800'
        default:
            return 'bg-gray-100 text-gray-800'
    }
}

function getRdvStatusBorderClass(etat) {
    switch (etat) {
        case 'planifié':
            return 'border-blue-200'
        case 'respecté':
            return 'border-green-200'
        case 'annulé':
            return 'border-red-200'
        case 'reporté':
            return 'border-yellow-200'
        default:
            return 'border-gray-200'
    }
}

function getEtatLabel(etat) {
    const found = etats.find(e => e.value === etat)
    return found ? found.label : etat
}

function prevMonth() {
    currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() - 1, 1)
}

function nextMonth() {
    currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() + 1, 1)
}

function selectDate(date) {
    // Si vous souhaitez pré-remplir la date dans la modale d'ajout, vous pouvez faire ceci :
    // form.date_heure = date.toISOString().slice(0, 16);
    // showAddModal.value = true;
    console.log('Date sélectionnée:', date)
}

function openRdvDetails(rdv) {
    selectedRdv.value = rdv
    showRdvModal.value = true
}

function updateRdvStatus(newStatus) {
    if (!selectedRdv.value) return

    const updateData = {
        etat: newStatus,
        notes: newStatus === 'annulé' ? 'Rendez-vous annulé par le médecin' : ''
    }

    router.put(route('rdvs.update', selectedRdv.value.id), updateData, {
        preserveScroll: true,
        onSuccess: () => {
            showRdvModal.value = false
            showToast(`Rendez-vous ${newStatus} avec succès !`, 'success')
            router.reload({ preserveScroll: true })
        },
        onError: (errors) => {
            console.error("Erreurs lors de la mise à jour du statut:", errors)
            const errorMessage = Object.values(errors).flat()[0] || "Une erreur est survenue lors de la mise à jour du statut."
            showToast(errorMessage, 'error')
        }
    })
}

function rescheduleRdv() {
    if (!selectedRdv.value || !newRdvDate.value) return

    router.put(route('rdvs.update', selectedRdv.value.id), {
        etat: 'reporté',
        date_heure: newRdvDate.value,
        motif_report: rescheduleReason.value ? `Reporté - Raison: ${rescheduleReason.value}` : 'Rendez-vous reporté'
    }, {
        preserveScroll: true,
        onSuccess: () => {
            showRescheduleModal.value = false
            showRdvModal.value = false
            router.reload({ preserveScroll: true,
                onSuccess: () => {
                    showToast('Rendez-vous reporté avec succès !', 'success');
                }
            })
        },
        onError: (errors) => {
            console.error("Erreurs lors du report du RDV:", errors);
            const errorMessage = Object.values(errors).flat()[0] || "Une erreur est survenue lors du report du rendez-vous.";
            showToast(errorMessage, 'error');
        }
    })
}

// Nouveau: Méthode pour soumettre le formulaire d'ajout de RDV
const submitAddRdv = () => {
    form.post(route('rdvs.store'), {
        onSuccess: () => {
            form.reset()
            showAddModal.value = false
            router.reload({ preserveScroll: true,
                onSuccess: () => {
                    showToast('Rendez-vous ajouté avec succès !', 'success');
                }
            })
        },
        onError: (errors) => {
            console.error("Erreurs lors de l'ajout du RDV:", errors);
            // Afficher le message d'erreur général ou le premier message d'erreur de validation
            const errorMessage = Object.values(errors).flat()[0] || "Une erreur est survenue lors de l'ajout du rendez-vous.";
            showToast(errorMessage, 'error');
        }
    })
}

// Initialisation
onMounted(() => {
    // Format initial pour le datetime-local dans la modale de report
    const now = new Date()
    const formatted = now.toISOString().slice(0, 16)
    newRdvDate.value = formatted

    // Initialisation de la date/heure pour la modale d'ajout
    form.date_heure = formatted
})




// Nouveau: Garder un œil sur les messages flash d'Inertia
// C'est une bonne pratique pour afficher les messages flash (succès/erreur)
// envoyés depuis le contrôleur Laravel après une redirection

// Nouveau: États pour la notification Toast
const showNotification = ref(false)
const notificationMessage = ref('')
const notificationType = ref('success') // ou 'error'

// Nouvelle fonction pour afficher la notification
function showToast(message, type) {
    notificationMessage.value = message
    notificationType.value = type
    showNotification.value = true

    // Cacher la notification après 3 secondes (3000 ms)
    setTimeout(() => {
        showNotification.value = false
    }, 3000)
}

watch(() => props.flash.success, (message) => {
    if (message) {
        showToast(message, 'success');
    }
}, {
    immediate: true
}); // immediate: true pour vérifier au montage initial

watch(() => props.flash.error, (message) => {
    if (message) {
        showToast(message, 'error');
    }
}, {
    immediate: true
}); // immediate: true pour vérifier au montage initial

// ... (le reste de votre script existant)
</script>
