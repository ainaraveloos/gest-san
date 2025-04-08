<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div
            class="bg-gradient-to-r from-blue-400 to-blue-600 px-6 py-8 shadow-lg"
        >
            <div
                class="flex flex-col lg:flex-row justify-between items-center gap-6"
            >
                <div>
                    <h1 class="text-2xl font-bold text-white mb-1">
                        Tableau de bord
                    </h1>
                    <p class="text-blue-100 text-sm">
                        Aperçu de l'activité médicale
                    </p>
                </div>
                <div class="w-full md:w-auto">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2 text-white text-xl">
                            <fonta icon="calendar-check" />
                        </div>
                        <a-segmented
                            v-model:value="selectedPeriod"
                            :options="[
                                { label: 'Aujourd\'hui', value: 'today' },
                                { label: 'Cette semaine', value: 'week' },
                                { label: 'Ce mois', value: 'month' },
                                { label: 'Cette année', value: 'year' },
                            ]"
                            class="period-segmented"
                            size="small"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div
            class="p-6"
            v-if="localStats && Object.keys(localStats).length > 0"
        >
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div
                    class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl shadow-sm p-6 border border-blue-400 relative overflow-hidden"
                >
                    <div class="absolute -right-4 -top-4 opacity-10">
                        <UserOutlined class="text-white text-9xl" />
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm font-medium text-blue-100">
                            Total Patients
                        </p>
                        <h3 class="text-2xl font-bold text-white mt-1">
                            {{ localStats.totalPatients }}
                        </h3>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-green-400 to-green-600 rounded-xl shadow-sm p-6 border border-green-400 relative overflow-hidden"
                >
                    <div class="absolute -right-4 -top-4 opacity-10">
                        <CalendarOutlined class="text-white text-9xl" />
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm font-medium text-green-100">
                            Consultations
                        </p>
                        <h3 class="text-2xl font-bold text-white mt-1">
                            {{ localStats.totalConsultations }}
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Charts and Tables -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Main Chart -->
                <div
                    class="bg-white rounded-xl shadow-sm p-6 border border-gray-100"
                >
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">
                            Activité des consultations
                        </h2>
                    </div>
                    <div class="chart-container">
                        <Pie
                            :data="consultationChartData"
                            :options="pieOptions"
                        />
                    </div>
                    <div class="mt-4 flex justify-center gap-6">
                        <template
                            v-for="(
                                value, key, index
                            ) in localStats.consultationsByType"
                            :key="key"
                        >
                            <div class="flex items-center">
                                <div
                                    class="w-3 h-3 rounded-full mr-2"
                                    :style="{
                                        backgroundColor: [
                                            '#3B82F6',
                                            '#10B981',
                                            '#F59E0B',
                                            '#8B5CF6',
                                            '#EF4444',
                                        ][index % 5],
                                    }"
                                ></div>
                                <span class="text-sm text-gray-600"
                                    >{{
                                        key === "visite_aptitude"
                                            ? "visite d'aptitude"
                                            : key
                                    }}
                                    ({{ value }})</span
                                >
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Badge Status -->
                <div
                    class="bg-white rounded-xl shadow-sm p-6 border border-gray-100"
                >
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        Statut des badges
                    </h2>
                    <div class="chart-container">
                        <Doughnut
                            :data="badgeChartData"
                            :options="doughnutOptions"
                        />
                    </div>
                    <div class="mt-4 flex justify-center gap-6">
                        <template
                            v-for="(value, key) in localStats.badgesStatus"
                            :key="key"
                        >
                            <div class="flex items-center">
                                <div
                                    class="w-3 h-3 rounded-full mr-2"
                                    :style="{
                                        backgroundColor:
                                            key === 'actif'
                                                ? '#3B82F6'
                                                : '#4ade80',
                                    }"
                                ></div>
                                <span class="text-sm text-gray-600"
                                    >{{ key }} ({{ value }})</span
                                >
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Recent Consultations and Frequent Patients -->
                <div
                    class="grid grid-cols-1 xl:grid-cols-12 gap-6 lg:col-span-2"
                >
                    <!-- Recent Consultations -->
                    <div
                        class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 xl:col-span-8"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">
                                Dernières consultations
                            </h2>
                            <a-button type="link" class="text-blue-500"
                                >Voir tout</a-button
                            >
                        </div>
                        <div class="table-responsive">
                            <a-table
                                :columns="recentConsultationsColumns"
                                :data-source="localStats.recentConsultations"
                                :pagination="false"
                                class="custom-table"
                                :scroll="{ x: true }"
                            />
                        </div>
                    </div>

                    <!-- Frequent Patients -->
                    <div
                        class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 xl:col-span-4 col-span-full"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">
                                Patients fréquents
                            </h2>
                        </div>
                        <div class="table-responsive">
                            <a-table
                                :columns="frequentPatientsColumns"
                                :data-source="localStats.frequentPatients"
                                :pagination="false"
                                class="custom-table"
                                :scroll="{ x: true }"
                            />
                        </div>
                    </div>
                </div>

                <!-- Medical Follow-up Chart -->
                <div
                    class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 lg:col-span-2"
                >
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        Suivi médical
                    </h2>
                    <div class="chart-container">
                        <Bar
                            :data="medicalFollowUpData"
                            :options="chartOptions"
                        />
                    </div>
                </div>

                <!-- Medical Rest Days by Doctor -->
                <div
                    class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 lg:col-span-2"
                >
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        Jours de repos médicaux par médecin
                    </h2>

                    <div class="table-responsive">
                        <a-table
                            :columns="medicalRestColumns"
                            :data-source="localStats.medicalRestDays"
                            :pagination="false"
                            class="custom-table"
                            :scroll="{ x: true }"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="p-6 text-center">
            <p class="text-gray-600">Chargement des données...</p>
        </div>
    </div>
