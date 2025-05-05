<template>

    <Head title="Ajout Administrateur" />

    <div class="px-6 py-6">
        <div
            class="min-h-[calc(100vh-8rem)] bg-gradient-to-br from-indigo-50 to-white rounded-xl shadow-md border border-gray-200 p-10">
            <!-- Titre principal -->
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Ajouter un Administrateur CS</h1>

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
                        {{ $page.props.flash.msg }}
                    </p>
                </div>
            </div>





            <!-- Formulaire d'ajout -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8 border-l-4 border-blue-500 animous">
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                            <input id="nom" v-model="form.nom" type="text" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
                            <input id="prenom" v-model="form.prenom" type="text" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input id="email" v-model="form.email" type="email" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Un mot de passe aléatoire sera généré et envoyé par email à l'administrateur.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="reset"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit" :disabled="form.processing"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md shadow-sm text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3'
import { ref, watch } from 'vue'

const page = usePage()

// Formulaire simplifié (sans les champs password)
const form = useForm({
    nom: '',
    prenom: '',
    email: ''
})

const submit = () => {
    form.post(route('SuperAdmin.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset()
    })
}

// Optionnel : Fermer automatiquement la notification après 5 secondes
watch(() => page.props.flash, (newVal) => {
    if (newVal.success || newVal.error) {
        setTimeout(() => {
            page.props.flash = {}
        }, 5000)
    }
}, { deep: true })
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
