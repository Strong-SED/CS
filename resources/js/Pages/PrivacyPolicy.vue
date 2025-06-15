<script setup>
import { Head } from '@inertiajs/vue3'

// Aucune prop n'est nécessaire pour ce composant simple de politique de confidentialité,
// car le contenu est directement intégré ou pourrait être chargé via un appel API si dynamique.
</script>

<template>
    <Head title="Politique de Confidentialité" />

    <div class="px-6 py-10 min-h-screen bg-gradient-to-br from-indigo-50 to-white flex justify-center">
        <!-- Div principale du contenu, avec le style demandé -->
        <div class="w-10/12 max-w-4xl mx-auto bg-white rounded-xl shadow-xl border border-gray-200 border-l-4 border-indigo-600 p-8 md:p-12">
            <div class="text-center mb-10">
                <h1 class="text-4xl font-bold text-indigo-800 mb-2">Politique de Confidentialité</h1>
                <p class="text-lg text-gray-600">Dernière mise à jour : 15 juin 2025</p>
            </div>

            <div class="prose max-w-none text-gray-700 leading-relaxed space-y-8">
                <section>
                    <h2 class="text-2xl font-semibold text-indigo-700 mb-4">1. Introduction</h2>
                    <p>Bienvenue sur CS-Ops. Nous nous engageons à protéger la confidentialité de vos informations personnelles. Cette Politique de Confidentialité explique comment nous collectons, utilisons, divulguons et protégeons vos données lorsque vous utilisez notre plateforme de gestion de centre de santé.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-indigo-700 mb-4">2. Informations que nous collectons</h2>
                    <p>Nous collectons différents types d'informations pour fournir et améliorer nos services, notamment :</p>
                    <ul class="list-disc list-inside space-y-2 pl-4">
                        <li><strong>Informations d'identification personnelle :</strong> Nom, prénom, date de naissance, genre, adresse e-mail, numéro de téléphone, adresse postale, numéro d'identification national (NPI si applicable), etc.</li>
                        <li><strong>Informations de santé :</strong> Dossiers médicaux, historique des consultations, diagnostics, traitements prescrits, résultats d'analyses, groupe sanguin, allergies, antécédents médicaux.</li>
                        <li><strong>Informations techniques :</strong> Adresse IP, type de navigateur, système d'exploitation, pages visitées, temps passé sur la plateforme.</li>
                        <li><strong>Informations de facturation :</strong> Détails des factures, montants, statuts de paiement.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-indigo-700 mb-4">3. Comment nous utilisons vos informations</h2>
                    <p>Nous utilisons les informations collectées aux fins suivantes :</p>
                    <ul class="list-disc list-inside space-y-2 pl-4">
                        <li>Pour fournir et gérer les services de santé, y compris l'enregistrement des patients, la planification des rendez-vous et la gestion des dossiers médicaux.</li>
                        <li>Pour traiter les factures et les paiements.</li>
                        <li>Pour améliorer nos services, personnaliser votre expérience et développer de nouvelles fonctionnalités.</li>
                        <li>Pour communiquer avec vous concernant votre compte, les rendez-vous ou d'autres informations importantes.</li>
                        <li>Pour assurer la sécurité de notre plateforme et prévenir les fraudes.</li>
                        <li>Pour se conformer aux obligations légales et réglementaires.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-indigo-700 mb-4">4. Partage de vos informations</h2>
                    <p>Nous ne partageons vos informations personnelles qu'avec des tiers dans les circonstances suivantes et dans le respect strict des lois applicables :</p>
                    <ul class="list-disc list-inside space-y-2 pl-4">
                        <li>Avec le personnel médical et administratif de votre centre de santé pour les besoins de votre prise en charge.</li>
                        <li>Avec des prestataires de services tiers qui nous aident à exploiter notre plateforme (hébergement, analyse de données), sous des accords de confidentialité stricts.</li>
                        <li>Avec les autorités compétentes si la loi l'exige ou pour protéger nos droits.</li>
                        <li>Avec votre consentement explicite.</li>
                    </ul>
                    <p>Vos données de santé sont traitées avec la plus grande confidentialité et ne sont partagées que lorsque cela est strictement nécessaire pour votre traitement ou exigé par la loi.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-indigo-700 mb-4">5. Sécurité des données</h2>
                    <p>Nous mettons en œuvre des mesures de sécurité techniques et organisationnelles appropriées pour protéger vos informations personnelles contre l'accès, l'utilisation ou la divulgation non autorisés, la modification ou la destruction. Cela inclut le chiffrement, les contrôles d'accès, les sauvegardes régulières et la formation du personnel.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-indigo-700 mb-4">6. Vos droits</h2>
                    <p>Conformément à la législation en vigueur sur la protection des données, vous disposez de plusieurs droits concernant vos informations personnelles :</p>
                    <ul class="list-disc list-inside space-y-2 pl-4">
                        <li>Droit d'accès à vos données.</li>
                        <li>Droit de rectification de vos données inexactes ou incomplètes.</li>
                        <li>Droit à l'effacement de vos données ("droit à l'oubli").</li>
                        <li>Droit à la limitation du traitement de vos données.</li>
                        <li>Droit à la portabilité de vos données.</li>
                        <li>Droit d'opposition au traitement de vos données.</li>
                    </ul>
                    <p>Pour exercer ces droits, veuillez nous contacter aux coordonnées indiquées ci-dessous.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-indigo-700 mb-4">7. Modifications de cette politique</h2>
                    <p>Nous pouvons mettre à jour cette Politique de Confidentialité de temps à autre. Nous vous informerons de tout changement en publiant la nouvelle politique sur cette page et en mettant à jour la date de "Dernière mise à jour". Nous vous encourageons à consulter régulièrement cette politique pour rester informé de la manière dont nous protégeons vos informations.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-indigo-700 mb-4">8. Nous contacter</h2>
                    <p>Si vous avez des questions ou des préoccupations concernant cette Politique de Confidentialité, veuillez nous contacter à :</p>
                    <p><strong>Email :</strong> support@cs-ops.com</p>
                    <p><strong>Téléphone :</strong> +229 XX XX XX XX</p>
                    <p><strong>Adresse postale :</strong> [Adresse du centre de santé], [Ville], [Pays]</p>
                </section>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Tailwind CSS est utilisé, donc peu de CSS personnalisé est nécessaire ici. */
/* Les classes 'prose max-w-none' de Tailwind Typography plugin peuvent être utilisées
   si vous l'avez configuré pour styliser automatiquement le contenu HTML.
   Sinon, les styles pour h2, p, ul, li seront appliqués via des classes Tailwind directes. */
.prose h2 {
    /* Exemple de surcharge si besoin, sinon les classes Tailwind suffisent */
    margin-top: 1.5em; /* Espace au-dessus des titres de section */
    margin-bottom: 0.75em;
}

.prose p {
    margin-bottom: 1em;
}

.prose ul {
    margin-bottom: 1em;
}
</style>