</template>

<script setup>
import TestLayout from "@/Layouts/TestLayout.vue";
import { CalendarOutlined, UserOutlined } from "@ant-design/icons-vue";
import { router } from "@inertiajs/vue3";
import {
    ArcElement,
    BarElement,
    CategoryScale,
    Chart as ChartJS,
    Legend,
    LinearScale,
    Title,
    Tooltip,
} from "chart.js";
import { computed, onMounted, ref, watch } from "vue";
import { Bar, Doughnut, Pie } from "vue-chartjs";

ChartJS.register(
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend,
    ArcElement
);

defineOptions({
    layout: TestLayout,
});

const props = defineProps({
    stats: {
        type: Object,
        required: true,
    },
});

// Récupérer la période sauvegardée ou utiliser la valeur par défaut
const selectedPeriod = ref(localStorage.getItem("dashboardPeriod") || "month");
const localStats = ref({});

// Fonction pour récupérer les données filtrées
const fetchFilteredData = () => {
    console.log("Fetching data for period:", selectedPeriod.value);
    // Sauvegarder la période sélectionnée
    localStorage.setItem("dashboardPeriod", selectedPeriod.value);

    router.get(
        route("admin.dashboard"),
        { period: selectedPeriod.value },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                console.log("Données reçues:", page.props.stats);
                localStats.value = page.props.stats;
            },
            onError: (error) => {
                console.error(
                    "Erreur lors de la récupération des données:",
                    error
                );
            },
        }
    );
};

// Initialiser localStats avec les props
onMounted(() => {
    console.log("Props stats:", props.stats);
    localStats.value = JSON.parse(JSON.stringify(props.stats));
    console.log("LocalStats initialisé:", localStats.value);

    // Si la période sauvegardée est différente de la période par défaut,
    // récupérer les données filtrées
    if (localStorage.getItem("dashboardPeriod") !== "month") {
        fetchFilteredData();
    }
});

// Observer les changements de période
watch(selectedPeriod, (newValue) => {
    console.log("Période changée:", newValue);
    fetchFilteredData();
});

// Configuration commune des graphiques
const commonChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    animation: {
        duration: 1000,
        easing: "easeInOutQuart",
    },
    plugins: {
        tooltip: {
            backgroundColor: "rgba(255, 255, 255, 0.9)",
            titleColor: "#1F2937",
            bodyColor: "#1F2937",
            borderColor: "#E5E7EB",
            borderWidth: 1,
            padding: 12,
            boxPadding: 6,
            usePointStyle: true,
        },
    },
};

