<template>

    <Head title="Tableau de Bord Secrétaire" />

    <div class="px-6 py-6">
        <div
            class="min-h-[calc(100vh-8rem)] bg-gradient-to-br from-indigo-50 to-white rounded-xl shadow-md border border-gray-200 p-10">
            <!-- En-tête -->
            <div class="mb-10 text-center">
                <h1 class="text-4xl font-bold text-indigo-800 mb-2">Bienvenue au Centre de Santé</h1>
                <p class="text-xl text-gray-600">Tableau de bord secrétaire - Gestion des patients et consultations</p>
            </div>

            <!-- Statistiques rapides -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10 animous">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                    <div class="bg-blue-100 p-4 rounded-full mr-4">
                        <i class="fas fa-user-injured text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Patients aujourd'hui</p>
                        <p class="text-2xl font-bold">24</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                    <div class="bg-green-100 p-4 rounded-full mr-4">
                        <i class="fas fa-calendar-check text-2xl text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">RDV programmés</p>
                        <p class="text-2xl font-bold">15</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                    <div class="bg-purple-100 p-4 rounded-full mr-4">
                        <i class="fas fa-file-invoice text-2xl text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Factures à régler</p>
                        <p class="text-2xl font-bold">8</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                    <div class="bg-yellow-100 p-4 rounded-full mr-4">
                        <i class="fas fa-vial text-2xl text-yellow-600"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Analyses en attente</p>
                        <p class="text-2xl font-bold">5</p>
                    </div>
                </div>
            </div>

            <!-- Après la section des statistiques -->
            <div class="mb-10 bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden animous2">
                <h3 class="text-xl font-bold text-gray-800 p-6 pb-2">Guide Rapide de la Secrétaire</h3>
                <p class="text-gray-500 px-6">Conseils et bonnes pratiques pour votre quotidien</p>

                <div class="relative">
                    <!-- Carrousel items -->
                    <div class="carousel-container overflow-hidden">
                        <div class="carousel-track flex transition-transform duration-300 ease-in-out">
                            <!-- Item 1 -->
                            <div class="carousel-item w-full flex-shrink-0 p-6">
                                <div class="flex flex-col md:flex-row items-center">
                                    <div class="bg-indigo-100 p-5 rounded-full mb-4 md:mb-0 md:mr-6">
                                        <i class="fas fa-user-check text-3xl text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-800 mb-2">Bien accueillir les patients
                                        </h4>
                                        <ul class="text-gray-600 list-disc pl-5 space-y-1">
                                            <li>Vérifier l'identité du patient</li>
                                            <li>Mettre à jour les informations si nécessaire</li>
                                            <li>Préparer le dossier médical avant la consultation</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Item 2 -->
                            <div class="carousel-item w-full flex-shrink-0 p-6">
                                <div class="flex flex-col md:flex-row items-center">
                                    <div class="bg-green-100 p-5 rounded-full mb-4 md:mb-0 md:mr-6">
                                        <i class="fas fa-calendar-plus text-3xl text-green-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-800 mb-2">Gestion des RDV</h4>
                                        <ul class="text-gray-600 list-disc pl-5 space-y-1">
                                            <li>Prévoir 15 minutes entre chaque consultation</li>
                                            <li>Confirmer les RDV la veille par SMS</li>
                                            <li>Anticiper les urgences dans l'agenda</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Item 3 -->
                            <div class="carousel-item w-full flex-shrink-0 p-6">
                                <div class="flex flex-col md:flex-row items-center">
                                    <div class="bg-blue-100 p-5 rounded-full mb-4 md:mb-0 md:mr-6">
                                        <i class="fas fa-file-invoice text-3xl text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-800 mb-2">Facturation</h4>
                                        <ul class="text-gray-600 list-disc pl-5 space-y-1">
                                            <li>Vérifier les informations avant impression</li>
                                            <li>Classer les factures par date</li>
                                            <li>Archiver les règlements en fin de journée</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Item 4 -->
                            <div class="carousel-item w-full flex-shrink-0 p-6">
                                <div class="flex flex-col md:flex-row items-center">
                                    <div class="bg-purple-100 p-5 rounded-full mb-4 md:mb-0 md:mr-6">
                                        <i class="fas fa-vial text-3xl text-purple-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-800 mb-2">Communication Labo</h4>
                                        <ul class="text-gray-600 list-disc pl-5 space-y-1">
                                            <li>Vérifier l'orthographe des noms</li>
                                            <li>Noter l'heure de prélèvement</li>
                                            <li>Suivre les résultats en attente</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contrôles du carrousel -->
                    <button
                        class="carousel-prev absolute left-2 top-1/2 -translate-y-1/2 bg-white p-2 rounded-full shadow-md text-gray-600 hover:text-indigo-600">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button
                        class="carousel-next absolute right-2 top-1/2 -translate-y-1/2 bg-white p-2 rounded-full shadow-md text-gray-600 hover:text-indigo-600">
                        <i class="fas fa-chevron-right"></i>
                    </button>

                    <!-- Indicateurs -->
                    <div class="flex justify-center space-x-2 p-4">
                        <button class="carousel-indicator w-3 h-3 rounded-full bg-gray-300 hover:bg-indigo-400"
                            data-index="0"></button>
                        <button class="carousel-indicator w-3 h-3 rounded-full bg-gray-300 hover:bg-indigo-400"
                            data-index="1"></button>
                        <button class="carousel-indicator w-3 h-3 rounded-full bg-gray-300 hover:bg-indigo-400"
                            data-index="2"></button>
                        <button class="carousel-indicator w-3 h-3 rounded-full bg-gray-300 hover:bg-indigo-400"
                            data-index="3"></button>
                    </div>
                </div>
            </div>

            <!-- Actions principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animous3">
                <!-- Enregistrement patient -->
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-indigo-100 p-3 rounded-full mr-4">
                            <i class="fas fa-user-plus text-xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Enregistrer un patient</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Créer un nouveau dossier patient avec ses informations personnelles et
                        médicales.</p>
                    <button
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm">
                        Nouveau patient <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>

                <!-- Gestion des RDV -->
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 p-3 rounded-full mr-4">
                            <i class="fas fa-calendar-alt text-xl text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Gestion des rendez-vous</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Planifier, modifier ou annuler des consultations avec les médecins
                        disponibles.</p>
                    <button
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                        Voir agenda <i class="fas fa-calendar-day ml-2"></i>
                    </button>
                </div>

                <!-- Dossiers patients -->
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-folder-open text-xl text-blue-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Dossiers patients</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Accéder à l'historique médical complet des patients enregistrés.</p>
                    <button
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                        Rechercher <i class="fas fa-search ml-2"></i>
                    </button>
                </div>

                <!-- Facturation -->
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 p-3 rounded-full mr-4">
                            <i class="fas fa-file-invoice-dollar text-xl text-purple-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Facturation</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Générer des factures pour les consultations, examens et médicaments.
                    </p>
                    <button
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm">
                        Nouvelle facture <i class="fas fa-file-export ml-2"></i>
                    </button>
                </div>

                <!-- Notifications labo -->
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-yellow-100 p-3 rounded-full mr-4">
                            <i class="fas fa-vial text-xl text-yellow-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Laboratoire</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Envoyer des demandes d'analyses et suivre les résultats en attente.
                    </p>
                    <button
                        class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm">
                        Demander analyse <i class="fas fa-paper-plane ml-2"></i>
                    </button>
                </div>

                <!-- Consultation rapide -->
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-red-100 p-3 rounded-full mr-4">
                            <i class="fas fa-stethoscope text-xl text-red-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Démarrer consultation</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Initialiser le processus de consultation pour un patient en salle
                        d'attente.</p>
                    <button
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                        Commencer <i class="fas fa-play ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- Section urgences/alertes -->
            <div class="mt-10 bg-red-50 border border-red-200 rounded-xl p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-600 mr-3"></i>
                    <h3 class="text-xl font-bold text-red-800">Alertes et urgences</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white p-4 rounded-lg border border-red-100 flex items-center">
                        <i class="fas fa-ambulance text-red-500 mr-3"></i>
                        <div>
                            <p class="font-medium">Urgence en attente</p>
                            <p class="text-sm text-gray-600">1 patient en salle d'urgence</p>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-red-100 flex items-center">
                        <i class="fas fa-clock text-red-500 mr-3"></i>
                        <div>
                            <p class="font-medium">RDV en retard</p>
                            <p class="text-sm text-gray-600">2 consultations non commencées</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'

