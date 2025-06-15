<template>
    <nav
        class="flex items-center justify-between bg-gradient-to-l from-indigo-50 to-slate-50 shadow-md sticky top-0 px-6 py-3 z-40 border-b border-gray-100">
        <div class="flex items-center space-x-4">
            <a href="#" class="hidden lg:flex items-center space-x-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center shadow-md">
                    <i class="fas fa-hashtag text-white text-sm"></i>
                </div>
                <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">CS-Ops</span>
            </a>

            <button class="text-gray-500 hover:text-indigo-600 lg:hidden">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <form class="hidden md:flex">
                <div class="relative">
                    <span v-if="user && user.centre_de_sante" class="text-gray-700 font-medium text-lg">
                        {{ user.centre_de_sante.nom }}
                    </span>
                    <span v-else class="text-gray-400 text-lg">
                        Centre non défini
                    </span>
                </div>
            </form>
        </div>

        <div class="flex items-center space-x-6">
            <!-- <div class="relative group">
                <button class="text-gray-500 hover:text-indigo-600 transition-colors relative">
                    <i class="fas fa-envelope text-xl"></i>
                    <span
                        class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full text-white text-xs flex items-center justify-center">3</span>
                </button>
                <div
                    class="hidden group-hover:block absolute right-0 mt-2 w-72 bg-white shadow-xl rounded-lg py-2 z-50 border border-gray-100">
                    <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                        <h4 class="font-medium text-gray-800">Messages</h4>
                        <span class="text-xs text-indigo-600">3 New</span>
                    </div>
                    <div class="max-h-80 overflow-y-auto">
                        </div>
                    <a href="#" class="block text-center py-2 text-sm text-indigo-600 hover:bg-gray-50">View All</a>
                </div>
            </div>

            <div class="relative group">
                <button class="text-gray-500 hover:text-indigo-600 transition-colors relative">
                    <i class="fas fa-bell text-xl"></i>
                    <span
                        class="absolute -top-1 -right-1 w-4 h-4 bg-yellow-500 rounded-full text-white text-xs flex items-center justify-center">5</span>
                </button>
                <div
                    class="hidden group-hover:block absolute right-0 mt-2 w-72 bg-white shadow-xl rounded-lg py-2 z-50 border border-gray-100">
                    <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                        <h4 class="font-medium text-gray-800">Notifications</h4>
                        <span class="text-xs text-indigo-600">5 New</span>
                    </div>
                    <div class="max-h-80 overflow-y-auto">
                        </div>
                    <a href="#" class="block text-center py-2 text-sm text-indigo-600 hover:bg-gray-50">View All</a>
                </div>
            </div> -->

            <Link :href="route('profile.show')" class="relative group flex items-center space-x-2">
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center overflow-hidden shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="w-6 h-6 text-indigo-600">
                        <path fill-rule="evenodd"
                            d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <span class="hidden lg:inline text-gray-700 font-medium">{{ user.nom }} {{ user.prenom }}</span>
                <i class="fas fa-chevron-down hidden lg:inline text-xs text-gray-400 group-hover:text-indigo-600"></i>
            </Link>
        </div>
    </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { usePage } from '@inertiajs/vue3';

const { auth } = usePage().props;
const user = auth.user; // `user` peut être null si non connecté

const role = {
    s_admin: 'super_admin',
    admin: 'admin_cs',
    med: 'medecin',
    secret: 'secretaire',
    lab: 'laborantin',
}
</script>
