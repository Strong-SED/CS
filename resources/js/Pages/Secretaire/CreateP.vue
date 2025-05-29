<template>
<Head title="Gestion des Patients" />

<div class="px-6 py-6">
    <div class="min-h-[calc(100vh-8rem)] bg-gradient-to-br from-indigo-50 to-white rounded-xl shadow-md border border-gray-200 p-10">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-indigo-800">Gestion des Patients</h1>
                <p class="text-gray-600">Gérez les dossiers médicaux des patients</p>
            </div>
            <button @click="showAddPatientModal = true" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-md flex items-center">
                <i class="fas fa-user-plus mr-2"></i> Nouveau Patient
            </button>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-3">
                    <div class="relative">
                        <input type="text" v-model="searchQuery" @input="searchPatients" placeholder="Rechercher par nom, prénom, ID..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
                        <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                    </div>
                </div>
                <div>
                    <select v-model="genreFilter" @change="fetchPatients" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
                        <option value="">Tous les genres</option>
                        <option value="M">Masculin</option>
                        <option value="F">Féminin</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Patients Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nom Complet</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date Naiss.</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Genre</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Téléphone</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="patient in patients.data" :key="patient.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ patient.id
                                    }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <i class="fas fa-user text-indigo-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ patient.prenom }}</div>
                                        <div class="text-sm text-gray-500">{{ patient.email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{
                                    formatDate(patient.date_naissance) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" :class="patient.genre === 'M' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800'">
                                    {{ patient.genre === 'M' ? 'Masculin' : 'Féminin' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ patient.telephone }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" :class="getStatusClass(patient.status)">
                                    {{ patient.status || 'Actif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <button @click="viewPatient(patient)" class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button @click="F_editPatient(patient)" class="text-yellow-600 hover:text-yellow-900">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button @click="confirmDelete(patient)" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="patients.data.length === 0">
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                Aucun patient trouvé
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="bg-white px-6 py-3 flex items-center justify-between border-t border-gray-200">
                <div class="flex-1 flex justify-between sm:hidden">
                    <button @click="prevPage" :disabled="patients.current_page === 1" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Précédent
                    </button>
                    <button @click="nextPage" :disabled="patients.current_page === patients.last_page" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
                            <button @click="changePage(1)" :disabled="patients.current_page === 1" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Premier</span>
                                <i class="fas fa-angle-double-left"></i>
                            </button>
                            <button @click="prevPage" :disabled="patients.current_page === 1" class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Précédent</span>
                                <i class="fas fa-chevron-left"></i>
                            </button>

                            <template v-for="page in pagesRange" :key="page">
                                <button @click="changePage(page)" :class="{ 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600': page === patients.current_page }" class="relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                    {{ page }}
                                </button>
                            </template>

                            <button @click="nextPage" :disabled="patients.current_page === patients.last_page" class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Suivant</span>
                                <i class="fas fa-chevron-right"></i>
                            </button>
                            <button @click="changePage(patients.last_page)" :disabled="patients.current_page === patients.last_page" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Dernier</span>
                                <i class="fas fa-angle-double-right"></i>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <TransitionRoot appear :show="showDeleteModal" as="template">
        <Dialog as="div" class="relative z-50" @close="showDeleteModal = false">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black/25 backdrop-blur-sm" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                        <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 flex items-center">
                                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-red-100 mr-3">
                                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                                </div>
                                Confirmer la suppression
                            </DialogTitle>

                            <div class="mt-4">
                                <p class="text-sm text-gray-500">
                                    Êtes-vous sûr de vouloir supprimer le patient <span class="font-semibold">{{ patientToDelete?.prenom }} {{ patientToDelete?.nom }}</span> ? Cette action est irréversible.
                                </p>
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500" @click="showDeleteModal = false">
                                    Annuler
                                </button>
                                <button type="button" class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500" @click="deletePatient">
                                    Supprimer
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>

    <!-- Notification Toast -->
    <TransitionRoot appear :show="showNotification" as="template">
        <div class="fixed inset-0 flex items-end justify-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end z-50">
            <TransitionChild enter="transform ease-out duration-300 transition" enter-from="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" enter-to="translate-y-0 opacity-100 sm:translate-x-0" leave="transition ease-in duration-100" leave-from="opacity-100" leave-to="opacity-0" class="w-full max-w-sm">
                <div class="w-full h-20 border-l-5 bg-white rounded-xl shadow-lg pointer-events-auto" :class="{
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

    <!-- Add Patient Modal -->
    <TransitionRoot appear :show="showAddPatientModal" as="template">
        <Dialog as="div" class="relative z-50" @close="showAddPatientModal = false">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black/25 backdrop-blur-sm" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                        <DialogPanel class="w-full max-w-3xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 flex items-center">
                                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100 mr-3">
                                    <i class="fas fa-user-plus text-indigo-600"></i>
                                </div>
                                Nouveau Patient
                            </DialogTitle>

                            <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                                    <input type="text" v-model="newPatient.nom" id="nom" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3">
                                    <p v-if="errors.nom" class="mt-1 text-sm text-red-600">{{ errors.nom }}</p>
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
                                    <input type="text" v-model="newPatient.prenom" id="prenom" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3">
                                    <p v-if="errors.prenom" class="mt-1 text-sm text-red-600">{{ errors.prenom }}
                                    </p>
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="date_naissance" class="block text-sm font-medium text-gray-700">Date
                                        de
                                        Naissance</label>
                                    <input type="date" :value="formatDateForInput(newPatient.date_naissance)" @input="newPatient.date_naissance = $event.target.value" id="date_naissance" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3">
                                    <p v-if="errors.date_naissance" class="mt-1 text-sm text-red-600">{{
                                            errors.date_naissance }}</p>
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="genre" class="block text-sm font-medium text-gray-700">Genre</label>
                                    <select v-model="newPatient.genre" id="genre" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3">
                                        <option value="M">Masculin</option>
                                        <option value="F">Féminin</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" v-model="newPatient.email" id="email" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3">
                                    <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                                    <input type="tel" v-model="newPatient.telephone" id="telephone" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3">
                                    <p v-if="errors.telephone" class="mt-1 text-sm text-red-600">{{ errors.telephone
                                            }}</p>
                                </div>
                                <div class="sm:col-span-6">
                                    <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
                                    <input type="text" v-model="newPatient.adresse" id="adresse" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3">
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="npi" class="block text-sm font-medium text-gray-700">NPI</label>
                                    <input type="text" v-model="newPatient.npi" id="npi" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3">
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500" @click="showAddPatientModal = false">
                                    Annuler
                                </button>
                                <button type="button" :disabled="isSaving" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed" @click="savePatient">
                                    <span v-if="isSaving">
                                        <i class="fas fa-spinner fa-spin mr-2"></i> Enregistrement...
                                    </span>
                                    <span v-else>
                                        Enregistrer
                                    </span>
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>

    <!-- Edit Patient Modal -->
    <TransitionRoot appear :show="showEditPatientModal" as="template">
        <Dialog as="div" class="relative z-50" @close="showEditPatientModal = false">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black/25 backdrop-blur-sm" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                        <DialogPanel class="w-full max-w-3xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 flex items-center">
                                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100 mr-3">
                                    <i class="fas fa-user-edit text-indigo-600"></i>
                                </div>
                                Modifier Patient
                            </DialogTitle>

                            <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="edit-nom" class="block text-sm font-medium text-gray-700">Nom</label>
                                    <input type="text" v-model="editPatient.nom" id="edit-nom" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3">
                                    <p v-if="editErrors.nom" class="mt-1 text-sm text-red-600">{{ editErrors.nom }}
                                    </p>
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="edit-prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
                                    <input type="text" v-model="editPatient.prenom" id="edit-prenom" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3">
                                    <p v-if="editErrors.prenom" class="mt-1 text-sm text-red-600">
                                        {{ editErrors.prenom }}
                                    </p>
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="edit-date_naissance" class="block text-sm font-medium text-gray-700">Date de
                                        Naissance</label>
                                    <input type="date" v-model="editPatient.date_naissance" id="edit-date_naissance" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3">
                                    <p v-if="editErrors.date_naissance" class="mt-1 text-sm text-red-600">
                                        {{ editErrors.date_naissance }}</p>
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="edit-genre" class="block text-sm font-medium text-gray-700">Genre</label>
                                    <select v-model="editPatient.genre" id="edit-genre" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3">
                                        <option value="M">Masculin</option>
                                        <option value="F">Féminin</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-4">
                                    <label for="edit-email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" v-model="editPatient.email" id="edit-email" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3">
                                    <p v-if="editErrors.email" class="mt-1 text-sm text-red-600">
                                        {{ editErrors.email }}</p>
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="edit-telephone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                                    <input type="tel" v-model="editPatient.telephone" id="edit-telephone" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3">
                                    <p v-if="editErrors.telephone" class="mt-1 text-sm text-red-600">
                                        {{ editErrors.telephone }}</p>
                                </div>
                                <div class="sm:col-span-6">
                                    <label for="edit-adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
                                    <input type="text" v-model="editPatient.adresse" id="edit-adresse" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3">
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="edit-npi" class="block text-sm font-medium text-gray-700">NPI</label>
                                    <input type="text" v-model="editPatient.npi" id="edit-npi" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3">
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500" @click="showEditPatientModal = false">
                                    Annuler
                                </button>
                                <button type="button" :disabled="isUpdating" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed" @click="updatePatient">
                                    <span v-if="isUpdating">
                                        <i class="fas fa-spinner fa-spin mr-2"></i> Mise à jour...
                                    </span>
                                    <span v-else>
                                        Mettre à jour
                                    </span>
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>

    <!-- Patient View Modal -->
    <TransitionRoot appear :show="!!selectedPatient" as="template">
        <Dialog as="div" class="relative z-50" @close="selectedPatient = null">
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
                                Infos Patient: {{ selectedPatient?.prenom }} {{ selectedPatient?.nom }}
                            </DialogTitle>

                            <div class="mt-6">
                                <div class="border-b border-gray-200">
                                    <nav class="-mb-px flex space-x-8">
                                        <button @click="activeTab = 'info'" :class="activeTab === 'info' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                            Informations
                                        </button>
                                        <button @click="activeTab = 'history'" :class="activeTab === 'history' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                            Dossier Médical
                                        </button>
                                    </nav>
                                </div>

                                <!-- Info Tab -->
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
                                        <label class="block text-sm font-medium text-gray-700">Date de
                                            Naissance</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ formatDate(selectedPatient?.date_naissance) }}
                                        </p>
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Genre</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ selectedPatient?.genre === 'M' ? 'Masculin' : 'Féminin' }}</p>
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
                                        <p class="mt-1 text-sm text-gray-900">{{ selectedPatient?.npi || 'Non renseigné' }}
                                        </p>
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Statut</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ selectedPatient?.status || 'Actif'
                                                }}</p>
                                    </div>
                                </div>

                                <!-- History Tab -->
                                <div v-if="activeTab === 'history'" class="mt-4">
                                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                Dossier Médical
                                            </h3>
                                            <button v-if="!medicalRecord" @click="initDossierCreationFromView" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                <i class="fas fa-plus mr-1"></i> Créer un dossier
                                            </button>
                                        </div>

                                        <div class="px-4 py-5 sm:p-6" v-if="medicalRecord">
                                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                                <div class="sm:col-span-3">
                                                    <label class="block text-sm font-medium text-gray-700">Groupe Sanguin</label>
                                                    <p class="mt-1 text-sm text-gray-900">
                                                        {{ medicalRecord.groupe_sanguin || 'Non renseigné' }}
                                                    </p>
                                                </div>
                                                <div class="sm:col-span-3">
                                                    <label class="block text-sm font-medium text-gray-700">Date de création</label>
                                                    <p class="mt-1 text-sm text-gray-900">
                                                        {{ formatDate(medicalRecord.created_at) }}
                                                    </p>
                                                </div>
                                                <div class="sm:col-span-6">
                                                    <label class="block text-sm font-medium text-gray-700">Allergies</label>
                                                    <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">
                                                        {{ medicalRecord.allergies || 'Aucune allergie connue' }}
                                                    </p>
                                                </div>
                                                <div class="sm:col-span-6">
                                                    <label class="block text-sm font-medium text-gray-700">Historique Médical</label>
                                                    <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">
                                                        {{ medicalRecord.historique_medical || 'Aucun historique médical enregistré' }}
                                                    </p>
                                                </div>
                                                <div class="sm:col-span-6">
                                                    <label class="block text-sm font-medium text-gray-700">Antécédents Médicaux</label>
                                                    <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">
                                                        {{ medicalRecord.antecedents_medicaux || 'Aucun antécédent medical enregistré' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <!-- Dans le template, après la partie qui affiche les infos du dossier médical -->
                                            <div class="mt-6 flex justify-end" v-if="medicalRecord">
                                                <button @click="initConsultation" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    <i class="fas fa-stethoscope mr-2 -ml-1"></i>
                                                    Nouvelle Consultation
                                                </button>
                                            </div>
                                        </div>

                                        <div class="px-4 py-5 sm:p-6 text-center" v-else>
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun dossier médical</h3>
                                            <p class="mt-1 text-sm text-gray-500">Ce patient n'a pas encore de dossier médical.</p>
                                            <div class="mt-6">
                                                <button @click="initDossierCreationFromView" type="button" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    <i class="fas fa-file-medical mr-2 -ml-1"></i>
                                                    Créer un dossier médical
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="button" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500" @click="selectedPatient = null">
                                    Fermer
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>

    <!-- Confirmation pour créer un dossier médical -->
    <TransitionRoot appear :show="showConfirmCreateDossierModal" as="template">
        <Dialog as="div" class="relative z-50" @close="showConfirmCreateDossierModal = false">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black/25 backdrop-blur-sm" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                        <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                            <!-- Contenu du modal -->
                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 flex items-center">
                                <!-- Titre -->
                            </DialogTitle>

                            <div class="mt-4">
                                <p class="text-sm text-gray-500">
                                    Voulez-vous créer un dossier médical pour ce patient maintenant ?
                                </p>
                            </div>

                            <!-- Boutons - éléments focusables -->
                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" class="..." @click="showConfirmCreateDossierModal = false">
                                    Plus tard
                                </button>
                                <button type="button" class="..." @click="initDossierCreation">
                                    Créer maintenant
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>

    <!-- Modal de création du dossier médical -->
    <TransitionRoot appear :show="showCreateDossierModal" as="template">
        <Dialog as="div" class="relative z-50" @close="showCreateDossierModal = false">
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
                                Nouveau Dossier Médical
                            </DialogTitle>

                            <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="groupe_sanguin" class="block text-sm font-medium text-gray-700">Groupe Sanguin</label>
                                    <select v-model="dossierMedical.groupe_sanguin" id="groupe_sanguin" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3" required>
                                        <option value="">Sélectionner</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                    </select>
                                    <p v-if="dossierErrors.groupe_sanguin" class="mt-1 text-sm text-red-600">{{ dossierErrors.groupe_sanguin }}</p>
                                </div>

                                <div class="sm:col-span-6">
                                    <label for="allergies" class="block text-sm font-medium text-gray-700">Allergies</label>
                                    <textarea v-model="dossierMedical.allergies" id="allergies" rows="2" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3"></textarea>
                                    <p v-if="dossierErrors.allergies" class="mt-1 text-sm text-red-600">{{ dossierErrors.allergies }}</p>
                                </div>

                                <!-- <div class="sm:col-span-6">
                                    <label for="historique_medical" class="block text-sm font-medium text-gray-700">Historique Médical</label>
                                    <textarea v-model="dossierMedical.historique_medical" id="historique_medical" rows="4" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3"></textarea>
                                    <p v-if="dossierErrors.historique_medical" class="mt-1 text-sm text-red-600">{{ dossierErrors.historique_medical }}</p>
                                </div> -->

                                <div class="sm:col-span-6">
                                    <label for="antecedents_medicaux" class="block text-sm font-medium text-gray-700">Antécédents Médicaux</label>
                                    <textarea v-model="dossierMedical.antecedents_medicaux" id="antecedents_medicaux" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3"></textarea>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500" @click="showCreateDossierModal = false">
                                    Annuler
                                </button>
                                <button type="button" :disabled="isCreatingDossier" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed" @click="createDossierMedical">
                                    <span v-if="isCreatingDossier">
                                        <i class="fas fa-spinner fa-spin mr-2"></i> Création...
                                    </span>
                                    <span v-else>
                                        Créer le dossier
                                    </span>
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>

    <!-- Consultation Modal -->
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
import {
    Head,
    router,
    usePage
} from '@inertiajs/vue3'
import {
    ref,
    computed,
    watch,
    onMounted
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
    patients: Object,
    filters: Object,
    medecinsLibres: Object
})

// State
const showAddPatientModal = ref(false)
const selectedPatient = ref(null)
const medicalRecord = ref(null)
const activeTab = ref('info')
const searchQuery = ref(props.filters.search || '')
const genreFilter = ref(props.filters.genre || '') // Changé de statusFilter à genreFilter
const isSaving = ref(false)
const errors = ref({})
const showNotification = ref(false)
const notificationMessage = ref('')
const notificationType = ref('success')
const showDeleteModal = ref(false)
const patientToDelete = ref(null)
const showCreateDossierModal = ref(false)
const showConfirmCreateDossierModal = ref(false)
const newlyCreatedPatientId = ref(null)
const dossierMedical = ref({
    patient_id: null,
    groupe_sanguin: '',
    allergies: '',
    historique_medical: '',
    antecedents_medicaux: ''
})
const dossierErrors = ref({})
const isCreatingDossier = ref(false)
const showEditPatientModal = ref(false)
const editPatient = ref({
    id: null,
    nom: '',
    prenom: '',
    date_naissance: '',
    genre: 'M',
    email: '',
    telephone: '',
    adresse: '',
    npi: ''
})
const editErrors = ref({})
const isUpdating = ref(false)

// Notification handling
const showToast = (message, type) => {
    notificationMessage.value = message
    notificationType.value = type
    showNotification.value = true

    // Auto-hide after 5 seconds
    setTimeout(() => {
        showNotification.value = false
    }, 5000)
}

// Watch for flash messages
watch(() => usePage().props.flash, (flash) => {
    if (flash.success) {
        showToast(flash.success, 'success')
    } else if (flash.error) {
        showToast(flash.error, 'error')
    }
}, {
    deep: true
})

// Edit patient
function F_editPatient(patient) {
    showEditPatientModal.value = true
    editPatient.value = {
        id: patient.id,
        nom: patient.nom,
        prenom: patient.prenom,
        date_naissance: patient.date_naissance,
        genre: patient.genre,
        email: patient.email,
        telephone: patient.telephone,
        adresse: patient.adresse,
        npi: patient.npi
    }
}

// Update patient
function updatePatient() {
    isUpdating.value = true
    editErrors.value = {}

    router.put(route('Secretaire.UpdateP', {
        id: editPatient.value.id
    }), editPatient.value, {
        preserveScroll: true,
        onSuccess: () => {
            showEditPatientModal.value = false
            fetchPatients()
        },
        onError: (errors) => {
            editErrors.value = errors
        },
        onFinish: () => {
            isUpdating.value = false
        }
    })
}

// Delete patient
function confirmDelete(patient) {
    patientToDelete.value = patient
    showDeleteModal.value = true
}

function deletePatient() {
    router.delete(route('Secretaire.DeleteP', {
        id: patientToDelete.value.id
    }), {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteModal.value = false
            fetchPatients()
        }
    })
}