const currentSlide = ref(0)
const totalSlides = 4

onMounted(() => {
    const track = document.querySelector('.carousel-track')
    const prevBtn = document.querySelector('.carousel-prev')
    const nextBtn = document.querySelector('.carousel-next')
    const indicators = document.querySelectorAll('.carousel-indicator')

    function updateCarousel() {
        track.style.transform = `translateX(-${currentSlide.value * 100}%)`

        // Update indicators
        indicators.forEach((indicator, index) => {
            if (index === currentSlide.value) {
                indicator.classList.add('bg-indigo-600')
                indicator.classList.remove('bg-gray-300')
            } else {
                indicator.classList.remove('bg-indigo-600')
                indicator.classList.add('bg-gray-300')
            }
        })
    }

    nextBtn.addEventListener('click', () => {
        currentSlide.value = (currentSlide.value + 1) % totalSlides
        updateCarousel()
    })

    prevBtn.addEventListener('click', () => {
        currentSlide.value = (currentSlide.value - 1 + totalSlides) % totalSlides
        updateCarousel()
    })

    indicators.forEach(indicator => {
        indicator.addEventListener('click', () => {
            currentSlide.value = parseInt(indicator.dataset.index)
            updateCarousel()
        })
    })

    // Auto-rotation
    const interval = setInterval(() => {
        currentSlide.value = (currentSlide.value + 1) % totalSlides
        updateCarousel()
    }, 5000)

    // Cleanup on component unmount
    return () => clearInterval(interval)
})
</script>

<style scoped>
.animous {
    opacity: 0;
    animation: appear 1s ease-in-out forwards;
}

.animous2 {
    opacity: 0;
    animation: appear 1s ease-in-out 1s forwards;
}

.animous3 {
    opacity: 0;
    animation: appear 1s ease-in-out 2s forwards;
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

.carousel-container {
    width: 100%;
    overflow: hidden;
}

.carousel-track {
    display: flex;
    width: 100%;
    transition: transform 0.5s ease;
}

.carousel-item {
    flex: 0 0 100%;
    min-width: 100%;
}
</style>
