<template>

    <Head title="Gestion des Patients" />

    <div class="px-6 py-6">
        <div
            class="min-h-[calc(100vh-8rem)] bg-gradient-to-br from-indigo-50 to-white rounded-xl shadow-md border border-gray-200 p-10">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-indigo-800">Gestion des Patients</h1>
                    <p class="text-gray-600">Gérez les dossiers médicaux des patients</p>
                </div>
                <button @click="showAddPatientModal = true"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-md flex items-center">
                    <i class="fas fa-user-plus mr-2"></i> Nouveau Patient
                </button>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-3">
                        <div class="relative">
                            <input type="text" v-model="searchQuery" placeholder="Rechercher par nom, prénom, ID..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
                            <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <div>
                        <select
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
                            <option>Tous les statuts</option>
                            <option>Actif</option>
                            <option>Inactif</option>
                            <option>En attente</option>
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
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nom Complet</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date Naiss.</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Genre</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Téléphone</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut</th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="patient in filteredPatients" :key="patient.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ patient.id
                                    }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div
                                                class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <i class="fas fa-user text-indigo-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ patient.lastName }} {{
                                                patient.firstName }}</div>
                                            <div class="text-sm text-gray-500">{{ patient.email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{
                                    formatDate(patient.birthDate) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        :class="patient.gender === 'M' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800'">
                                        {{ patient.gender === 'M' ? 'Masculin' : 'Féminin' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ patient.phone }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        :class="getStatusClass(patient.status)">
                                        {{ patient.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <button @click="viewPatient(patient.id)"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button @click="editPatient(patient.id)"
                                            class="text-yellow-600 hover:text-yellow-900">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button @click="confirmDelete(patient.id)"
                                            class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="bg-white px-6 py-3 flex items-center justify-between border-t border-gray-200">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <button
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Précédent
                        </button>
                        <button
                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Suivant
                        </button>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Affichage de <span class="font-medium">1</span> à <span class="font-medium">10</span>
                                sur <span class="font-medium">120</span> patients
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                aria-label="Pagination">
                                <button
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Précédent</span>
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button aria-current="page"
                                    class="z-10 bg-indigo-50 border-indigo-500 text-indigo-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                    1
                                </button>
                                <button
                                    class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                    2
                                </button>
                                <button
                                    class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                    3
                                </button>
                                <button
                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Suivant</span>
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Patient Modal -->
        <TransitionRoot appear :show="showAddPatientModal" as="template">
            <Dialog as="div" class="relative z-50" @close="showAddPatientModal = false">
                <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0"
                    enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                    <div class="fixed inset-0 bg-black/25 backdrop-blur-sm" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4 text-center">
                        <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100" leave="duration-200 ease-in"
                            leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                            <DialogPanel
                                class="w-full max-w-3xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                                <DialogTitle as="h3"
                                    class="text-lg font-medium leading-6 text-gray-900 flex items-center">
                                    <div
                                        class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100 mr-3">
                                        <i class="fas fa-user-plus text-indigo-600"></i>
                                    </div>
                                    Nouveau Patient
                                </DialogTitle>

                                <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                    <div class="sm:col-span-3">
                                        <label for="lastName"
                                            class="block text-sm font-medium text-gray-700">Nom</label>
                                        <input type="text" v-model="newPatient.lastName" id="lastName"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label for="firstName"
                                            class="block text-sm font-medium text-gray-700">Prénom</label>
                                        <input type="text" v-model="newPatient.firstName" id="firstName"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label for="birthDate" class="block text-sm font-medium text-gray-700">Date de
                                            Naissance</label>
                                        <input type="date" v-model="newPatient.birthDate" id="birthDate"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label for="gender"
                                            class="block text-sm font-medium text-gray-700">Genre</label>
                                        <select v-model="newPatient.gender" id="gender"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            <option value="M">Masculin</option>
                                            <option value="F">Féminin</option>
                                        </select>
                                    </div>
                                    <div class="sm:col-span-4">
                                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                        <input type="email" v-model="newPatient.email" id="email"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="phone"
                                            class="block text-sm font-medium text-gray-700">Téléphone</label>
                                        <input type="tel" v-model="newPatient.phone" id="phone"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <div class="sm:col-span-6">
                                        <label for="address"
                                            class="block text-sm font-medium text-gray-700">Adresse</label>
                                        <input type="text" v-model="newPatient.address" id="address"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label for="city" class="block text-sm font-medium text-gray-700">Ville</label>
                                        <input type="text" v-model="newPatient.city" id="city"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label for="zipCode" class="block text-sm font-medium text-gray-700">Code
                                            Postal</label>
                                        <input type="text" v-model="newPatient.zipCode" id="zipCode"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end space-x-3">
                                    <button type="button"
                                        class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        @click="showAddPatientModal = false">
                                        Annuler
                                    </button>
                                    <button type="button"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        @click="savePatient">
                                        Enregistrer
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
                <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0"
                    enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                    <div class="fixed inset-0 bg-black/25 backdrop-blur-sm" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4 text-center">
                        <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100" leave="duration-200 ease-in"
                            leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                            <DialogPanel
                                class="w-full max-w-4xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                                <DialogTitle as="h3"
                                    class="text-lg font-medium leading-6 text-gray-900 flex items-center">
                                    <div
                                        class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100 mr-3">
                                        <i class="fas fa-user text-indigo-600"></i>
                                    </div>
                                    Dossier Patient: {{ selectedPatient?.lastName }} {{ selectedPatient?.firstName }}
                                </DialogTitle>

                                <div class="mt-6">
                                    <div class="border-b border-gray-200">
                                        <nav class="-mb-px flex space-x-8">
                                            <button @click="activeTab = 'info'"
                                                :class="activeTab === 'info' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                                Informations
                                            </button>
                                            <button @click="activeTab = 'history'"
                                                :class="activeTab === 'history' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                                Historique Médical
                                            </button>
                                            <button @click="activeTab = 'consultations'"
                                                :class="activeTab === 'consultations' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                                Consultations
                                            </button>
                                            <button @click="activeTab = 'documents'"
                                                :class="activeTab === 'documents' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                                Documents
                                            </button>
                                        </nav>
                                    </div>

                                    <!-- Info Tab -->
                                    <div v-if="activeTab === 'info'"
                                        class="mt-4 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                        <div class="sm:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700">Nom</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ selectedPatient?.lastName }}</p>
                                        </div>
                                        <div class="sm:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700">Prénom</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ selectedPatient?.firstName }}</p>
                                        </div>
                                        <div class="sm:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700">Date de
                                                Naissance</label>
                                            <p class="mt-1 text-sm text-gray-900">{{
                                                formatDate(selectedPatient?.birthDate) }}</p>
                                        </div>
                                        <div class="sm:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700">Genre</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ selectedPatient?.gender === 'M' ?
                                                'Masculin' : 'Féminin' }}</p>
                                        </div>
                                        <div class="sm:col-span-4">
                                            <label class="block text-sm font-medium text-gray-700">Email</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ selectedPatient?.email }}</p>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ selectedPatient?.phone }}</p>
                                        </div>
                                        <div class="sm:col-span-6">
                                            <label class="block text-sm font-medium text-gray-700">Adresse</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ selectedPatient?.address }}</p>
                                        </div>
                                        <div class="sm:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700">Ville</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ selectedPatient?.city }}</p>
                                        </div>
                                        <div class="sm:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700">Code Postal</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ selectedPatient?.zipCode }}</p>
                                        </div>
                                    </div>

                                    <!-- History Tab -->
                                    <div v-if="activeTab === 'history'" class="mt-4">
                                        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                    Antécédents Médicaux
                                                </h3>
                                            </div>
                                            <div class="px-4 py-5 sm:p-6">
                                                <p class="text-sm text-gray-500">Aucun antécédent médical enregistré.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Consultations Tab -->
                                    <div v-if="activeTab === 'consultations'" class="mt-4">
                                        <div
                                            class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                                            <table class="min-w-full divide-y divide-gray-300">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col"
                                                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                            Date</th>
                                                        <th scope="col"
                                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                            Médecin</th>
                                                        <th scope="col"
                                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                            Motif</th>
                                                        <th scope="col"
                                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                            Diagnostic</th>
                                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                            <span class="sr-only">Voir</span>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200 bg-white">
                                                    <tr v-for="consultation in consultations" :key="consultation.id">
                                                        <td
                                                            class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                            {{ formatDate(consultation.date) }}</td>
                                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                            Dr. {{ consultation.doctor }}</td>
                                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{
                                                            consultation.reason }}</td>
                                                        <td class="px-3 py-4 text-sm text-gray-500">{{
                                                            consultation.diagnosis }}</td>
                                                        <td
                                                            class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                            <button
                                                                class="text-indigo-600 hover:text-indigo-900">Voir</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Documents Tab -->
                                    <div v-if="activeTab === 'documents'" class="mt-4">
                                        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                    Documents Associés
                                                </h3>
                                            </div>
                                            <div class="px-4 py-5 sm:p-6">
                                                <p class="text-sm text-gray-500">Aucun document associé.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <button type="button"
                                        class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        @click="selectedPatient = null">
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
import { Head } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue'

