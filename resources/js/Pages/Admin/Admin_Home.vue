<template>
    <Head title="Dashboard Administrateur de Centre de Santé" />

    <div class="px-6 py-6">
        <div class="min-h-[calc(100vh-8rem)] bg-gradient-to-br from-indigo-50 to-white rounded-xl shadow-md border border-gray-200 p-10">
            <!-- Titre du dashboard -->
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Dashboard Administrateur </h1>
            <!-- <h1 class="text-3xl font-bold text-gray-800 mb-8">Dashboard Administrateur - {{ centreSante.nom }}</h1> -->

            <!-- ... (notification toast reste identique) ... -->

            <!-- Statistiques des utilisateurs -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
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

            <!-- Bouton d'ajout -->
            <div class="mb-6 flex justify-end">
                <button @click="showAddModal = true"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 flex items-center">
                    <i class="fas fa-plus mr-2"></i> Ajouter un utilisateur
                </button>
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
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom & Prénom</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
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
                                    <button @click="confirmDelete(user.id)" class="text-red-600 hover:text-red-900">
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


</template>

<script setup>
import { Head, usePage, router } from '@inertiajs/vue3'
import { ref, watch, computed } from 'vue'
// import UserFormModal from './Components/UserFormModal.vue'

const page = usePage()

const props = defineProps({
    users: {
        type: Array,
        default: () => []
    },
    centreSante: {
        type: Object,
        default: () => ({ nom: 'Centre de Santé' })
    }
});

// const props = defineProps({
//     users: Array,
//     centreSante: Object
// })

// États
const showAddModal = ref(false)
const showEditModal = ref(false)
const showDeleteModal = ref(false)
const editingUser = ref(null)
const userToDelete = ref(null)

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

// Actions
const editUser = (user) => {
    editingUser.value = { ...user }
    showEditModal.value = true
}

const confirmDelete = (id) => {
    userToDelete.value = id
    showDeleteModal.value = true
}

const deleteUser = () => {
    router.delete(`/admin/users/${userToDelete.value}`, {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteModal.value = false
            userToDelete.value = null
        }
    })
}

const handleUserAdded = (newUser) => {
    showAddModal.value = false
    // La liste se rafraîchira automatiquement via Inertia
}

const handleUserUpdated = () => {
    showEditModal.value = false
    editingUser.value = null
}

// Gestion des notifications
watch(() => page.props.flash, (newVal) => {
    if (newVal.success || newVal.error) {
        setTimeout(() => {
            page.props.flash = {}
        }, 5000)
    }
}, { deep: true })
</script>
