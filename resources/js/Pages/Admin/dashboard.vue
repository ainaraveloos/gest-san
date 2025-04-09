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
                        Tableau de bord administratif
                    </h1>
                    <p class="text-blue-100 text-sm">
                        Aperçu des activités et statistiques médicales
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
                    class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl shadow-lg p-6 border border-blue-400 relative overflow-hidden group hover:shadow-xl transition-all duration-300"
                >
                    <div
                        class="absolute -right-4 -top-4 opacity-10 group-hover:opacity-20 transition-opacity duration-300"
                    >
                        <UserOutlined class="text-white text-9xl" />
                    </div>
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                    ></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-2 h-2 rounded-full bg-blue-200"></div>
                            <p
                                class="text-sm font-medium text-blue-100 tracking-wide uppercase"
                            >
                                Nombre total de patients
                            </p>
                        </div>
                        <h3
                            class="text-3xl font-bold text-white mt-1 flex items-center gap-2"
                        >
                            {{ localStats.totalPatients }}
                            <span class="text-blue-200 text-sm font-normal"
                                >patients</span
                            >
                        </h3>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-green-400 to-green-600 rounded-xl shadow-lg p-6 border border-green-400 relative overflow-hidden group hover:shadow-xl transition-all duration-300"
                >
                    <div
                        class="absolute -right-4 -top-4 opacity-10 group-hover:opacity-20 transition-opacity duration-300"
                    >
                        <CalendarOutlined class="text-white text-9xl" />
                    </div>
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-green-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                    ></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-2">
                            <div
                                class="w-2 h-2 rounded-full bg-green-200"
                            ></div>
                            <p
                                class="text-sm font-medium text-green-100 tracking-wide uppercase"
                            >
                                {{ totalConsultationsTitle }}
                            </p>
                        </div>
                        <h3
                            class="text-3xl font-bold text-white mt-1 flex items-center gap-2"
                        >
                            {{ localStats.totalConsultations }}
                            <span class="text-green-200 text-sm font-normal"
                                >consultations</span
                            >
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Charts and Tables -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Tendance des consultations -->

                <!-- Medical Follow-up Chart -->
                <div
                    class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 lg:col-span-2"
                >
                    <h2 class="text-lg font-semibold text-gray-600 mb-4">
                        {{ medicalFollowUpTitle }}
                    </h2>
                    <div class="chart-container">
                        <Bar
                            :data="medicalFollowUpData"
                            :options="medicalFollowUpOptions"
                        />
                    </div>
                    <div class="mt-4 flex flex-wrap justify-center gap-4">
                        <template
                            v-for="(item, index) in [
                                {
                                    label: 'Ordonnances',
                                    value:
                                        localStats.medicalFollowUp
                                            ?.ordonnances || 0,
                                },
                                {
                                    label: 'Demande d\'Examens',
                                    value:
                                        localStats.medicalFollowUp?.examens ||
                                        0,
                                },
                                {
                                    label: 'Lettre de Références',
                                    value:
                                        localStats.medicalFollowUp
                                            ?.references || 0,
                                },
                                {
                                    label: 'Certificats médicaux',
                                    value:
                                        localStats.medicalFollowUp
                                            ?.certificats || 0,
                                },
                            ]"
                            :key="item.label"
                        >
                            <div
                                class="flex items-center bg-gray-50 px-3 py-2 rounded-lg"
                            >
                                <div
                                    class="w-3 h-3 rounded-full mr-2"
                                    :style="{
                                        backgroundColor: [
                                            '#3B82F6',
                                            '#10B981',
                                            '#F59E0B',
                                            '#8B5CF6',
                                        ][index % 4],
                                    }"
                                ></div>
                                <span class="text-sm text-gray-600 font-medium"
                                    >{{ item.label }}
                                    <span class="text-gray-400 ml-1"
                                        >({{ item.value }})</span
                                    ></span
                                >
                            </div>
                        </template>
                    </div>
                </div>
                <!-- Main Chart -->
                <div
                    class="bg-white rounded-xl shadow-sm p-6 border border-gray-100"
                >
                    <div class="flex items-center justify-between mb-4">
                        <h2
                            class="text-lg font-semibold text-gray-600 tracking-tight"
                        >
                            {{ consultationChartTitle }}
                        </h2>
                    </div>
                    <div class="chart-container">
                        <Doughnut
                            :data="consultationChartData"
                            :options="consultationOptions"
                        />
                    </div>
                    <div class="mt-4 flex flex-wrap justify-center gap-4">
                        <template
                            v-for="(
                                value, key, index
                            ) in localStats.consultationsByType"
                            :key="key"
                        >
                            <div
                                class="flex items-center bg-gray-50 px-3 py-2 rounded-lg"
                            >
                                <div
                                    class="w-3 h-3 rounded-full mr-2"
                                    :style="{
                                        backgroundColor: ['#3B82F6', '#10B981'][
                                            index % 2
                                        ],
                                    }"
                                ></div>
                                <span class="text-sm text-gray-600 font-medium"
                                    >{{
                                        key === "visite_aptitude"
                                            ? "visite d'aptitude"
                                            : key
                                    }}
                                    <span class="text-gray-400 ml-1"
                                        >({{ value }})</span
                                    ></span
                                >
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Badge Status -->
                <div
                    class="bg-white rounded-xl shadow-sm p-6 border border-gray-100"
                >
                    <h2 class="text-lg font-semibold text-gray-600 mb-4">
                        Statut des badges d'accès
                    </h2>
                    <div class="chart-container">
                        <Doughnut
                            :data="badgeChartData"
                            :options="doughnutOptions"
                        />
                    </div>
                    <div class="mt-4 flex flex-wrap justify-center gap-4">
                        <template
                            v-for="(
                                value, key, index
                            ) in localStats.badgesStatus"
                            :key="key"
                        >
                            <div
                                class="flex items-center bg-gray-50 px-3 py-2 rounded-lg"
                            >
                                <div
                                    class="w-3 h-3 rounded-full mr-2"
                                    :style="{
                                        backgroundColor: ['#3B82F6', '#10B981'][
                                            index % 2
                                        ],
                                    }"
                                ></div>
                                <span class="text-sm text-gray-600 font-medium"
                                    >{{ key }}
                                    <span class="text-gray-400 ml-1"
                                        >({{ value }})</span
                                    ></span
                                >
                            </div>
                        </template>
                    </div>
                </div>
                <div
                    class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 lg:col-span-2"
                >
                    <h2 class="text-lg font-semibold text-gray-600 mb-4">
                        Tendance des consultations des 6 derniers mois
                    </h2>
                    <div class="chart-container">
                        <Line
                            :data="monthlyTrendsData"
                            :options="monthlyTrendsOptions"
                        />
                    </div>
                </div>
                <!-- Recent Consultations and Frequent Patients -->
                <div
                    class="grid grid-cols-1 xl:grid-cols-12 gap-6 lg:col-span-2"
                >
                    <!-- Recent Consultations -->
                    <div
                        class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 xl:col-span-8"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-600">
                                {{ recentConsultationsTitle }}
                            </h2>
                            <a-button
                                type="link"
                                class="text-blue-500"
                                @click="
                                    router.visit(route('admin.patient.index'))
                                "
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
                            <h2 class="text-lg font-semibold text-gray-600">
                                Patients les plus fréquents
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

                <!-- Medical Rest Days by Doctor -->
                <div
                    class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 lg:col-span-2"
                >
                    <h2 class="text-lg font-semibold text-gray-600 mb-4">
                        Activités médicales
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
            <p class="text-gray-600">Chargement des données en cours...</p>
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
    Filler,
    Legend,
    LinearScale,
    LineElement,
    PointElement,
    Title,
    Tooltip,
} from "chart.js";
import { computed, h, onMounted, ref, watch } from "vue";
import { Bar, Doughnut, Line } from "vue-chartjs";

