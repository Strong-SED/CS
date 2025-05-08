<template>
    <div class="min-h-screen bg-gradient-to-r from-indigo-200 to-white flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
      <!-- En-tête avec logo -->
      <div class="sm:mx-auto sm:w-full sm:max-w-md text-center animous">
        <div class="flex justify-center">
          <svg class="w-20 h-20 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
          </svg>
        </div>
        <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Création d'un Centre de Santé</h2>
      </div>

      <!-- Messages d'erreur -->
      <div v-if="hasErrors" class="sm:mx-auto sm:w-full sm:max-w-md animous">
        <div class="text-red-800 bg-red-100 p-4 rounded-lg mb-4 transition-all duration-500">
          <p v-for="error in Object.values(form.errors)" :key="error" class="text-sm">
            {{ error }}
          </p>
        </div>
      </div>

      <!-- Carte du formulaire -->
      <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md animous">
        <div class="bg-white py-8 px-6 shadow rounded-lg sm:px-10 border border-gray-200">
          <form class="space-y-6" @submit.prevent="submitForm">
            <!-- Champ Nom -->
            <div>
              <label for="nom" class="block text-sm font-medium text-gray-700">Nom du centre</label>
              <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z" clip-rule="evenodd"></path>
                  </svg>
                </div>
                <input id="nom" v-model="form.nom" type="text" required
                  class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2 border"
                  placeholder="Nom du centre de santé">
              </div>
            </div>

            <!-- Champ Adresse -->
            <div>
              <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
              <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                  </svg>
                </div>
                <input id="adresse" v-model="form.adresse" type="text" required
                  class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2 border"
                  placeholder="Adresse complète">
              </div>
            </div>

            <!-- Champ Ville -->
            <div>
              <label for="ville" class="block text-sm font-medium text-gray-700">Ville</label>
              <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                  </svg>
                </div>
                <input id="ville" v-model="form.ville" type="text" required
                  class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2 border"
                  placeholder="Ville">
              </div>
            </div>

            <!-- Boutons -->
            <div class="flex items-center justify-between">
              <button type="button" @click="resetForm"
                class="px-4 py-2 text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                Réinitialiser
              </button>

              <button type="submit" :disabled="form.processing"
                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                :class="{ 'opacity-75 cursor-not-allowed': form.processing }">
                <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ form.processing ? 'Enregistrement...' : 'Enregistrer' }}
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Pied de page -->
      <div class="mt-8 text-center text-sm text-gray-500 animous2">
        <p>© {{ new Date().getFullYear() }} CS Opt. Tous droits réservés.</p>
      </div>
    </div>
  </template>

  <script setup>
  import { useForm, router } from '@inertiajs/vue3'
  import { ref, watch, computed } from 'vue'


  const form = useForm({
    nom: '',
    adresse: '',
    ville: '',
    admin_c_s_id: null
  })

  const hasErrors = computed(() => Object.keys(form.errors).length > 0)

  const submitForm = () => {
    form.post(route('CS.Create'), {
      onSuccess: () => {
        form.reset()
      },
      preserveScroll: true
    })
  }

  const resetForm = () => {
    form.reset()
  }

  // Masquer automatiquement les erreurs après 5 secondes
  watch(
    () => form.errors,
    (errors) => {
      if (Object.keys(errors).length > 0) {
        setTimeout(() => {
          form.clearErrors()
        }, 5000)
      }
    },
    { deep: true }
  )
  </script>

  <script>
  export default {
    layout: null,
  }
  </script>

  <style scoped>
  .animous {
    animation: appear 0.8s ease-in-out;
  }

  .animous2 {
    opacity: 0;
    animation: appear 0.8s ease-in-out 0.5s forwards;
  }

  @keyframes appear {
    0% {
      opacity: 0;
      transform: translateY(-20px);
    }
    100% {
      opacity: 1;
      transform: translateY(0);
    }
  }

  input:focus {
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.3);
    outline: none;
  }
  </style>
