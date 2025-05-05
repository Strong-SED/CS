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
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Réinitialisation du mot de passe</h2>
            <p class="mt-2 text-sm text-gray-600">
                Entrez votre adresse email pour recevoir un lien de réinitialisation
            </p>
        </div>

        <!-- Messages d'état -->
        <div v-if="showMessage" class="sm:mx-auto sm:w-full sm:max-w-md animous">
            <p :class="messageClass" class="m-4 text-sm rounded-2xl p-4">
                {{ messageText }}
            </p>
        </div>

        <!-- Carte de formulaire -->
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

                    <!-- Bouton d'envoi -->
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
                            <span>{{ form.processing ? 'Envoi en cours...' : 'Envoyer le lien' }}</span>
                        </button>
                    </div>
                </form>

                <!-- Lien de retour à la connexion -->
                <div class="mt-6 text-center">
                    <Link :href="route('login')" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        Retour à la page de connexion
                    </Link>
                </div>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="mt-8 text-center text-sm text-gray-500 animous2">
            <p>© {{ Datetime.getFullYear() }} CS Ops. Tous droits réservés.</p>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { ref, watch, computed } from 'vue';

const Datetime = new Date()


const form = useForm({
    email: '',
})

const showMessage = ref(false)
const messageType = ref('') // 'success' ou 'error'
let messageTimeout = null

const messageText = computed(() => {
    if (messageType.value === 'success') {
        return 'Un e-mail de réinitialisation a été envoyé.'
    } else if (messageType.value === 'error') {
        return form.errors.email || 'Une erreur est survenue'
    }
    return ''
})

const messageClass = computed(() => {
    return messageType.value === 'success'
        ? 'text-green-800 bg-green-200'
        : 'text-red-800 bg-red-200'
})

const showStatusMessage = (type) => {
    messageType.value = type
    showMessage.value = true

    // Effacer le timeout précédent s'il existe
    if (messageTimeout) clearTimeout(messageTimeout)

    // Masquer le message après 5 secondes
    messageTimeout = setTimeout(() => {
        showMessage.value = false
        if (type === 'error') {
            form.clearErrors('email')
        }
    }, 5000)
}

watch(
    () => form.errors.email,
    (newError) => {
        if (newError) {
            showStatusMessage('error')
        }
    }
)

const submit = () => {
    form.post(route('forgot_password_checker'), {
        onSuccess: () => {
            showStatusMessage('success')
            form.reset('email')
        },
        onError: () => {
            // Le watch s'occupe déjà de l'erreur
        }
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
        transform: translateY(-20px);
    }

    100% {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