// State
const showAddPatientModal = ref(false)
const selectedPatient = ref(null)
const activeTab = ref('info')
const searchQuery = ref('')

// Sample data - replace with your actual data
const patients = ref([
    {
        id: 1,
        lastName: 'Dupont',
        firstName: 'Jean',
        birthDate: '1985-05-15',
        gender: 'M',
        email: 'jean.dupont@example.com',
        phone: '0612345678',
        address: '12 Rue de la Paix',
        city: 'Paris',
        zipCode: '75001',
        status: 'Actif'
    },
    {
        id: 2,
        lastName: 'Martin',
        firstName: 'Sophie',
        birthDate: '1990-08-22',
        gender: 'F',
        email: 'sophie.martin@example.com',
        phone: '0698765432',
        address: '24 Avenue des Champs',
        city: 'Lyon',
        zipCode: '69002',
        status: 'Actif'
    },
    {
        id: 3,
        lastName: 'Bernard',
        firstName: 'Pierre',
        birthDate: '1978-11-30',
        gender: 'M',
        email: 'pierre.bernard@example.com',
        phone: '0678912345',
        address: '8 Boulevard Voltaire',
        city: 'Marseille',
        zipCode: '13001',
        status: 'Inactif'
    }
])

const consultations = ref([
    {
        id: 1,
        date: '2023-05-10',
        doctor: 'Smith',
        reason: 'Consultation générale',
        diagnosis: 'Rhume'
    },
    {
        id: 2,
        date: '2023-03-15',
        doctor: 'Johnson',
        reason: 'Suivi annuel',
        diagnosis: 'Bon état de santé'
    }
])

