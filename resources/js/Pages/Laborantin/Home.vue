<script setup>
import { Head } from '@inertiajs/vue3'
import { onMounted, ref, defineProps } from 'vue'

const props = defineProps({
    stats: {
        type: Object,
        default: () => ({
            prescrites: 0,
            enCours: 0,
            termineesAujourdhui: 0,
        }),
    },
    // **NOUVELLES PROPS À DÉCLARER**
    recentAnalyses: {
        type: Array,
        default: () => [], // Valeur par défaut si aucune donnée n'est passée
    },
    urgentAnalyses: {
        type: Array,
        default: () => [], // Valeur par défaut
    },
});

const currentSlide = ref(0)
const totalSlides = 4 // Nombre total d'éléments du carrousel

onMounted(() => {
    const track = document.querySelector('.carousel-track')
    const prevBtn = document.querySelector('.carousel-prev')
    const nextBtn = document.querySelector('.carousel-next')
    const indicators = document.querySelectorAll('.carousel-indicator')

    function updateCarousel() {
        if (!track) return; // Sécurité au cas où l'élément n'est pas encore monté
        track.style.transform = `translateX(-${currentSlide.value * 100}%)`

        indicators.forEach((indicator, index) => {
            if (index === currentSlide.value) {
                indicator.classList.add('bg-blue-600')
                indicator.classList.remove('bg-gray-300')
            } else {
                indicator.classList.remove('bg-blue-600')
                indicator.classList.add('bg-gray-300')
            }
        })
    }

    // Ajout de vérifications pour s'assurer que les boutons existent avant d'ajouter des écouteurs
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            currentSlide.value = (currentSlide.value + 1) % totalSlides
            updateCarousel()
        })
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            currentSlide.value = (currentSlide.value - 1 + totalSlides) % totalSlides
            updateCarousel()
        })
    }

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

