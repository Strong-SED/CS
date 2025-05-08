<template>

    <Head title="Dashboard Super Admin" />

    <div class="px-6 py-6">
        <div
            class="min-h-[calc(100vh-8rem)] bg-gradient-to-br from-indigo-50 to-white rounded-xl shadow-md border border-gray-200 p-10">
            <!-- Titre du dashboard -->
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Dashboard Super Admin</h1>

            <!-- Notification Toast -->
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

            <!-- Statistiques des administrateurs -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-indigo-600">
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Total Administrateurs</h3>
                    <p class="text-3xl font-bold text-indigo-600">{{ props.admin.length }}</p>
                </div>

                <!-- Vous pouvez ajouter d'autres statistiques ici -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-600">
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Nombre d'admin avec CS</h3>
                    <p class="text-3xl font-bold text-green-600">{{ adminsAvecCentre }}</p>
                </div>

                <!-- <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-600">
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Exemple </h3>
                    <p class="text-3xl font-bold text-purple-600">0</p>
                </div> -->
            </div>

            <!-- Liste des administrateurs -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Liste des Administrateurs</h3>
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
                                    Centre de Santé</th>

                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="item in admin" :key="item.id"> <!-- Supprimez .admin ici -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ item.nom }} {{ item.prenom }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ item.centreDeSante ? item.centreDeSante.nom : 'Pas de centre de santé à son actif' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <button @click="editAdmin(item)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        Modifier
                                    </button>
                                    <button @click="confirmDelete(item.id)" class="text-red-600 hover:text-red-900">
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
        <!-- Overlay avec opacité réduite -->
        <div class="fixed inset-0 bg-black opacity-50 bg-opacity-40 transition-opacity"></div>

        <!-- Conteneur centré -->
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <!-- Modale proprement dite -->
            <div
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all w-full max-w-2xl">
                <div class="bg-white px-6 py-6 w-full">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Modifier l'administrateur</h3>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                            <input v-model="editingAdmin.nom" type="text"
                                class="w-full rounded-md border border-gray-300 px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                            <input v-model="editingAdmin.prenom" type="text"
                                class="w-full rounded-md border border-gray-300 px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input v-model="editingAdmin.email" type="email"
                                class="w-full rounded-md border border-gray-300 px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                    <button @click="showEditModal = false"
                        class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Annuler
                    </button>
                    <button @click="updateAdmin"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modale de suppression -->
    <div v-if="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Overlay avec opacité réduite -->
        <div class="fixed inset-0 bg-black opacity-40 transition-opacity"></div>

        <!-- Conteneur centré -->
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <!-- Modale proprement dite -->
            <div
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all w-full max-w-2xl">
                <div class="bg-white px-6 py-6 w-full">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Confirmer la suppression</h3>
                    <p class="text-gray-600">Êtes-vous sûr de vouloir supprimer cet administrateur ? Cette action est
                        irréversible.</p>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                    <button @click="showDeleteModal = false"
                        class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Annuler
                    </button>
                    <button @click="deleteAdmin"
                        class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Supprimer définitivement
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
    admin: Array
})

props.admin.forEach(admin => {
    console.log(admin.nom, '->', admin.centre_sante.id);
});





const editingAdmin = ref(null)
const showEditModal = ref(false)
const showDeleteModal = ref(false)
const adminToDelete = ref(null)

const editAdmin = (admin) => {
    editingAdmin.value = { ...admin }
    showEditModal.value = true
}

const updateAdmin = () => {
    router.put(`/super-admin/${editingAdmin.value.id}`, editingAdmin.value, {
        preserveScroll: true,
        onSuccess: () => {
            showEditModal.value = false
        }
    })
}

const confirmDelete = (id) => {
    adminToDelete.value = id
    showDeleteModal.value = true
}

const deleteAdmin = () => {
    router.delete(`/super-admin/${adminToDelete.value}`, {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteModal.value = false
        }
    })
}

const adminsAvecCentre = computed(() =>
    props.admin.filter(a => a.centreDeSante !== null).length
);


// Optionnel : Fermer automatiquement la notification après 5 secondes
watch(() => page.props.flash, (newVal) => {
    if (newVal.success || newVal.error) {
        setTimeout(() => {
            page.props.flash = {}
        }, 5000)
    }
}, { deep: true });
</script>
