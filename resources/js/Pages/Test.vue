<template>
    <Head title="Test" />

        <div class="px-6 py-6">
            <div
                class="min-h-[calc(100vh-8rem)] bg-gradient-to-br from-indigo-50 to-white rounded-xl shadow-md border border-gray-200 p-10 ">

                <div
                    class=" w-10/12 rounded-lg border-l-4 border-indigo-600 px-4 py-4 shadow grid grid-cols-5 items-center">

                    <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto col-span-1 ">
                        <i class="fas fa-file-alt text-3xl text-indigo-600"></i>
                    </div>

                    <div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">This is my test page</h3>
                        <p class="text-gray-600 mb-4">Active: Windows</p>
                        <p class="text-gray-500 text-sm mb-6">Go to hell if you can't be humble</p>
                        <button
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-md">
                            Get Started
                        </button>
                    </div>

                </div>
            </div>
        </div>

</template>

<script setup>
    import { Head } from '@inertiajs/vue3'
    import { Link } from  '@inertiajs/vue3'
</script>