// New patient form
const newPatient = ref({
    nom: '',
    prenom: '',
    date_naissance: '',
    genre: 'M',
    email: '',
    telephone: '',
    adresse: '',
    npi: '',
    status: 'actif'
})

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

function getStatusClass(status) {
    switch (status) {
        case 'actif':
            return 'bg-green-100 text-green-800'
        case 'inactif':
            return 'bg-red-100 text-red-800'
        default:
            return 'bg-gray-100 text-gray-800'
    }
}

function formatDateForInput(dateString) {
    if (!dateString) return '';

    // Si la date est déjà au format YYYY-MM-DD
    if (/^\d{4}-\d{2}-\d{2}$/.test(dateString)) {
        return dateString;
    }

    // Pour les dates ISO
    try {
        const date = new Date(dateString);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    } catch (e) {
        console.error("Erreur de format de date:", e);
        return '';
    }
}

function savePatient() {
    isSaving.value = true;
    errors.value = {};

    const patientData = {
        ...newPatient.value,
        date_naissance: formatDateForInput(newPatient.value.date_naissance)
    };

    router.post(route('Secretaire.StoreP'), patientData, {
        preserveScroll: true, // Conserver la position de défilement
        preserveState: true, // Conserver l'état du composant Vue
        onSuccess: (response) => {
            showAddPatientModal.value = false;

            // La réponse flash est toujours accessible via usePage().props.flash
            // Assurez-vous que le patient est bien envoyé dans la session flash côté Laravel
            const patient = usePage().props.flash.patient;

            if (!patient || !patient.id) {
                console.error("Patient non reçu dans la réponse flash", response);
                showToast("Patient créé avec succès", 'success');
                fetchPatients(); // Recharger les patients même si l'ID n'est pas directement récupéré ici
            } else {
                newlyCreatedPatientId.value = patient.id;
                showConfirmCreateDossierModal.value = true;
                showToast("Patient créé avec succès !", 'success'); // Déplacé ici pour une meilleure logique
            }

            resetPatientForm();
            fetchPatients(); // Recharger les patients pour voir le nouveau patient ajouté
        },
        onError: (err) => {
            errors.value = err;
            showToast("Erreur lors de la création du patient", 'error');
        },
        onFinish: () => {
            isSaving.value = false;
        }
    });
}

