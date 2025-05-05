<template>
    <div class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Réinitialiser votre mot de passe
            </h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-6 shadow rounded-lg sm:px-10">
                <!-- Message de statut (succès) -->
                <div v-if="$page.props.flash.status" class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ $page.props.flash.status }}
                </div>

                <!-- Messages d'erreur -->
                <div v-if="form.errors.email" class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    {{ form.errors.email }}
                </div>

                <form @submit.prevent="submit">
                    <input type="hidden" name="token" v-model="form.token">

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Adresse email
                        </label>
                        <input id="email" v-model="form.email" type="email" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            :class="{ 'border-red-500': form.errors.email }">
                    </div>

                    <div class="mt-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Nouveau mot de passe
                        </label>
                        <input id="password" v-model="form.password" type="password" required minlength="8"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            :class="{ 'border-red-500': form.errors.password }">
                    </div>

                    <div class="mt-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                            Confirmer le mot de passe
                        </label>
                        <input id="password_confirmation" v-model="form.password_confirmation" type="password" required minlength="8"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mt-6">
                        <button type="submit" :disabled="form.processing"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            :class="{ 'opacity-50 cursor-not-allowed': form.processing }">
                            <span v-if="form.processing">Traitement...</span>
                            <span v-else>Réinitialiser le mot de passe</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    token: String,
    email: String,
    errors: Object
})

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('password', 'password_confirmation')
        },
        onError: () => {
            if (form.errors.token) {
                // Gestion spécifique des erreurs de token
                alert("Le lien de réinitialisation est invalide ou a expiré.")
            }
        }
    })
}
</script>

<script>
export default {
    layout: null,
}
</script>