ChartJS.register(
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend,
    ArcElement,
    LineElement,
    PointElement,
    Filler
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
            backgroundColor: "rgba(255, 255, 255, 0.95)",
            titleColor: "#1F2937",
            bodyColor: "#1F2937",
            borderColor: "#E5E7EB",
            borderWidth: 1,
            padding: 12,
            boxPadding: 6,
            usePointStyle: true,
            titleFont: {
                size: 14,
                weight: "600",
                family: "'Inter', sans-serif",
            },
            bodyFont: {
                size: 13,
                weight: "500",
                family: "'Inter', sans-serif",
            },
            cornerRadius: 8,
            boxWidth: 8,
            boxHeight: 8,
            displayColors: true,
            callbacks: {
                label: function (context) {
                    const label = context.dataset.label || "";
                    const value = context.raw;
                    const total = context.dataset.data.reduce(
                        (a, b) => a + b,
                        0
                    );
                    const percentage = Math.round((value / total) * 100);
                    return `${label}: ${value} (${percentage}%)`;
                },
            },
        },
        legend: {
            position: "bottom",
            labels: {
                padding: 20,
                font: {
                    size: 12,
                    family: "'Inter', sans-serif",
                    weight: "500",
                },
                boxWidth: 12,
                boxHeight: 12,
                usePointStyle: true,
                pointStyle: "circle",
            },
        },
    },
};