// Configuration spécifique pour chaque type de graphique
const chartOptions = {
    ...commonChartOptions,
    plugins: {
        ...commonChartOptions.plugins,
        legend: {
            position: "bottom",
            labels: {
                padding: 20,
                font: { size: 12 },
                boxWidth: 12,
                boxHeight: 12,
            },
        },
        tooltip: {
            ...commonChartOptions.plugins.tooltip,
            callbacks: {
                label: (context) => `${context.dataset.label}: ${context.raw}`,
            },
        },
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: { color: "#E5E7EB", drawBorder: false },
            ticks: { color: "#6B7280", font: { size: 12 } },
        },
        x: {
            grid: { display: false },
            ticks: { color: "#6B7280", font: { size: 12 } },
        },
    },
};

const pieOptions = {
    ...commonChartOptions,
    plugins: {
        ...commonChartOptions.plugins,
        legend: { display: false },
        tooltip: {
            ...commonChartOptions.plugins.tooltip,
            callbacks: {
                label: (context) => {
                    const total = context.dataset.data.reduce(
                        (a, b) => a + b,
                        0
                    );
                    const percentage = Math.round((context.raw / total) * 100);
                    return `${context.label}: ${context.raw} (${percentage}%)`;
                },
            },
        },
    },
};

const doughnutOptions = {
    ...pieOptions,
    cutout: "60%",
};

// Couleurs communes pour les graphiques
const chartColors = {
    blue: "#3B82F6",
    green: "#10B981",
    yellow: "#F59E0B",
    purple: "#8B5CF6",
    red: "#EF4444",
    gray: "#d1d5db",
};

// Configuration des colonnes des tableaux
const tableColumns = {
    recentConsultations: [
        {
            title: "Patient",
            dataIndex: ["patient", "nom"],
            key: "patient",
            render: (_, record) =>
                `${record.patient.nom} ${record.patient.prenom}`,
        },
        {
            title: "Médecin",
            dataIndex: ["medecin", "nom"],
            key: "medecin",
            render: (_, record) =>
                `${record.medecin.nom} ${record.medecin.prenom}`,
        },
        { title: "Date", dataIndex: "date_consultation", key: "date" },
    ],
    frequentPatients: [
        {
            title: "Patient",
            dataIndex: "nom",
            key: "name",
            render: (_, record) => `${record.nom} ${record.prenom}`,
        },
        {
            title: "Consultations",
            dataIndex: "consultations_count",
            key: "consultations",
        },
        {
            title: "Actions",
            key: "actions",
            render: (_, record) => {
                return {
                    children: [
                        {
                            type: "a",
                            props: {
                                href: route("admin.patient.view", {
                                    patient: record.id,
                                }),
                                class: "text-blue-500 hover:text-blue-700",
                            },
                            children: "Voir",
                        },
                    ],
                };
            },
        },
    ],
    medicalRest: [
        { title: "Médecin", dataIndex: "medecin", key: "medecin" },
        {
            title: "Patients",
            dataIndex: "patients",
            key: "patients",
        },
        {
            title: "Total jours de repos",
            dataIndex: "totalJours",
            key: "totalJours",
        },
        {
            title: "Moyenne par patient",
            dataIndex: "moyenne",
            key: "moyenne",
            render: (value) => Math.round(value),
        },
    ],
};

// Données des graphiques avec gestion des cas vides
const createEmptyDataset = (label = "Aucune donnée") => ({
    labels: [label],
    datasets: [
        { data: [1], backgroundColor: [chartColors.gray], borderWidth: 0 },
    ],
});

const consultationChartData = computed(() => {
    if (
        !localStats.value.consultationsByType ||
        Object.keys(localStats.value.consultationsByType).length === 0
    ) {
        return createEmptyDataset();
    }

    const colors = Object.values(chartColors).slice(
        0,
        Object.keys(localStats.value.consultationsByType).length
    );
    return {
        labels: Object.keys(localStats.value.consultationsByType),
        datasets: [
            {
                data: Object.values(localStats.value.consultationsByType),
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: "#ffffff",
            },
        ],
    };
});

