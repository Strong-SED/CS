<template>
    <div
        class="min-h-screen bg-gradient-to-r from-indigo-200 to-white flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
        <!-- Logo et Titre -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center animous">
            <div class="flex justify-center">
                <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4">
                    </path>
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900 ">Connexion à votre compte</h2>
        </div>

        <!-- Messages d'état -->
        <div v-if="showEmailError" class="sm:mx-auto sm:w-full sm:max-w-md animous">
            <p class="text-red-800 bg-red-200 m-4 text-sm rounded-2xl p-4 transition-all duration-700">
                {{ form.errors.email }}
            </p>
        </div>

        <!-- Carte de connexion -->
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md animous">
            <div class="bg-white py-8 px-6 shadow rounded-lg sm:px-10 border border-gray-200">

                <!-- Formulaire -->
                <form class="mb-0 space-y-6" @submit.prevent="submit">
                    <!-- Champ Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z">
                                    </path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                            </div>
                            <input id="email" v-model="form.email" type="email" autocomplete="email" required
                                class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2 border"
                                placeholder="email@exemple.com">
                        </div>
                    </div>

                    <!-- Champ Mot de passe -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <input id="password" v-model="form.password" type="password" autocomplete="current-password"
                                required
                                class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2 border"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" v-model="form.remember" type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember" class="ml-2 block text-sm text-gray-700">Se souvenir de moi</label>
                        </div>

                        <div class="text-sm">
                            <Link :href="route('forgot_password')"
                                class="font-medium text-blue-600 hover:text-blue-500">
                            Mot de passe oublié?
                            </Link>
                        </div>
                    </div>

                    <!-- Bouton de connexion -->
                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                            :disabled="form.processing" :class="{ 'opacity-75 cursor-not-allowed': form.processing }">
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span>{{ form.processing ? 'Connexion en cours...' : 'Se connecter' }}</span>
                        </button>
                    </div>
                </form>

                <!-- Séparateur -->
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500"></span>
                        </div>
                    </div>
                </div>

                <!-- Boutons de connexion sociale -->

            </div>
        </div>

        <!-- Pied de page -->
        <div class="mt-8 text-center text-sm text-gray-500 animous2">
            <p>© {{ Datetime.getFullYear() }} CS Ops. Tous droits réservés.</p>
            <div class="mt-2 flex justify-center space-x-6">
                <a href="#" class="text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Conditions d'utilisation</span>
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                            clip-rule="evenodd"></path>
                    </svg>
                </a>
                <a href="#" class="text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Politique de confidentialité</span>
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue'
import { route } from 'ziggy-js';

const Datetime = new Date()

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

// Réactive pour contrôler l'affichage de l'erreur
const showEmailError = ref(false)
let errorTimeout = null

// Watcher pour détecter les changements d'erreur
watch(
    () => form.errors.email,
    (newError) => {
        if (newError) {
            showEmailError.value = true

            // Effacer le timeout précédent s'il existe
            if (errorTimeout) clearTimeout(errorTimeout)

            // Masquer l'erreur après 3 secondes
            errorTimeout = setTimeout(() => {
                showEmailError.value = false
                form.clearErrors('email') // Optionnel: efface aussi l'erreur du form
            }, 3000)
        } else {
            showEmailError.value = false
        }
    }
)


const submit = () => {
    form.post(route('Login_verifier'), {
        onFinish: () => form.reset('password'),
    })
}

</script>

<script>
export default {
    layout: null,
}
</script>

<style scoped>
.animous {
    animation: appear 1s ease-in-out;
}

.animous2 {
    opacity: 0;
    animation: appear 1s ease-in-out 1.5s forwards;
}

@keyframes appear {
    0% {
        opacity: 0;
        transform: translateY(-30px);
    }

    100% {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
