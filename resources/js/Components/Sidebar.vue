<template>
    <div class="min-w-80 fixed h-full bg-gradient-to-b from-indigo-50 to-white shadow-xl transition-all duration-300 ease-in-out transform hover:shadow-2xl">
        <nav class="h-full flex flex-col">
            <!-- Logo Section -->
            <div class="p-6 pb-4">
                <Link :href="route('SuperAdmin.Home')" class="flex items-center space-x-2">
                    <div class="w-10 h-10 rounded-lg bg-indigo-600 flex items-center justify-center shadow-md">
                        <i class="fas fa-hashtag text-white text-lg"></i>
                    </div>
                    <span class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">CS-Ops</span>
                </Link>
            </div>

            <!-- User Profile -->
            <div class="px-6 py-4 flex items-center space-x-3 group cursor-pointer">
                <div class="relative">
                    <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center overflow-hidden shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-indigo-600">
                            <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white shadow-sm"></div>
                </div>
                <div class="flex flex-col">
                    <span v-if="user" class="font-semibold text-gray-800 group-hover:text-indigo-600 transition-colors">
                        {{ user.nom }} {{ user.prenom }}
                    </span>
                    <span v-if="user" class="text-xs text-gray-500">{{ user.role }}</span>
                </div>
            </div>

            <!-- Navigation Menu -->

            <!-- SuperAdmin  -->
            <div
                class="flex-1 px-3 py-4 space-y-1 overflow-y-auto"
                v-if="user.role === role.s_admin">
                <!-- SuperAdmin -->
                <Link :href="route('SuperAdmin.Home')" class="flex items-center px-4 py-3 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="fas fa-tachometer-alt text-sm"></i>
                    </div>
                    <span class="ml-3 font-medium">Dashboard</span>
                </Link>

                <!-- Test -->
                <Link  :href="route('SuperAdmin.Create')" class="flex items-center px-4 py-3 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="fas fa-solid fa-user-plus text-sm"></i>
                    </div>
                    <span class="ml-3 font-medium">Ajout d'Administrateur CS</span>
                </Link>
            </div>


            <!-- Admin cs -->
            <div
                class="flex-1 px-3 py-4 space-y-1 overflow-y-auto"
                v-if="user.role === role.admin">
                <!-- SuperAdmin -->
                <Link :href="route('AdminCS.Home')" class="flex items-center px-4 py-3 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="fas fa-tachometer-alt text-sm"></i>
                    </div>
                    <span class="ml-3 font-medium">Dashboard</span>
                </Link>

                <!-- Test -->
                <Link  :href="route('AdminCS.Create')" class="flex items-center px-4 py-3 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="fas fa-solid fa-user-plus text-sm"></i>
                    </div>
                    <span class="ml-3 font-medium">Ajout d'un Utilisateur</span>
                </Link>
            </div>

            <!-- Medecin -->
            <div
                class="flex-1 px-3 py-4 space-y-1 overflow-y-auto"
                v-if="user.role === role.med">
                <!-- SuperAdmin -->
                <Link :href="route('SuperAdmin.Home')" class="flex items-center px-4 py-3 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="fas fa-tachometer-alt text-sm"></i>
                    </div>
                    <span class="ml-3 font-medium">Dashboard</span>
                </Link>

                <!-- Test -->
                <Link  :href="route('SuperAdmin.Home')" class="flex items-center px-4 py-3 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="fas fa-regular fa-flask-vial text-sm"></i>
                    </div>
                    <span class="ml-3 font-medium">Test-Page</span>
                </Link>
            </div>

            <!-- Secretaire -->
            <div
                class="flex-1 px-3 py-4 space-y-1 overflow-y-auto"
                v-if="user.role === role.secret">
                <!-- SuperAdmin -->
                <Link :href="route('Secretaire.Home')" class="flex items-center px-4 py-3 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="fas fa-solid fa-house text-lg"></i>
                    </div>
                    <span class="ml-3 font-medium">Home</span>
                </Link>


                <Link  :href="route('Secretaire.CreateP')" class="flex items-center px-4 py-3 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="fas fa-solid fa-person-circle-plus text-lg"></i>
                    </div>
                    <span class="ml-3 font-medium">Enregistrer un patient</span>
                </Link>

                <Link :href="route('Secretaire.Dashboard')" class="flex items-center px-4 py-3 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="fas fa-tachometer-alt text-lg"></i>
                    </div>
                    <span class="ml-3 font-medium">Dashboard</span>
                </Link>

            </div>

            <!-- Laborantin -->
            <div
                class="flex-1 px-3 py-4 space-y-1 overflow-y-auto"
                v-if="user.role === role.lab">
                <!-- SuperAdmin -->
                <Link :href="route('SuperAdmin.Home')" class="flex items-center px-4 py-3 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="fas fa-tachometer-alt text-sm"></i>
                    </div>
                    <span class="ml-3 font-medium">Dashboard</span>
                </Link>

                <!-- Test -->
                <Link  :href="route('SuperAdmin.Home')" class="flex items-center px-4 py-3 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="fas fa-regular fa-flask-vial text-sm"></i>
                    </div>
                    <span class="ml-3 font-medium">Test-Page</span>
                </Link>
            </div>

            <!-- Sidebar Footer -->
            <div class="px-6 py-4 border-t border-gray-100">
                <div class="flex items-center justify-center space-x-2">
                    <button class="p-2 rounded-full text-gray-500 hover:bg-gray-100 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-cog"></i>
                    </button>
                    <button class="p-2 rounded-full text-gray-500 hover:bg-gray-100 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-question-circle"></i>
                    </button>
                    <Link :href="route('logout')" method="post" as="button" class="p-2 rounded-full text-gray-500 hover:bg-gray-100 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-sign-out-alt"></i>
                    </Link>
                </div>
            </div>
        </nav>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { usePage } from '@inertiajs/vue3';

const { auth } = usePage().props;
const user = auth.user;

const role = {
    s_admin: 'super_admin',
    admin: 'admin_cs',
    med: 'medecin',
    secret: 'secretaire',
    lab: 'laborantin',
}

</script>