<template>
    <Head title="Tableau de Bord Laborantin" />

    <div class="px-6 py-6">
        <div class="min-h-[calc(100vh-8rem)] bg-gradient-to-br from-blue-50 to-white rounded-xl shadow-md border border-gray-200 p-10">
            <div class="mb-10 text-center">
                <h1 class="text-4xl font-bold text-blue-800 mb-2">Bienvenue au Laboratoire Médical</h1>
                <p class="text-xl text-gray-600">Tableau de bord laborantin - Gestion des analyses</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 animous">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                    <div class="bg-indigo-100 p-4 rounded-full mr-4">
                        <i class="fas fa-flask text-2xl text-indigo-600"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Analyses prescrites</p>
                        <p class="text-2xl font-bold">{{ props.stats.prescrites }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                    <div class="bg-orange-100 p-4 rounded-full mr-4">
                        <i class="fas fa-spinner text-2xl text-orange-600"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Analyses en cours</p>
                        <p class="text-2xl font-bold">{{ props.stats.enCours }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                    <div class="bg-green-100 p-4 rounded-full mr-4">
                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Analyses terminées aujourd'hui</p>
                        <p class="text-2xl font-bold">{{ props.stats.termineesAujourdhui }}</p>
                    </div>
                </div>
            </div>

            <div class="mb-10 bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden animous2">
                <h3 class="text-xl font-bold text-gray-800 p-6 pb-2">Guide Rapide du Laborantin</h3>
                <p class="text-gray-500 px-6">Conseils et bonnes pratiques pour votre quotidien au laboratoire</p>

                <div class="relative">
                    <div class="carousel-container overflow-hidden">
                        <div class="carousel-track flex transition-transform duration-300 ease-in-out" :style="{ transform: `translateX(-${currentSlide * 100}%)` }">
                            <div class="carousel-item w-full flex-shrink-0 p-6">
                                <div class="flex flex-col md:flex-row items-center">
                                    <div class="bg-blue-100 p-5 rounded-full mb-4 md:mb-0 md:mr-6">
                                        <i class="fas fa-vial text-3xl text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-800 mb-2">Réception et vérification des échantillons</h4>
                                        <ul class="text-gray-600 list-disc pl-5 space-y-1">
                                            <li>Vérifier l'étiquetage et la conformité</li>
                                            <li>Enregistrer les échantillons dans le système</li>
                                            <li>Stocker selon les protocoles appropriés</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="carousel-item w-full flex-shrink-0 p-6">
                                <div class="flex flex-col md:flex-row items-center">
                                    <div class="bg-purple-100 p-5 rounded-full mb-4 md:mb-0 md:mr-6">
                                        <i class="fas fa-microscope text-3xl text-purple-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-800 mb-2">Effectuer les analyses</h4>
                                        <ul class="text-gray-600 list-disc pl-5 space-y-1">
                                            <li>Suivre les procédures standardisées</li>
                                            <li>Assurer la calibration des équipements</li>
                                            <li>Documenter toutes les étapes du processus</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="carousel-item w-full flex-shrink-0 p-6">
                                <div class="flex flex-col md:flex-row items-center">
                                    <div class="bg-green-100 p-5 rounded-full mb-4 md:mb-0 md:mr-6">
                                        <i class="fas fa-file-medical text-3xl text-green-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-800 mb-2">Saisie et validation des résultats</h4>
                                        <ul class="text-gray-600 list-disc pl-5 space-y-1">
                                            <li>Enregistrer les résultats avec précision</li>
                                            <li>Vérifier la cohérence des données</li>
                                            <li>Transmettre les résultats aux médecins/secrétaires</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="carousel-item w-full flex-shrink-0 p-6">
                                <div class="flex flex-col md:flex-row items-center">
                                    <div class="bg-yellow-100 p-5 rounded-full mb-4 md:mb-0 md:mr-6">
                                        <i class="fas fa-tools text-3xl text-yellow-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-800 mb-2">Maintenance et contrôle qualité</h4>
                                        <ul class="text-gray-600 list-disc pl-5 space-y-1">
                                            <li>Effectuer la maintenance préventive des appareils</li>
                                            <li>Participer aux programmes de contrôle qualité</li>
                                            <li>Signaler toute anomalie ou dysfonctionnement</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button @click="prevSlide" class="carousel-prev absolute left-2 top-1/2 -translate-y-1/2 bg-white p-2 rounded-full shadow-md text-gray-600 hover:text-blue-600">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button @click="nextSlide" class="carousel-next absolute right-2 top-1/2 -translate-y-1/2 bg-white p-2 rounded-full shadow-md text-gray-600 hover:text-blue-600">
                        <i class="fas fa-chevron-right"></i>
                    </button>

                    <div class="flex justify-center space-x-2 p-4">
                        <button v-for="n in totalSlides" :key="n" @click="goToSlide(n - 1)"
                            class="carousel-indicator w-3 h-3 rounded-full"
                            :class="{ 'bg-blue-600': currentSlide === (n - 1), 'bg-gray-300 hover:bg-blue-400': currentSlide !== (n - 1) }">
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animous3">
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-indigo-100 p-3 rounded-full mr-4">
                            <i class="fas fa-list-alt text-xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Analyses prescrites</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Consulter la liste des analyses à effectuer.</p>
                    <!-- <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm">
                        Voir les demandes <i class="fas fa-arrow-right ml-2"></i>
                    </button> -->
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-orange-100 p-3 rounded-full mr-4">
                            <i class="fas fa-sync-alt text-xl text-orange-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Analyses en cours</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Mettre à jour le statut et saisir les résultats des analyses en cours.</p>
                    <!-- <button class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm">
                        Gérer les analyses <i class="fas fa-edit ml-2"></i>
                    </button> -->
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 p-3 rounded-full mr-4">
                            <i class="fas fa-history text-xl text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Historique des analyses</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Accéder à l'historique complet de toutes les analyses effectuées.</p>
                    <!-- <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                        Consulter l'historique <i class="fas fa-search ml-2"></i>
                    </button> -->
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-yellow-100 p-3 rounded-full mr-4">
                            <i class="fas fa-boxes text-xl text-yellow-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Gestion des stocks</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Gérer les niveaux de réactifs et autres consommables de laboratoire.</p>
                    <!-- <button class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm">
                        Voir les stocks <i class="fas fa-warehouse ml-2"></i>
                    </button> -->
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-teal-100 p-3 rounded-full mr-4">
                            <i class="fas fa-book text-xl text-teal-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Protocoles et procédures</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Consulter les protocoles et procédures d'analyse.</p>
                    <!-- <button class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm">
                        Accéder aux docs <i class="fas fa-file-alt ml-2"></i>
                    </button> -->
                </div>

                <!-- <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-red-100 p-3 rounded-full mr-4">
                            <i class="fas fa-headset text-xl text-red-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Contacter les médecins</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Faciliter la communication avec les médecins pour les clarifications.</p>
                    <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                        Envoyer un message <i class="fas fa-comment-medical ml-2"></i>
                    </button>
                </div> -->
            </div>

            <div class="mt-10 bg-red-50 border border-red-200 rounded-xl p-6" v-if="props.urgentAnalyses.length > 0 || props.stats.reactiveFaible > 0">
                <div class="flex items-center mb-4">
                    <i class="fas fa-exclamation-circle text-2xl text-red-600 mr-3"></i>
                    <h3 class="text-xl font-bold text-red-800">Alertes laboratoire</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div v-if="props.urgentAnalyses.length > 0" class="bg-white p-4 rounded-lg border border-red-100 flex items-center">
                        <i class="fas fa-clock text-red-500 mr-3"></i>
                        <div>
                            <p class="font-medium">Analyses urgentes à traiter</p>
                            <ul class="text-sm text-gray-600 list-disc pl-4">
                                <li v-for="analyse in props.urgentAnalyses" :key="analyse.id">
                                    {{ analyse.type_analyse }} pour le patient ID {{ analyse.patient_id }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div v-if="props.stats.reactiveFaible && props.stats.reactiveFaible > 0" class="bg-white p-4 rounded-lg border border-red-100 flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                        <div>
                            <p class="font-medium">Réactifs faibles</p>
                            <p class="text-sm text-gray-600">{{ props.stats.reactiveFaible }} réactifs avec niveau critique</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Les animations sont conservées */
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

