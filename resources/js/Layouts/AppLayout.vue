<template>

    <div class="bg-white">
        <!-- Main Container -->
        <div class="relative bg-gradient-to-b from-indigo-50 to-white flex">
            
            <!-- Sidebar Start -->
            <Sidebar />
            <!-- Sidebar End -->

            <!-- Content Start -->
            <div class="ml-80 w-10/12">
                <!-- Navbar Start -->
                <Navbar />
                <!-- Navbar End -->

                <!-- Blank Page Start -->
                <slot />
                <!-- Blank Page End -->

                <!-- Footer Start -->
                <Footer />
                <!-- Footer End -->
            </div>
            <!-- Content End -->

            <!-- Back to Top Button -->
            <a href="#"
                class="fixed bottom-4 right-4 w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-indigo-700 transition">
                <i class="fas fa-arrow-up"></i>
            </a>
        </div>
    </div>


</template>

<style type="text/tailwindcss">
@layer utilities {

    /* Custom utilities if needed */
    .sidebar {
        width: 17rem;
    }

    .content {
        width: calc(100% - 17rem);
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 0;
            @apply hidden;
        }

        .content {
            width: 100%;
        }
    }
}
</style>

<script setup>
import { Link } from '@inertiajs/vue3';  // Pour les liens Inertia
import Footer from '@/Components/Footer.vue'
import Sidebar from '@/Components/Sidebar.vue'
import Navbar from '@/Components/Navbar.vue'
</script>