const pieOptions = {
    ...commonChartOptions,
    plugins: {
        ...commonChartOptions.plugins,
        legend: {
            display: false,
        },
    },
};

const doughnutOptions = {
    ...pieOptions,
    cutout: "60%",
    plugins: {
        ...pieOptions.plugins,
        tooltip: {
            ...pieOptions.plugins.tooltip,
            callbacks: {
                label: (context) => {
                    const label = context.label;
                    const value = context.raw;
                    const total = context.dataset.data.reduce(
                        (a, b) => a + b,
                        0
                    );
                    const percentage = Math.round((value / total) * 100);
                    return `${label}: ${value} (${percentage}%)`;
                },
            },
        },
    },
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
            dataIndex: ["patient", "nom_complet"],
            key: "patient",
        },
        {
            title: "Médecin",
            dataIndex: ["medecin", "nom_complet"],
            key: "medecin",
        },
        { title: "Date", dataIndex: "date_consultation", key: "date" },
    ],
    frequentPatients: [
        {
            title: "Patient",
            dataIndex: "nom_complet",
            key: "name",
        },
        {
            title: "Consultations",
            dataIndex: "consultations_count",
            key: "consultations",
        },
        {
            title: "Actions",
            key: "actions",
            align: "center",
            render: (_, record) =>
                h(
                    "button",
                    {
                        class: "text-blue-500 hover:text-blue-700 cursor-pointer",
                        onClick: () =>
                            router.visit(
                                route("admin.patient.view", {
                                    patient: record.id,
                                })
                            ),
                    },
                    "Voir"
                ),
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

// Configuration spécifique pour le graphique de répartition des consultations
const consultationOptions = {
    ...commonChartOptions,
    cutout: "0%",
    plugins: {
        ...commonChartOptions.plugins,
        legend: {
            display: false,
        },
        tooltip: {
            ...commonChartOptions.plugins.tooltip,
            callbacks: {
                label: (context) => {
                    const label = context.label;
                    const value = context.raw;
                    const total = context.dataset.data.reduce(
                        (a, b) => a + b,
                        0
                    );
                    const percentage = Math.round((value / total) * 100);
                    return `${label}: ${value} (${percentage}%)`;
                },
            },
        },
    },
};

// Modifier la configuration des données pour le graphique en anneau
const consultationChartData = computed(() => {
    if (
        !localStats.value.consultationsByType ||
        Object.keys(localStats.value.consultationsByType).length === 0
    ) {
        return createEmptyDataset();
    }

    return {
        labels: Object.keys(localStats.value.consultationsByType).map((key) =>
            key === "visite_aptitude" ? "visite d'aptitude" : key
        ),
        datasets: [
            {
                data: Object.values(localStats.value.consultationsByType),
                backgroundColor: [
                    "rgba(147, 197, 253, 0.8)",
                    "rgba(134, 239, 172, 0.8)",
                ],
                borderWidth: 1,
                borderColor: "#ffffff",
            },
        ],
    };
});

// Ajout de la computed property pour le titre dynamique
const consultationChartTitle = computed(() => {
    const periodMap = {
        today: "aujourd'hui",
        week: "cette semaine",
        month: "ce mois-ci",
        year: "cette année",
    };
    return `Répartition des types de consultations ${
        periodMap[selectedPeriod.value]
    }`;
});

// Ajout de la computed property pour le titre du nombre total de consultations
const totalConsultationsTitle = computed(() => {
    const periodMap = {
        today: "aujourd'hui",
        week: "cette semaine",
        month: "ce mois-ci",
        year: "cette année",
    };
    return `Nombre total de consultations ${periodMap[selectedPeriod.value]}`;
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
                    (key) => (key === "actif" ? chartColors.blue : "#f87171")
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
            "Certificats médicaux",
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

// Configuration spécifique pour le graphique de suivi médical
const medicalFollowUpOptions = {
    responsive: true,
    maintainAspectRatio: false,
    animation: {
        duration: 1000,
        easing: "easeInOutQuart",
    },
    plugins: {
        legend: {
            display: false,
        },
        tooltip: {
            backgroundColor: "rgba(255, 255, 255, 0.95)",
            titleColor: "#1F2937",
            bodyColor: "#1F2937",
            borderColor: "#E5E7EB",
            borderWidth: 1,
            padding: 12,
            boxPadding: 6,
            usePointStyle: true,
            titleFont: {
                size: 14,
                weight: "600",
                family: "'Inter', sans-serif",
            },
            bodyFont: {
                size: 13,
                weight: "500",
                family: "'Inter', sans-serif",
            },
            cornerRadius: 8,
            boxWidth: 8,
            boxHeight: 8,
            displayColors: true,
            callbacks: {
                label: function (context) {
                    const label = context.dataset.label || "";
                    const value = context.raw;
                    return `${label}: ${value}`;
                },
            },
        },
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: {
                color: "#E5E7EB",
                drawBorder: false,
                borderDash: [5, 5],
            },
            ticks: {
                color: "#6B7280",
                font: {
                    size: 12,
                    family: "'Inter', sans-serif",
                },
            },
        },
        x: {
            grid: { display: false },
            ticks: {
                color: "#6B7280",
                font: {
                    size: 12,
                    family: "'Inter', sans-serif",
                },
            },
        },
    },
};

// Colonnes pour le tableau des dernières consultations
const recentConsultationsColumns = tableColumns.recentConsultations;

// Colonnes pour le tableau des patients fréquents
const frequentPatientsColumns = tableColumns.frequentPatients;

// Colonnes pour les jours de repos médicaux
const medicalRestColumns = tableColumns.medicalRest;

// Données pour la tendance mensuelle
const monthlyTrendsData = computed(() => {
    if (!localStats.value.monthlyTrends) {
        return createEmptyDataset();
    }

    return {
        labels: localStats.value.monthlyTrends.map((item) => item.month),
        datasets: [
            {
                label: "Nombre de consultations",
                data: localStats.value.monthlyTrends.map(
                    (item) => item.consultations
                ),
                borderColor: chartColors.blue,
                backgroundColor: "rgba(59, 130, 246, 0.1)",
                tension: 0.4,
                fill: true,
            },
        ],
    };
});

const monthlyTrendsOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false,
        },
        tooltip: {
            callbacks: {
                label: function (context) {
                    return `Consultations: ${context.raw}`;
                },
            },
        },
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: {
                color: "#E5E7EB",
                drawBorder: false,
            },
        },
        x: {
            grid: {
                display: false,
            },
        },
    },
};

