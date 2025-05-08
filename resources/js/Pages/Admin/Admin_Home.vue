<template>

    <Head title="Dashboard Administrateur de Centre de Santé" />

    <div class="px-6 py-6">
        <div
            class="min-h-[calc(100vh-8rem)] bg-gradient-to-br from-indigo-50 to-white rounded-xl shadow-md border border-gray-200 p-10">
            <!-- Titre du dashboard -->
            <!-- <h1 class="text-3xl font-bold text-gray-800 mb-8">Dashboard Administrateur </h1> -->
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Dashboard Administrateur - {{ props.centreSante.nom }}
            </h1>

            <!-- ... (notification toast reste identique) ... -->
            <div v-if="$page.props.flash.success || $page.props.flash.error"
                class="w-96 h-20 border-l-5 absolute top-26 right-10 bg-white rounded-xl shadow-lg transition-all duration-300"
                :class="{
                    'border-green-600': $page.props.flash.success,
                    'border-red-600': $page.props.flash.error
                }">
                <div class="w-full h-full p-3 grid grid-cols-5 items-center">
                    <div class="flex justify-center items-center col-span-1">
                        <i v-if="$page.props.flash.success"
                            class="fas text-3xl text-green-600 fa-solid fa-circle-check"></i>
                        <i v-if="$page.props.flash.error"
                            class="fas text-3xl text-red-600 fa-solid fa-circle-exclamation"></i>
                    </div>
                    <p class="text-sm text-slate-900 w-full col-span-4">
                        {{ $page.props.flash.success || $page.props.flash.error }}
                    </p>
                </div>
            </div>

            <!-- Statistiques des utilisateurs -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-26">
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-indigo-600">
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Total Utilisateurs</h3>
                    <p class="text-3xl font-bold text-indigo-600">{{ props.users.length }}</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-600">
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Médecins</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ stats.medecins }}</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-600">
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Secrétaires</h3>
                    <p class="text-3xl font-bold text-green-600">{{ stats.secretaires }}</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-600">
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Laborantins</h3>
                    <p class="text-3xl font-bold text-purple-600">{{ stats.laborantins }}</p>
                </div>
            </div>

            <!-- Liste des utilisateurs -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Liste des Utilisateurs</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nom & Prénom</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Rôle</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="user in users" :key="user.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ user.nom }} {{ user.prenom }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span :class="{
                                        'bg-blue-100 text-blue-800': user.role === 'medecin',
                                        'bg-green-100 text-green-800': user.role === 'secretaire',
                                        'bg-purple-100 text-purple-800': user.role === 'laborantin'
                                    }" class="px-2 py-1 rounded-full text-xs font-semibold">
                                        {{ getRoleLabel(user.role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <button @click="editUser(user)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        Modifier
                                    </button>
                                    <button @click="confirmDelete(user)" class="text-red-600 hover:text-red-900">
                                        Supprimer
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Modale d'édition -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-black opacity-50 bg-opacity-40 transition-opacity"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all w-full max-w-2xl">
                <div class="bg-white px-6 py-6 w-full">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Modifier l'utilisateur</h3>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                            <input v-model="editingUser.nom" type="text"
                                class="w-full rounded-md border border-gray-300 px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                            <input v-model="editingUser.prenom" type="text"
                                class="w-full rounded-md border border-gray-300 px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input v-model="editingUser.email" type="email"
                                class="w-full rounded-md border border-gray-300 px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                            <select v-model="editingUser.role" @change="handleRoleChange"
                                class="w-full rounded-md border border-gray-300 px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="medecin">Médecin</option>
                                <option value="secretaire">Secrétaire</option>
                                <option value="laborantin">Laborantin</option>
                            </select>
                        </div>

                        <!-- Champ spécialité conditionnel -->
                        <div v-if="editingUser.role === 'medecin'">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Spécialité</label>
                            <input v-model="editingUser.specialite" type="text"
                                class="w-full rounded-md border border-gray-300 px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                    <button @click="showEditModal = false"
                        class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                        Annuler
                    </button>
                    <button @click="updateUser"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modale de suppression -->
    <div v-if="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-black opacity-40 transition-opacity"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all w-full max-w-md">
                <div class="bg-white px-6 py-6 w-full">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Confirmer la suppression</h3>
                    <p class="text-gray-600">Êtes-vous sûr de vouloir supprimer l'utilisateur {{ editingUser?.nom }} {{
                        editingUser?.prenom }} ?</p>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                    <button @click="showDeleteModal = false"
                        class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                        Annuler
                    </button>
                    <button @click="deleteUser"
                        class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                        Confirmer
                    </button>
                </div>
            </div>
        </div>
    </div>


</template>

<script setup>
import { Head, usePage, router } from '@inertiajs/vue3'
import { ref, watch, computed } from 'vue'

const page = usePage()

const props = defineProps({
    users: Array,
    centreSante: Object
})

// États
const showEditModal = ref(false)
const showDeleteModal = ref(false)
const editingUser = ref({
    id: null,
    nom: '',
    prenom: '',
    email: '',
    role: '',
    specialite: null
})

// Statistiques calculées
const stats = computed(() => ({
    medecins: props.users.filter(u => u.role === 'medecin').length,
    secretaires: props.users.filter(u => u.role === 'secretaire').length,
    laborantins: props.users.filter(u => u.role === 'laborantin').length,
    total: props.users.length
}))

// Méthodes utilitaires
const getRoleLabel = (role) => {
    const roles = {
        'medecin': 'Médecin',
        'secretaire': 'Secrétaire',
        'laborantin': 'Laborantin'
    }
    return roles[role] || role
}

// Gestion du changement de rôle
const handleRoleChange = () => {
    if (editingUser.value.role !== 'medecin') {
        editingUser.value.specialite = null
    }
}

// Actions
const editUser = (user) => {
    editingUser.value = {
        id: user.id,
        nom: user.nom,
        prenom: user.prenom,
        email: user.email,
        role: user.role,
        specialite: user.specialite || null
    }
    showEditModal.value = true
}

const confirmDelete = (user) => {
    editingUser.value = { ...user }
    showDeleteModal.value = true
}

const updateUser = () => {
    // Préparation des données avant envoi
    const userData = {
        nom: editingUser.value.nom,
        prenom: editingUser.value.prenom,
        email: editingUser.value.email,
        role: editingUser.value.role,
        specialite: editingUser.value.role === 'medecin' ? editingUser.value.specialite : null
    }

    router.put(`/AdminCS/${editingUser.value.id}`, userData, {
        preserveScroll: true,
        onSuccess: () => {
            showEditModal.value = false
            editingUser.value = {
                id: null,
                nom: '',
                prenom: '',
                email: '',
                role: '',
                specialite: null
            }
        }
    })
}

const deleteUser = () => {
    router.delete(`/AdminCS/${editingUser.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteModal.value = false
            editingUser.value = {
                id: null,
                nom: '',
                prenom: '',
                email: '',
                role: '',
                specialite: null
            }
        }
    })
}

// Gestion des notifications
watch(() => page.props.flash, (newVal) => {
    if (newVal.success || newVal.error) {
        setTimeout(() => {
            page.props.flash = {}
        }, 5000)
    }
}, { deep: true })

// Réinitialisation quand la modale se ferme
watch(showEditModal, (newVal) => {
    if (!newVal) {
        editingUser.value = {
            id: null,
            nom: '',
            prenom: '',
            email: '',
            role: '',
            specialite: null
        }
    }
})
</script>
