<template>
  <Head title="Gestion des Patients" />

  <div class="container mx-auto px-4 py-8">
    <!-- Titre et bouton d'ajout -->
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Gestion des Patients</h1>
      <Link
        href="/patients/create"
        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
      >
        <i class="fas fa-plus mr-2"></i>Nouveau Patient
      </Link>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Champ de recherche -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
          <input
            type="text"
            v-model="filters.search"
            placeholder="Nom, Email, Téléphone..."
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
          />
        </div>

        <!-- Filtre par genre -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Genre</label>
          <select
            v-model="filters.genre"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="">Tous</option>
            <option value="M">Masculin</option>
            <option value="F">Féminin</option>
            <option value="Autre">Autre</option>
          </select>
        </div>

        <!-- Bouton de réinitialisation -->
        <div class="flex items-end">
          <button
            @click="resetFilters"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors"
          >
            <i class="fas fa-redo mr-2"></i>Réinitialiser
          </button>
        </div>
      </div>
    </div>

    <!-- Tableau des patients -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Nom Complet
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Genre
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Téléphone
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Email
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                NPI
              </th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="patient in patients.data" :key="patient.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                      <i class="fas fa-user text-blue-600"></i>
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ patient.prenom }} {{ patient.nom }}</div>
                    <div class="text-sm text-gray-500">{{ patient.date_naissance }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                      :class="{
                        'bg-blue-100 text-blue-800': patient.genre === 'M',
                        'bg-pink-100 text-pink-800': patient.genre === 'F',
                        'bg-purple-100 text-purple-800': patient.genre === 'Autre'
                      }">
                  {{ patient.genre }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ patient.telephone }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ patient.email }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ patient.npi }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button
                  @click="openModal(patient)"
                  class="text-blue-600 hover:text-blue-900 mr-3"
                >
                  <i class="fas fa-eye"></i> Voir
                </button>
                <Link
                  :href="`/patients/${patient.id}/edit`"
                  class="text-green-600 hover:text-green-900 mr-3"
                >
                  <i class="fas fa-edit"></i> Modifier
                </Link>
                <button
                  @click="confirmDelete(patient)"
                  class="text-red-600 hover:text-red-900"
                >
                  <i class="fas fa-trash"></i> Supprimer
                </button>
              </td>
            </tr>
            <tr v-if="patients.data.length === 0">
              <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                Aucun patient trouvé
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="bg-white px-6 py-3 border-t border-gray-200">
        <Pagination :links="patients.links" />
      </div>
    </div>

    <!-- Modal de visualisation -->
    <Modal :show="showModal" @close="closeModal" max-width="4xl">
      <div class="p-6">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-800">
            Détails du Patient
          </h2>
          <button @click="closeModal" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <!-- Onglets -->
        <div class="border-b border-gray-200 mb-6">
          <nav class="-mb-px flex space-x-8">
            <button
              @click="activeTab = 'info'"
              :class="{
                'border-blue-500 text-blue-600': activeTab === 'info',
                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'info'
              }"
              class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
            >
              Informations Patient
            </button>
            <button
              @click="activeTab = 'dossier'"
              :class="{
                'border-blue-500 text-blue-600': activeTab === 'dossier',
                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'dossier'
              }"
              class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
            >
              Dossier Médical
            </button>
          </nav>
        </div>

        <!-- Contenu des onglets -->
        <div v-if="activeTab === 'info'">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <h3 class="text-lg font-medium text-gray-900 mb-4">Informations Personnelles</h3>
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-500">Nom Complet</label>
                  <p class="mt-1 text-sm text-gray-900">{{ currentPatient.prenom }} {{ currentPatient.nom }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Date de Naissance</label>
                  <p class="mt-1 text-sm text-gray-900">{{ currentPatient.date_naissance }} ({{ calculateAge(currentPatient.date_naissance) }} ans)</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Genre</label>
                  <p class="mt-1 text-sm text-gray-900">{{ currentPatient.genre }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">NPI</label>
                  <p class="mt-1 text-sm text-gray-900">{{ currentPatient.npi }}</p>
                </div>
              </div>
            </div>

            <div>
              <h3 class="text-lg font-medium text-gray-900 mb-4">Coordonnées</h3>
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-500">Adresse</label>
                  <p class="mt-1 text-sm text-gray-900">{{ currentPatient.adresse }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Téléphone</label>
                  <p class="mt-1 text-sm text-gray-900">{{ currentPatient.telephone }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Email</label>
                  <p class="mt-1 text-sm text-gray-900">{{ currentPatient.email }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-if="activeTab === 'dossier'">
          <div v-if="currentPatient.dossier_medical">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations Médicales</h3>
                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-500">Groupe Sanguin</label>
                    <p class="mt-1 text-sm text-gray-900">{{ currentPatient.dossier_medical.groupe_sanguin || 'Non renseigné' }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-500">Allergies</label>
                    <p class="mt-1 text-sm text-gray-900">{{ currentPatient.dossier_medical.allergies || 'Aucune connue' }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-500">Antécédents Médicaux</label>
                    <p class="mt-1 text-sm text-gray-900">{{ currentPatient.dossier_medical.antecedents_medicaux || 'Aucun' }}</p>
                  </div>
                </div>
              </div>

              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Traitements en Cours</h3>
                <div v-if="currentPatient.dossier_medical.traitements && currentPatient.dossier_medical.traitements.length > 0">
                  <div v-for="traitement in currentPatient.dossier_medical.traitements" :key="traitement.id" class="mb-3 p-3 bg-gray-50 rounded-lg">
                    <p class="font-medium">{{ traitement.nom }}</p>
                    <p class="text-sm text-gray-600">{{ traitement.posologie }}</p>
                    <p class="text-xs text-gray-500">Depuis {{ traitement.date_debut }}</p>
                  </div>
                </div>
                <p v-else class="text-sm text-gray-500">Aucun traitement en cours</p>
              </div>
            </div>

            <div class="mt-6">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Dernières Consultations</h3>
              <div v-if="currentPatient.dossier_medical.consultations && currentPatient.dossier_medical.consultations.length > 0">
                <div v-for="consultation in currentPatient.dossier_medical.consultations.slice(0, 3)" :key="consultation.id" class="mb-3 p-3 border border-gray-200 rounded-lg">
                  <div class="flex justify-between">
                    <p class="font-medium">{{ consultation.motif }}</p>
                    <p class="text-sm text-gray-500">{{ consultation.date }}</p>
                  </div>
                  <p class="text-sm text-gray-600 mt-1">{{ consultation.diagnostic }}</p>
                </div>
              </div>
              <p v-else class="text-sm text-gray-500">Aucune consultation enregistrée</p>
            </div>
          </div>
          <div v-else class="text-center py-8 text-gray-500">
            <i class="fas fa-file-medical text-4xl mb-3 text-gray-300"></i>
            <p>Aucun dossier médical disponible pour ce patient</p>
          </div>
        </div>
      </div>
    </Modal>
  </div>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'

const props = defineProps({
  patients: Object,
  filters: Object
})

const showModal = ref(false)
const currentPatient = ref({})
const activeTab = ref('info')

const filters = ref({
  search: props.filters.search || '',
  genre: props.filters.genre || ''
})

// Fonction pour ouvrir le modal avec les données du patient
const openModal = (patient) => {
  currentPatient.value = patient
  activeTab.value = 'info'
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
}

// Fonction pour calculer l'âge à partir de la date de naissance
const calculateAge = (birthdate) => {
  if (!birthdate) return 'N/A'
  const today = new Date()
  const birthDate = new Date(birthdate)
  let age = today.getFullYear() - birthDate.getFullYear()
  const monthDiff = today.getMonth() - birthDate.getMonth()

  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
    age--
  }

  return age
}

// Fonction pour réinitialiser les filtres
const resetFilters = () => {
  filters.value = {
    search: '',
    genre: ''
  }
}

// Fonction de confirmation de suppression
const confirmDelete = (patient) => {
  if (confirm(`Êtes-vous sûr de vouloir supprimer ${patient.prenom} ${patient.nom} ?`)) {
    router.delete(`/patients/${patient.id}`)
  }
}

// Watch les filtres pour déclencher la recherche
watch(filters, (value) => {
  router.get('/patients', {
    search: value.search,
    genre: value.genre
  }, {
    preserveState: true,
    replace: true,
    preserveScroll: true
  })
})
</script>