// Ajout de la computed property pour le titre dynamique
const medicalFollowUpTitle = computed(() => {
    const periodMap = {
        today: "aujourd'hui",
        week: "cette semaine",
        month: "ce mois-ci",
        year: "cette année",
    };
    return `Suivi médical ${periodMap[selectedPeriod.value]}`;
});

// Ajout de la computed property pour le titre de l'historique des consultations
const recentConsultationsTitle = computed(() => {
    const periodMap = {
        today: "aujourd'hui",
        week: "cette semaine",
        month: "ce mois-ci",
        year: "cette année",
    };
    return `Historique des consultations récentes ${
        periodMap[selectedPeriod.value]
    }`;
});
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

/* Styles pour les cartes de statistiques */
.bg-gradient-to-br {
    position: relative;
    transition: all 0.8s ease;
}

.bg-gradient-to-br::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1));
    opacity: 0;
    transition: opacity 0.8s ease;
}

.bg-gradient-to-br:hover::before {
    opacity: 1;
}

/* Animation pour les icônes */
@keyframes float {
    0% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
    100% {
        transform: translateY(0);
    }
}

.absolute.-right-4.-top-4 {
    animation: float 3s ease-in-out infinite;
}

/* Styles responsifs pour les cartes */
@media (max-width: 768px) {
    .text-3xl {
        @apply text-2xl;
    }

    .text-9xl {
        @apply text-8xl;
    }
}

@media (min-width: 769px) and (max-width: 1024px) {
    .text-3xl {
        @apply text-2xl;
    }
}
</style>
