<template>
    <div class="mx-auto bg-white rounded-xl shadow-lg p-8 space-y-8">
        <!-- En-tête amélioré -->
        <div class="flex justify-between items-start">
            <div class="flex items-center gap-4">
                <div
                    class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center shadow-md"
                >
                    <span class="text-2xl font-bold text-blue-600">{{
                        initials
                    }}</span>
                </div>
                <div class="space-y-2">
                    <h1 class="text-2xl font-bold text-gray-800">
                        {{ patient.nom }} {{ patient.prenom }}
                    </h1>
                    <div class="flex items-center gap-3">
                        <span
                            class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-bold"
                        >
                            {{ patient.numero }}
                        </span>
                        <span
                            v-if="patient.type === 'FAMILLE'"
                            class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm"
                        >
                            Famille
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cartes statistiques -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="bg-blue-50 p-4 rounded-xl flex items-center gap-4">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <fonta
                        icon="calendar-check"
                        class="text-blue-600 text-xl"
                    />
                </div>
                <div>
                    <p class="text-sm text-gray-500">Consultation</p>
                    <p class="text-lg font-semibold text-gray-600">
                        {{ patient.consultations_count }}
                    </p>
                </div>
            </div>
            <div class="bg-green-50 p-4 rounded-xl flex items-center gap-4">
                <div class="bg-green-100 p-3 rounded-lg">
                    <fonta icon="file-medical" class="text-green-600 text-xl" />
                </div>
                <div>
                    <p class="text-sm text-gray-500">Dossiers actifs</p>
                    <p class="text-lg font-semibold text-gray-600">
                        {{ totalDossiers ?? "N/A" }}
                    </p>
                </div>
            </div>
            <div class="bg-purple-50 p-4 rounded-xl flex items-center gap-4">
                <div class="bg-purple-100 p-3 rounded-lg">
                    <fonta icon="user-md" class="text-purple-600 text-xl" />
                </div>
                <div>
                    <p class="text-sm text-gray-500">Dernier visite</p>
                    <p class="text-lg font-semibold text-gray-600">
                        {{ formatDate(lastConsultationDate) ?? "N/A" }}
                    </p>
                </div>
            </div>
        </div>

        <a-tabs v-model:activeKey="activeKey" type="card">
            <!-- Onglet 1 - Informations -->
            <a-tab-pane key="1">
                <template #tab>
                    <span class="flex items-center gap-2">
                        <fonta icon="id-card" class="text-lg" />
                        Informations du Patient
                    </span>
                </template>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Section Principale -->
                    <div class="space-y-6">
                        <div
                            class="bg-white p-6 rounded-xl border border-gray-200"
                        >
                            <h3
                                class="text-lg font-semibold mb-4 text-blue-600"
                            >
                                État Civil
                            </h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm text-gray-500">
                                        Date de naissance
                                    </dt>
                                    <dd class="font-medium">
                                        {{ formatDate(patient.date_naissance) }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Âge</dt>
                                    <dd class="font-medium">
                                        {{
                                            calculateAge(patient.date_naissance)
                                        }}
                                        ans
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">
                                        Téléphone
                                    </dt>
                                    <dd class="font-medium">
                                        {{
                                            patient.telephone || "Non renseigné"
                                        }}
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Section Famille -->
                        <div
                            v-if="patient.type === 'FAMILLE'"
                            class="bg-white p-6 rounded-xl border border-gray-200"
                        >
                            <h3
                                class="text-lg font-semibold mb-4 text-purple-600"
                            >
                                Lien Familial
                            </h3>
                            <div class="space-y-3">
                                <p class="font-medium">
                                    {{ patient.salarie?.nom }}
                                    {{ patient.salarie?.prenom }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    Salarié référent
                                </p>
                                <p
                                    class="text-sm bg-purple-100 px-3 py-1 rounded-md w-fit"
                                >
                                    {{ patient.lien_parente }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Section Secondaire -->
                    <div class="space-y-6">
                        <div
                            class="bg-white p-6 rounded-xl border border-gray-200"
                        >
                            <h3
                                class="text-lg font-semibold mb-4 text-blue-600"
                            >
                                Coordonnées
                            </h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm text-gray-500">Email</dt>
                                    <dd class="font-medium">
                                        {{ patient.email || "Non renseigné" }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">
                                        Adresse
                                    </dt>
                                    <dd class="font-medium">
                                        {{ patient.adresse || "Non renseigné" }}
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Section Professionnelle -->
                        <div
                            v-if="patient.type === 'SALARIE'"
                            class="bg-white p-6 rounded-xl border border-gray-200"
                        >
                            <h3
                                class="text-lg font-semibold mb-4 text-blue-600"
                            >
                                Carrière
                            </h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm text-gray-500">
                                        Société
                                    </dt>
                                    <dd class="font-medium">
                                        {{ patient.societe?.nom }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Poste</dt>
                                    <dd class="font-medium">
                                        {{ patient.poste }}
                                    </dd>
                                </div>
                                <div
                                    v-if="
                                        ['CDD', 'TEMPORAIRE'].includes(
                                            patient.statut_emploi
                                        )
                                    "
                                >
                                    <dt class="text-sm text-gray-500">
                                        Fin de contrat
                                    </dt>
                                    <dd class="font-medium">
                                        {{
                                            patient.date_fin_contrat
                                                ? formatDate(
                                                      patient.date_fin_contrat
                                                  )
                                                : "Non précisé"
                                        }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </a-tab-pane>

            <!-- Onglet 2 - Constantes Vitales -->
            <a-tab-pane key="2">
                <template #tab>
                    <span class="flex items-center gap-2">
                        <fonta icon="heartbeat" class="text-lg" />
                        Constantes Vitales
                    </span>
                </template>

                <div
                    class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4"
                >
                    <div
                        class="bg-white p-4 rounded-xl border border-gray-200 transition-all"
                    >
                        <div class="text-blue-500 mb-2">
                            <fonta icon="weight" class="text-2xl" />
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Poids</p>
                        <p class="text-xl font-semibold text-gray-600">
                            {{ patient.poids ? `${patient.poids} kg` : "N/A" }}
                        </p>
                    </div>
                    <div
                        class="bg-white p-4 rounded-xl border border-gray-200 transition-all"
                    >
                        <div class="text-green-500 mb-2">
                            <fonta icon="ruler" class="text-2xl" />
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Taille</p>
                        <p class="text-xl font-semibold text-gray-600">
                            {{
                                patient.taille ? `${patient.taille} cm` : "N/A"
                            }}
                        </p>
                    </div>
                    <div
                        class="bg-white p-4 rounded-xl border border-gray-200 transition-all"
                    >
                        <div class="text-red-500 mb-2">
                            <fonta icon="heart" class="text-2xl" />
                        </div>
                        <p class="text-sm text-gray-500 mb-1">
                            Rythme cardiaque
                        </p>
                        <p class="text-xl font-semibold text-gray-600">
                            {{
                                patient.freq_card
                                    ? `${patient.freq_card} bpm`
                                    : "N/A"
                            }}
                        </p>
                    </div>
                    <div
                        class="bg-white p-4 rounded-xl border border-gray-200 transition-all"
                    >
                        <div class="text-orange-500 mb-2">
                            <fonta icon="temperature-high" class="text-2xl" />
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Température</p>
                        <p class="text-xl font-semibold text-gray-600">
                            {{
                                patient.temperature
                                    ? `${patient.temperature} °C`
                                    : "N/A"
                            }}
                        </p>
                    </div>
                </div>
            </a-tab-pane>

            <!-- Onglet 3 - Consultations -->
            <a-tab-pane key="3">
                <template #tab>
                    <span class="flex items-center gap-2">
                        <fonta icon="file-medical" class="text-lg" />
                        Consultations & Dossiers Medicaux
                    </span>
                </template>

                <div class="mt-4 space-y-4">
                    <a-collapse
                        v-model:activeKey="activeConsultations"
                        accordion
                    >
                        <a-collapse-panel
                            v-for="consultation in patient.consultations"
                            :key="consultation.id"
                            class="custom-collapse"
                        >
                            <template #header>
                                <div
                                    class="flex items-center justify-between w-full pr-4"
                                >
                                    <div>
                                        <p class="font-semibold text-green-600">
                                            {{
                                                formatDate(
                                                    consultation.created_at
                                                )
                                            }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="text-sm text-blue-600">
                                            Dr. {{ consultation.medecin?.nom }}
                                            {{ consultation.medecin?.prenom }}
                                        </span>
                                    </div>
                                </div>
                            </template>

                            <div
                                class="flex flex-wrap gap-3 p-4 bg-gray-50 rounded-lg"
                            >
                                <button
                                    v-if="consultation.ordonnance"
                                    class="flex items-center gap-1 px-2 py-2 bg-green-100 text-green-500 rounded-md text-sm font-medium transition-all duration-500 hover:shadow-md transform hover:scale-105"
                                >
                                    <fonta icon="prescription" /> Ordonnance
                                </button>
                                <button
                                    v-if="consultation.demande_examen"
                                    class="flex items-center gap-1 px-2 py-2 bg-blue-100 text-blue-500 rounded-md text-sm font-medium transition-all duration-500 hover:shadow-md transform hover:scale-105"
                                >
                                    <fonta icon="microscope" /> Examen
                                </button>
                                <button
                                    v-if="consultation.lettre_reference"
                                    class="flex items-center gap-1 px-2 py-2 bg-purple-100 text-purple-500 rounded-md text-sm font-medium transition-all duration-500 hover:shadow-md transform hover:scale-105"
                                >
                                    <fonta icon="envelope-open-text" /> Lettre
                                    de référence
                                </button>
                                <span
                                    v-if="
                                        !consultation.ordonnance &&
                                        !consultation.demande_examen &&
                                        !consultation.lettre_reference
                                    "
                                    class="text-gray-500 italic"
                                >
                                    Aucun document associé
                                </span>
                            </div>
                        </a-collapse-panel>
                    </a-collapse>
                    <div
                        v-if="patient.consultations_count === 0"
                        class="text-center text-gray-500 py-8 bg-gray-50 rounded-lg"
                    >
                        Aucune consultation enregistrée
                    </div>
                </div>
            </a-tab-pane>
        </a-tabs>
    </div>
</template>
<script setup>
import TestLayout from "@/Layouts/TestLayout.vue";
import { computed, defineOptions, defineProps, ref } from "vue";
defineOptions({
    layout: TestLayout,
});
const activeKey = ref("1");
const props = defineProps({
    patient: Object,
    totalDossiers: Number,
    lastConsultationDate: String,
});
const initials = computed(() => {
    let init = "";
    if (props.patient.nom) {
        init += props.patient.nom.charAt(0).toUpperCase();
    }
    if (props.patient.prenom) {
        init += props.patient.prenom.charAt(0).toUpperCase();
    }
    return init;
});
const formatDate = (date) => {
    return new Date(date).toLocaleDateString("fr-FR", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};
// Fonction calcul âge
const calculateAge = (dateNaissance) => {
    const birthDate = new Date(dateNaissance);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();

    if (
        monthDiff < 0 ||
        (monthDiff === 0 && today.getDate() < birthDate.getDate())
    ) {
        age--;
    }
    return age;
};
</script>

<style scsoped>
.ant-tabs-card.ant-tabs-top > .ant-tabs-nav .ant-tabs-tab {
    @apply !p-4 rounded-none bg-gray-100;
}
.ant-tabs-card.ant-tabs-top > .ant-tabs-nav .ant-tabs-tab:last-child {
    @apply !rounded-tr-3xl;
}
.ant-tabs-card.ant-tabs-top > .ant-tabs-nav .ant-tabs-tab:first-child {
    @apply !rounded-tl-3xl;
}

.ant-tabs .ant-tabs-tab-btn {
    @apply !text-base font-semibold;
}
.ant-tabs .ant-tabs-tab.ant-tabs-tab-active .ant-tabs-tab-btn {
    @apply !text-white;
}
.ant-tabs-card > .ant-tabs-nav .ant-tabs-tab-active,
:where(.css-dev-only-do-not-override-1p3hq3p).ant-tabs-card
    > div
    > .ant-tabs-nav
    .ant-tabs-tab-active {
    @apply !bg-blue-500 !text-white;
}
.ant-tabs > .ant-tabs-nav .ant-tabs-nav-list {
    @apply gap-1;
}

.custom-collapse .ant-collapse-header {
    @apply bg-white rounded-xl border border-gray-200 mb-2 hover:shadow-md !important;
    transition: all 0.3s ease;
}

.custom-collapse .ant-collapse-content {
    @apply border-t-0 rounded-b-xl !important;
}

.ant-collapse > .ant-collapse-item > .ant-collapse-header .ant-collapse-arrow {
    @apply text-gray-400 !important;
}
</style>