function resetPatientForm() {
    newPatient.value = {
        nom: '',
        prenom: '',
        date_naissance: '',
        genre: 'M',
        email: '',
        telephone: '',
        adresse: '',
        npi: '',
        status: 'actif'
    }
}

function initDossierCreation() {
    showConfirmCreateDossierModal.value = false
    dossierMedical.value = {
        patient_id: newlyCreatedPatientId.value,
        groupe_sanguin: '',
        allergies: '',
        historique_medical: '',
        antecedents_medicaux: ''
    }
    showCreateDossierModal.value = true
}

function createDossierMedical() {
    isCreatingDossier.value = true
    dossierErrors.value = {}

    router.post(route('dossiers-medicaux.store'), dossierMedical.value, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            showCreateDossierModal.value = false
            showToast('Dossier médical créé avec succès', 'success')

            // Recharger les données des patients pour avoir le nouveau dossier
            fetchPatients()

            // Si on visualise le patient, mettre à jour ses données
            if (selectedPatient.value && selectedPatient.value.id === dossierMedical.value.patient_id) {
                selectedPatient.value.dossier_medical = dossierMedical.value
                medicalRecord.value = dossierMedical.value
            }
        },
        onError: (errors) => {
            if (errors.dossier) {
                dossierErrors.value = errors.dossier
            } else {
                dossierErrors.value = errors
            }
        },
        onFinish: () => {
            isCreatingDossier.value = false
        }
    })
}

