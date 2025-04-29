<template>
    <div
        class=" min-w-80 fixed h-full bg-gradient-to-b from-indigo-50 to-white shadow-xl transition-all duration-300 ease-in-out transform hover:shadow-2xl">
        <nav class="h-full flex flex-col">
            <!-- Logo Section -->
            <div class="p-6 pb-4">
                <Link :href="route('Home')" class="flex items-center space-x-2">
                <div class="w-10 h-10 rounded-lg bg-indigo-600 flex items-center justify-center shadow-md">
                    <i class="fas fa-hashtag text-white text-lg"></i>
                </div>
                <span
                    class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">CS-Ops</span>
                </Link>
            </div>

            <!-- User Profile -->
            <div class="px-6 py-4 flex items-center space-x-3 group cursor-pointer">
                <div class="relative">
                    <div
                        class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center overflow-hidden shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-8 h-8 text-indigo-600">
                            <path fill-rule="evenodd"
                                d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div
                        class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white shadow-sm">
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="font-semibold text-gray-800 group-hover:text-indigo-600 transition-colors">Jhon
                        Doe</span>
                    <span class="text-xs text-gray-500">Administrator</span>
                </div>
            </div>

            <!-- Navigation Menu -->
            <div class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <!-- Dashboard -->
                <Link :href="route('Home')"
                    class="flex items-center px-4 py-3 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group">
                <div
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <i class="fas fa-tachometer-alt text-sm"></i>
                </div>
                <span class="ml-3 font-medium">Dashboard</span>
                </Link>

                <!-- Elements Dropdown -->
                <!-- <div class="relative">
                            <div
                                class="flex items-center px-4 py-3 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 cursor-pointer group">
                                <div
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                    <i class="fas fa-laptop text-sm"></i>
                                </div>
                                <span class="ml-3 font-medium">Elements</span>
                                <i
                                    class="fas fa-chevron-down ml-auto text-xs text-gray-400 group-hover:text-indigo-600 transition-transform duration-200"></i>
                            </div>
                            <div class="ml-12 mt-1 space-y-1 hidden">
                                <Link href="button.html"
                                    class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">Buttons</Link>
                                <Link href="typography.html"
                                    class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">Typography</Link>
                                <Link href="element.html"
                                    class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">Other
                                    Elements</Link>
                            </div>
                        </div> -->

                <!-- Widgets -->
                <!-- <Link href="widget.html"
                            class="flex items-center px-4 py-3 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group">
                            <div
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <i class="fas fa-th text-sm"></i>
                            </div>
                            <span class="ml-3 font-medium">Widgets</span>
                        </Link> -->

                <!-- Forms -->
                <!-- <Link href="form.html"
                            class="flex items-center px-4 py-3 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group">
                            <div
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <i class="fas fa-keyboard text-sm"></i>
                            </div>
                            <span class="ml-3 font-medium">Forms</span>
                        </Link> -->

                <!-- Tables -->
                <!-- <Link href="table.html"
                            class="flex items-center px-4 py-3 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group">
                            <div
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <i class="fas fa-table text-sm"></i>
                            </div>
                            <span class="ml-3 font-medium">Tables</span>
                        </Link> -->

                <!-- Charts -->
                <!-- <Link href="chart.html"
                            class="flex items-center px-4 py-3 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group">
                            <div
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <i class="fas fa-chart-bar text-sm"></i>
                            </div>
                            <span class="ml-3 font-medium">Charts</span>
                        </Link> -->

                <!-- Test -->
                <Link :href="route('Test')"
                    class="flex items-center px-4 py-3 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group">
                <div
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <i class=" fas fa-regular fa-flask-vial text-sm"></i>
                </div>
                <span class="ml-3 font-medium">Test-Page</span>
                </Link>

                <!-- Pages Dropdown -->
                <!-- <div class="relative">
                            <div
                                class="flex items-center px-4 py-3 text-indigo-600 bg-indigo-50 rounded-xl cursor-pointer group">
                                <div
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-600 text-white">
                                    <i class="far fa-file-alt text-sm"></i>
                                </div>
                                <span class="ml-3 font-medium">Pages</span>
                                <i
                                    class="fas fa-chevron-down ml-auto text-xs text-indigo-400 transition-transform duration-200"></i>
                            </div>
                            <div class="ml-12 mt-1 space-y-1">
                                <Link href="signin.html"
                                    class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">Sign
                                    In</Link>
                                <Link href="signup.html"
                                    class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">Sign
                                    Up</Link>
                                <Link href="404.html"
                                    class="block px-3 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">404
                                    Error</Link>
                                <Link href="blank.html"
                                    class="block px-3 py-2 text-sm text-indigo-600 bg-indigo-50 rounded-lg">Blank
                                    Page</Link>
                            </div>
                        </div> -->
            </div>

            <!-- Sidebar Footer -->
            <div class="px-6 py-4 border-t border-gray-100">
                <div class="flex items-center justify-center space-x-2">
                    <button
                        class="p-2 rounded-full text-gray-500 hover:bg-gray-100 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-cog"></i>
                    </button>
                    <button
                        class="p-2 rounded-full text-gray-500 hover:bg-gray-100 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-question-circle"></i>
                    </button>
                    <button
                        class="p-2 rounded-full text-gray-500 hover:bg-gray-100 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </nav>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const isActiveRoute = (name) => {
    return route().current(name);
};
</script>