const badgeChartData = computed(() => {
    if (
        !localStats.value.badgesStatus ||
        Object.keys(localStats.value.badgesStatus).length === 0
    ) {
        return createEmptyDataset();
    }

    return {
        labels: Object.keys(localStats.value.badgesStatus),
        datasets: [
            {
                data: Object.values(localStats.value.badgesStatus),
                backgroundColor: Object.keys(localStats.value.badgesStatus).map(
                    (key) => (key === "actif" ? chartColors.blue : "#4ade80")
                ),
                borderWidth: 2,
            },
        ],
    };
});

const medicalFollowUpData = computed(() => {
    if (!localStats.value.medicalFollowUp) {
        return createEmptyDataset();
    }

    return {
        labels: [
            "Ordonnances",
            "Demande d'Examens",
            "Lettre de Références",
            "Consultations",
        ],
        datasets: [
            {
                label: "Nombre de documents",
                data: [
                    localStats.value.medicalFollowUp.ordonnances || 0,
                    localStats.value.medicalFollowUp.examens || 0,
                    localStats.value.medicalFollowUp.references || 0,
                    localStats.value.medicalFollowUp.certificats || 0,
                ],
                backgroundColor: [
                    chartColors.blue,
                    chartColors.green,
                    chartColors.yellow,
                    chartColors.purple,
                ],
                borderRadius: 5,
            },
        ],
    };
});

// Colonnes pour le tableau des dernières consultations
const recentConsultationsColumns = tableColumns.recentConsultations;

// Colonnes pour le tableau des patients fréquents
const frequentPatientsColumns = tableColumns.frequentPatients;

// Colonnes pour les jours de repos médicaux
const medicalRestColumns = tableColumns.medicalRest;
</script>

<style scoped>
/* Styles communs pour les tableaux */
.custom-table :deep(.ant-table) {
    @apply bg-transparent;
}

.custom-table :deep(.ant-table-thead > tr > th) {
    @apply bg-transparent text-gray-600 font-medium border-b border-gray-100 whitespace-nowrap;
}

.custom-table :deep(.ant-table-tbody > tr > td) {
    @apply border-b border-gray-100 whitespace-nowrap;
}

.custom-table :deep(.ant-table-tbody > tr:hover > td) {
    @apply bg-gray-50;
}

/* Styles pour le sélecteur de période */
.period-segmented {
    @apply bg-gray-100;
}

.period-segmented :deep(.ant-segmented-item) {
    @apply hover:bg-gray-100 m-0.5 rounded-md transition-all duration-300;
    min-width: 100px;
}

.period-segmented :deep(.ant-segmented-item-selected) {
    @apply bg-green-500 !text-white !shadow-md;
}

.period-segmented :deep(.ant-segmented-item-label) {
    @apply py-2 px-4 text-sm font-medium;
}

.period-segmented
    :deep(.ant-segmented-item-selected .ant-segmented-item-label) {
    @apply text-white;
}

/* Styles pour les conteneurs de graphiques */
.chart-container {
    @apply relative w-full;
    height: 300px;
}

:deep(.chartjs-render-monitor) {
    animation: none !important;
}

/* Styles responsifs */
@media (max-width: 768px) {
    .period-segmented :deep(.ant-segmented-item) {
        min-width: 80px;
    }
    .period-segmented :deep(.ant-segmented-item-label) {
        @apply text-xs px-2;
    }
    .chart-container {
        height: 250px;
    }
}

@media (min-width: 769px) and (max-width: 1024px) {
    .period-segmented :deep(.ant-segmented-item) {
        min-width: 90px;
    }
    .period-segmented :deep(.ant-segmented-item-label) {
        @apply text-sm px-3;
    }
    .chart-container {
        height: 280px;
    }
}

@media (min-width: 1025px) {
    .chart-container {
        height: 320px;
    }
}

/* Styles pour les tableaux responsifs */
.table-responsive {
    @apply overflow-x-auto;
    -webkit-overflow-scrolling: touch;
}
</style>