function initDossierCreationFromView() {
    dossierMedical.value = {
        patient_id: selectedPatient.value.id,
        groupe_sanguin: '',
        allergies: '',
        historique_medical: '',
        antecedents_medicaux: ''
    }
    showCreateDossierModal.value = true
}

function fetchPatients(page = 1) {
    router.get(route('Secretaire.CreateP'), {
        search: searchQuery.value,
        genre: genreFilter.value, // Changé de status à genre
        page: page
    }, {
        preserveState: true,
        replace: true
    })
}

function viewPatient(patient) {
    selectedPatient.value = patient
    activeTab.value = 'info'
    // Utilisez directement le dossier medical chargé avec le patient
    medicalRecord.value = patient.dossier_medical || null
}

function searchPatients() {
    clearTimeout(searchPatients.debounce)
    searchPatients.debounce = setTimeout(() => {
        fetchPatients()
    }, 500)
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

watch(genreFilter, () => {
    fetchPatients()
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


function initConsultation() {
    if (!selectedPatient.value || !medicalRecord.value) return

    consultation.value = {
        dossier_medical_id: medicalRecord.value.id,
        medecin_id: null, // À remplir avec le médecin connecté ou à sélectionner
        date_consultation: new Date().toISOString().slice(0, 16),
        motif: '',
        poids: '',
        taille: '',
        temperature: '',
        tension_arterielle: '',
        status: 'en cours'
    }

    showConsultationModal.value = true
}

function createConsultation() {
    isCreatingConsultation.value = true
    consultationErrors.value = {}

    router.post(route('consultations.store'), consultation.value, {
        preserveScroll: true,
        onSuccess: () => {
            showConsultationModal.value = false
            // showToast('Consultation créée avec succès', 'success')

            // Recharger les données si nécessaire
            if (selectedPatient.value) {
                // Vous pourriez vouloir recharger les données du patient ici
                // pour afficher la nouvelle consultation dans son historique
            }
        },
        onError: (errors) => {
            consultationErrors.value = errors
        },
        onFinish: () => {
            isCreatingConsultation.value = false
        }
    })
}
</script>