const newPatient = ref({
    lastName: '',
    firstName: '',
    birthDate: '',
    gender: 'M',
    email: '',
    phone: '',
    address: '',
    city: '',
    zipCode: ''
})

// Computed
const filteredPatients = computed(() => {
    if (!searchQuery.value) return patients.value
    return patients.value.filter(patient => {
        return (
            patient.lastName.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            patient.firstName.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            patient.phone.includes(searchQuery.value) ||
            patient.id.toString().includes(searchQuery.value)
        )
    })
})

// Methods
function formatDate(dateString) {
    if (!dateString) return ''
    const date = new Date(dateString)
    return date.toLocaleDateString('fr-FR')
}

function getStatusClass(status) {
    switch (status) {
        case 'Actif': return 'bg-green-100 text-green-800'
        case 'Inactif': return 'bg-red-100 text-red-800'
        default: return 'bg-gray-100 text-gray-800'
    }
}

function viewPatient(id) {
    selectedPatient.value = patients.value.find(p => p.id === id)
    activeTab.value = 'info'
}

function editPatient(id) {
    // Implementation for editing a patient
    console.log('Edit patient', id)
}

function confirmDelete(id) {
    // Implementation for deleting a patient
    if (confirm('Êtes-vous sûr de vouloir supprimer ce patient ?')) {
        patients.value = patients.value.filter(p => p.id !== id)
    }
}

function savePatient() {
    // Generate a new ID for the patient
    const newId = Math.max(...patients.value.map(p => p.id), 0) + 1

    // Add the new patient to the list
    patients.value.push({
        id: newId,
        ...newPatient.value,
        status: 'Actif'
    })

    // Reset the form and close the modal
    newPatient.value = {
        lastName: '',
        firstName: '',
        birthDate: '',
        gender: 'M',
        email: '',
        phone: '',
        address: '',
        city: '',
        zipCode: ''
    }
    showAddPatientModal.value = false
}
</script>
