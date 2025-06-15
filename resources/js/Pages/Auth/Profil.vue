<script setup>
import { Head } from '@inertiajs/vue3'

// Définition des props que ce composant recevra de Laravel
const props = defineProps({
    user: Object, // L'objet utilisateur authentifié
})

// Accès facile aux données de l'utilisateur
const user = props.user;

// Fonction pour déterminer si l'utilisateur a un centre de santé associé
const hasCenter = (role) => {
    return ['admin_cs', 'medecin', 'secretaire', 'laborantin'].includes(role);
};
</script>

<template>
    <Head title="Mon Profil" />

    <div class="px-6 py-6 min-h-screen bg-gradient-to-br from-indigo-50 to-white flex items-center justify-center">
        <div class="w-full max-w-2xl bg-white rounded-xl shadow-xl border border-gray-200 p-8 md:p-10">
            <!-- En-tête du profil -->
            <div class="text-center mb-8">
                <div class="w-24 h-24 mx-auto rounded-full bg-indigo-100 flex items-center justify-center overflow-hidden shadow-lg border-4 border-white mb-4">
                    <!-- Icône utilisateur par défaut -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-16 h-16 text-indigo-600">
                        <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" clip-rule="evenodd" />
                    </svg>
                    <!-- Alternativement, vous pourriez afficher une image de profil si `user.profile_picture` existe -->
                    <!-- <img v-if="user.profile_picture" :src="user.profile_picture" alt="Profile Picture" class="w-full h-full object-cover"> -->
                </div>
                <h1 class="text-3xl font-bold text-indigo-800">{{ user.prenom }} {{ user.nom }}</h1>
                <p class="text-lg text-gray-600 capitalize">{{ user.role.replace('_', ' ') }}</p>
            </div>

            <!-- Détails du profil -->
            <div class="space-y-6">
                <!-- Email -->
                <div class="flex items-center bg-gray-50 p-4 rounded-lg border border-gray-100 shadow-sm">
                    <i class="fas fa-envelope text-indigo-500 text-xl mr-4"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Adresse Email</p>
                        <p class="text-lg text-gray-800">{{ user.email }}</p>
                    </div>
                </div>

                <!-- Spécialité (pour les médecins) -->
                <div v-if="user.role === 'medecin' && user.specialite" class="flex items-center bg-gray-50 p-4 rounded-lg border border-gray-100 shadow-sm">
                    <i class="fas fa-user-md text-indigo-500 text-xl mr-4"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Spécialité</p>
                        <p class="text-lg text-gray-800">{{ user.specialite }}</p>
                    </div>
                </div>

                <!-- Centre de Santé (pour les rôles concernés) -->
                <div v-if="hasCenter(user.role) && user.centre_de_sante_id" class="flex items-center bg-gray-50 p-4 rounded-lg border border-gray-100 shadow-sm">
                    <i class="fas fa-hospital text-indigo-500 text-xl mr-4"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Centre de Santé</p>
                        <!-- Assurez-vous que la relation 'centreDeSante' est chargée sur l'objet user si vous voulez afficher le nom du centre -->
                        <p class="text-lg text-gray-800"> {{ user.centre_de_sante.nom }}</p>
                        <!-- Si vous avez la relation chargée, vous pouvez faire : -->
                        <!-- <p v-if="user.centre_de_sante" class="text-lg text-gray-800">{{ user.centre_de_sante.nom }}</p> -->
                    </div>
                </div>

                <!-- Boutons d'action (exemple) -->
                <div class="pt-6 border-t border-gray-200 mt-8 flex justify-center space-x-4">
                    <!-- <button class="px-6 py-3 bg-indigo-600 text-white rounded-lg shadow-md hover:bg-indigo-700 transition-colors flex items-center">
                        <i class="fas fa-edit mr-2"></i> Modifier le Profil
                    </button> -->
                    <!-- <button class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg shadow-md hover:bg-gray-300 transition-colors flex items-center">
                        <i class="fas fa-key mr-2"></i> Changer le mot de passe
                    </button> -->
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Aucun style spécifique supplémentaire nécessaire si Tailwind est configuré. */
/* Les icônes "fas" nécessitent une inclusion de Font Awesome. */
</style>
