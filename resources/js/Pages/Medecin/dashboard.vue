<template>
    <div class="min-h-screen">
        <!-- Header -->
        <div
            class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8 shadow-lg"
        >
            <div
                class="flex flex-col lg:flex-row justify-between items-center gap-6 relative z-10"
            >
                <div class="relative pl-3">
                    <div class="flex items-center">
                        <div class="relative">
                            <span class="text-xl font-bold text-white"
                                >Tableau de bord</span
                            >
                        </div>
                        <div
                            class="ml-3 flex items-center bg-white bg-opacity-10 border border-white border-opacity-30 px-3 py-1 rounded-md shadow-sm"
                        >
                            <span
                                class="text-xs text-white font-medium tracking-wide uppercase"
                                >Médecin</span
                            >
                        </div>
                    </div>
                    <p class="text-indigo-100 text-sm mt-3 flex items-center">
                        <span
                            class="w-1 h-1 bg-indigo-200 rounded-full mr-2 opacity-70"
                        ></span>
                        Aperçu de vos activités et consultations médicales
                    </p>
                </div>
                <div class="w-full md:w-auto md:mx-0 mx-auto">
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
        <div class="p-6">
            <!-- Stats Cards -->
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 mb-6"
            >
                <!-- Patients Card -->
                <div
                    class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl shadow-xl p-6 border-0 relative overflow-hidden group hover:shadow-xl transition-all duration-300"
                >
                    <div
                        class="absolute -right-4 -top-4 opacity-10 group-hover:opacity-20 transition-opacity duration-300"
                    >
                        <UserOutlined class="text-white text-9xl" />
                    </div>
                    <div
                        class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-5 transition-all duration-500"
                    ></div>
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                    ></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-2 h-2 rounded-full bg-blue-200"></div>
                            <p
                                class="text-sm font-medium text-blue-100 tracking-wide uppercase"
                            >
                                Vos patients
                            </p>
                        </div>
                        <h3
                            class="text-3xl font-bold text-white mt-1 flex items-center gap-2"
                        >
                            {{ props.stats.totalPatients || 0 }}
                            <span class="text-blue-200 text-sm font-normal"
                                >patients</span
                            >
                        </h3>
                        <div
                            class="mt-4 pt-3 border-t border-blue-400 border-opacity-30"
                        >
                            <div class="flex items-center text-blue-100">
                                <fonta
                                    icon="chart-line"
                                    class="mr-2 opacity-70"
                                />
                                <span class="text-xs">Statistique globale</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Consultations Card -->
                <div
                    class="bg-gradient-to-br from-green-500 to-green-700 rounded-xl shadow-xl p-6 border-0 relative overflow-hidden group hover:shadow-xl transition-all duration-300"
                >
                    <div
                        class="absolute -right-4 -top-4 opacity-10 group-hover:opacity-20 transition-opacity duration-300"
                    >
                        <CalendarOutlined class="text-white text-9xl" />
                    </div>
                    <div
                        class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-5 transition-all duration-500"
                    ></div>
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
                                Consultations {{ periodLabel }}
                            </p>
                        </div>
                        <h3
                            class="text-3xl font-bold text-white mt-1 flex items-center gap-2"
                        >
                            {{ props.stats.totalConsultations || 0 }}
                            <span class="text-green-200 text-sm font-normal"
                                >consultations</span
                            >
                        </h3>
                        <div
                            class="mt-4 pt-3 border-t border-green-400 border-opacity-30"
                        >
                            <div class="flex items-center text-green-100">
                                <fonta
                                    icon="chart-line"
                                    class="mr-2 opacity-70"
                                />
                                <span class="text-xs"
                                    >Période sélectionnée</span
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ordonnances Card -->
                <div
                    class="bg-gradient-to-br from-teal-500 to-teal-700 rounded-xl shadow-xl p-6 border-0 relative overflow-hidden group hover:shadow-xl transition-all duration-300"
                >
                    <div
                        class="absolute -right-4 -top-4 opacity-10 group-hover:opacity-20 transition-opacity duration-300"
                    >
                        <FileDoneOutlined class="text-white text-9xl" />
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-2 h-2 rounded-full bg-teal-200"></div>
                            <p
                                class="text-sm font-medium text-teal-100 tracking-wide uppercase"
                            >
                                Ordonnances {{ periodLabel }}
                            </p>
                        </div>
                        <h3
                            class="text-3xl font-bold text-white mt-1 flex items-center gap-2"
                        >
                            {{ props.stats.totalOrdonnances || 0 }}
                            <span class="text-teal-200 text-sm font-normal"
                                >ordonnances</span
                            >
                        </h3>
                        <div
                            class="mt-4 pt-3 border-t border-teal-400 border-opacity-30"
                        >
                            <div class="flex items-center text-teal-100">
                                <fonta
                                    icon="chart-line"
                                    class="mr-2 opacity-70"
                                />
                                <span class="text-xs"
                                    >Période sélectionnée</span
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Examens Card -->
                <div
                    class="bg-gradient-to-br from-cyan-500 to-cyan-700 rounded-xl shadow-xl p-6 border-0 relative overflow-hidden group hover:shadow-xl transition-all duration-300"
                >
                    <div
                        class="absolute -right-4 -top-4 opacity-10 group-hover:opacity-20 transition-opacity duration-300"
                    >
                        <FileSearchOutlined class="text-white text-9xl" />
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-2 h-2 rounded-full bg-cyan-200"></div>
                            <p
                                class="text-sm font-medium text-cyan-100 tracking-wide uppercase"
                            >
                                Examens {{ periodLabel }}
                            </p>
                        </div>
                        <h3
                            class="text-3xl font-bold text-white mt-1 flex items-center gap-2"
                        >
                            {{ props.stats.totalExamens || 0 }}
                            <span class="text-cyan-200 text-sm font-normal"
                                >demandes</span
                            >
                        </h3>
                        <div
                            class="mt-4 pt-3 border-t border-cyan-400 border-opacity-30"
                        >
                            <div class="flex items-center text-cyan-100">
                                <fonta
                                    icon="chart-line"
                                    class="mr-2 opacity-70"
                                />
                                <span class="text-xs"
                                    >Période sélectionnée</span
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Références Card (nouveau) -->
                <div
                    class="bg-gradient-to-br from-sky-500 to-sky-700 rounded-xl shadow-xl p-6 border-0 relative overflow-hidden group hover:shadow-xl transition-all duration-300"
                >
                    <div
                        class="absolute -right-4 -top-4 opacity-10 group-hover:opacity-20 transition-opacity duration-300"
                    >
                        <SwapOutlined class="text-white text-9xl" />
                    </div>
                    <div
                        class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-5 transition-all duration-500"
                    ></div>
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-sky-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                    ></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-2 h-2 rounded-full bg-sky-200"></div>
                            <p
                                class="text-sm font-medium text-sky-100 tracking-wide uppercase"
                            >
                                Références {{ periodLabel }}
                            </p>
                        </div>
                        <h3
                            class="text-3xl font-bold text-white mt-1 flex items-center gap-2"
                        >
                            {{ props.stats.medicalFollowUp?.references || 0 }}
                            <span class="text-sky-200 text-sm font-normal"
                                >lettres</span
                            >
                        </h3>
                        <div
                            class="mt-4 pt-3 border-t border-sky-400 border-opacity-30"
                        >
                            <div class="flex items-center text-sky-100">
                                <fonta
                                    icon="chart-line"
                                    class="mr-2 opacity-70"
                                />
                                <span class="text-xs"
                                    >Période sélectionnée</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Tables -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Consultations by Type -->
                <div
                    class="bg-white rounded-xl shadow-md p-6 border border-gray-200"
                >
                    <h2
                        class="text-md font-semibold text-gray-600 mb-4 flex items-center"
                    >
                        <span
                            class="w-1.5 h-5 bg-gradient-to-b from-indigo-400 to-indigo-600 rounded-sm mr-2"
                        ></span>
                        Types de consultations {{ periodLabel }}
                    </h2>
                    <div class="chart-container">
                        <Doughnut
                            :data="consultationTypeData"
                            :options="doughnutOptions"
                        />
                    </div>
                    <div class="mt-4 flex flex-wrap justify-center gap-4">
                        <div
                            v-for="(value, key, index) in props.stats
                                .consultationsByType"
                            :key="key"
                            class="flex items-center bg-gray-50 px-3 py-2 rounded-lg border border-gray-100"
                        >
                            <div
                                class="w-3 h-3 rounded-full mr-2"
                                :style="{
                                    backgroundColor:
                                        chartColors[index % chartColors.length],
                                }"
                            ></div>
                            <span class="text-sm text-gray-600 font-medium">
                                {{
                                    key === "visite_aptitude"
                                        ? "Visite d'aptitude"
                                        : key === "maladie"
                                        ? "Consultation maladie"
                                        : key
                                }}
                                <span class="text-gray-400 ml-1"
                                    >({{ value }})</span
                                >
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Medical Follow-Up -->
                <div
                    class="bg-white rounded-xl shadow-md p-6 border border-gray-200"
                >
                    <h2
                        class="text-md font-semibold text-gray-600 mb-4 flex items-center"
                    >
                        <span
                            class="w-1.5 h-5 bg-gradient-to-b from-indigo-400 to-indigo-600 rounded-sm mr-2"
                        ></span>
                        Suivi médical {{ periodLabel }}
                    </h2>
                    <div class="chart-container">
                        <Bar
                            :data="medicalFollowUpData"
                            :options="barOptions"
                        />
                    </div>
                    <div class="mt-4 flex flex-wrap justify-center gap-4">
                        <div
                            v-for="(item, index) in medicalFollowUpItems"
                            :key="index"
                            class="flex items-center bg-gray-50 px-3 py-2 rounded-lg border border-gray-100"
                        >
                            <div
                                class="w-3 h-3 rounded-full mr-2"
                                :style="{
                                    backgroundColor:
                                        chartColors[index % chartColors.length],
                                }"
                            ></div>
                            <span class="text-sm text-gray-600 font-medium">
                                {{ item.label }}
                                <span class="text-gray-400 ml-1"
                                    >({{ item.value }})</span
                                >
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Monthly Trends -->
                <div
                    class="bg-white rounded-xl shadow-md p-6 border border-gray-200 lg:col-span-2"
                >
                    <h2
                        class="text-md font-semibold text-gray-600 mb-4 flex items-center"
                    >
                        <span
                            class="w-1.5 h-5 bg-gradient-to-b from-indigo-400 to-indigo-600 rounded-sm mr-2"
                        ></span>
                        Tendance des consultations des 6 derniers mois
                    </h2>
                    <div class="chart-container">
                        <Line
                            :data="monthlyTrendsData"
                            :options="lineOptions"
                        />
                    </div>
                </div>
            </div>

            <!-- Recent Consultations and Tools -->
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                <!-- Recent Consultations -->
                <div
                    class="bg-white rounded-xl shadow-md p-6 border border-gray-200 xl:col-span-8"
                >
                    <div class="flex items-center justify-between mb-4">
                        <h2
                            class="text-md font-semibold text-gray-600 flex items-center"
                        >
                            <span
                                class="w-1.5 h-5 bg-gradient-to-b from-indigo-400 to-indigo-600 rounded-sm mr-2"
                            ></span>
                            Consultations récentes
                        </h2>
                        <a-button
                            type="default"
                            class="border border-indigo-500 text-indigo-600 bg-indigo-50/50 hover:bg-indigo-100 hover:border-indigo-600 hover:text-indigo-700"
                            @click="
                                router.visit(
                                    route('medecin.consultation.create')
                                )
                            "
                        >
                            Nouvelle consultation
                        </a-button>
                    </div>
                    <div class="table-responsive">
                        <a-table
                            :columns="recentConsultationsColumns"
                            :data-source="props.stats.recentConsultations"
                            :pagination="false"
                            class="custom-table"
                            :scroll="{ x: true }"
                        >
                            <template #bodyCell="{ column, record }">
                                <template v-if="column.key === 'action'">
                                    <a-button
                                        type="default"
                                        size="small"
                                        @click="
                                            router.visit(
                                                route(
                                                    'patient.dossier.details',
                                                    { id: record.patient_id }
                                                )
                                            )
                                        "
                                        class="border border-indigo-400 text-indigo-600 bg-indigo-50/50 hover:bg-indigo-100 hover:border-indigo-600 flex items-center justify-center"
                                    >
                                        <span>Voir</span>
                                    </a-button>
                                </template>
                                <template v-else-if="column.key === 'type'">
                                    <span
                                        class="px-2.5 py-1 text-xs font-semibold rounded-full"
                                        :class="
                                            record.type === 'maladie'
                                                ? 'bg-blue-100 text-blue-700 border border-blue-200'
                                                : 'bg-green-100 text-green-700 border border-green-200'
                                        "
                                    >
                                        {{
                                            record.type === "maladie"
                                                ? "Maladie"
                                                : "Visite d'aptitude"
                                        }}
                                    </span>
                                </template>
                            </template>
                        </a-table>
                    </div>
                </div>

                <!-- Quick Access Tools -->
                <div
                    class="bg-white rounded-xl shadow-md p-6 border border-gray-200 xl:col-span-4"
                >
                    <h2
                        class="text-md font-semibold text-gray-600 mb-4 flex items-center"
                    >
                        <span
                            class="w-1.5 h-5 bg-gradient-to-b from-indigo-400 to-indigo-600 rounded-sm mr-2"
                        ></span>
                        Accès rapide
                    </h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div
                            @click="router.visit(route('patient.dossier'))"
                            class="p-4 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg cursor-pointer transition-all duration-300 flex flex-col items-center justify-center text-center"
                        >
                            <UserOutlined class="text-blue-500 text-2xl mb-2" />
                            <span class="text-sm font-medium text-blue-700"
                                >Dossiers patients</span
                            >
                        </div>
                        <div
                            @click="
                                router.visit(
                                    route('medecin.consultation.create')
                                )
                            "
                            class="p-4 bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg cursor-pointer transition-all duration-300 flex flex-col items-center justify-center text-center"
                        >
                            <FileDoneOutlined
                                class="text-green-500 text-2xl mb-2"
                            />
                            <span class="text-sm font-medium text-green-700"
                                >Nouvelle consultation</span
                            >
                        </div>
                        <div
                            @click="router.visit(route('medecin.parametre'))"
                            class="p-4 bg-teal-50 hover:bg-teal-100 border border-teal-200 rounded-lg cursor-pointer transition-all duration-300 flex flex-col items-center justify-center text-center"
                        >
                            <SettingOutlined
                                class="text-teal-500 text-2xl mb-2"
                            />
                            <span class="text-sm font-medium text-teal-700"
                                >Paramètres</span
                            >
                        </div>
                        <div
                            class="p-4 bg-cyan-50 hover:bg-cyan-100 border border-cyan-200 rounded-lg cursor-pointer transition-all duration-300 flex flex-col items-center justify-center text-center"
                        >
                            <FileSearchOutlined
                                class="text-cyan-500 text-2xl mb-2"
                            />
                            <span class="text-sm font-medium text-cyan-700"
                                >Recherche avancée</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import TestLayout from "@/Layouts/TestLayout.vue";
import {
    CalendarOutlined,
    FileDoneOutlined,
    FileSearchOutlined,
    SettingOutlined,
    SwapOutlined,
    UserOutlined,
} from "@ant-design/icons-vue";
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
import { computed, defineOptions, onMounted, ref, watch } from "vue";
import { Bar, Doughnut, Line } from "vue-chartjs";

ChartJS.register(
    CategoryScale,
    LinearScale,
    BarElement,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    ArcElement,
    Filler
);

defineOptions({
    layout: TestLayout,
});

// Props pour recevoir les données du backend
const props = defineProps({
    stats: {
        type: Object,
        required: true,
    },
    period: {
        type: String,
        default: "month",
    },
});

// Période sélectionnée pour le filtrage des données
const selectedPeriod = ref(props.period);
const isLoading = ref(false);

// Débogage: afficher les props dans la console au chargement
onMounted(() => {
    console.log("Dashboard médecin chargé avec période:", props.period);
    console.log("Stats initiales:", props.stats);
});

// Débogage: surveiller les changements dans les props
watch(
    () => props.period,
    (newPeriod) => {
        console.log("Props period mise à jour:", newPeriod);
        selectedPeriod.value = newPeriod;
    },
    { immediate: true }
);

watch(
    () => props.stats,
    (newStats) => {
        console.log("Stats mises à jour:", newStats);
        isLoading.value = false;
    },
    { deep: true }
);

// Label de la période pour l'affichage dans l'UI
const periodLabel = computed(() => {
    const periodMap = {
        today: "aujourd'hui",
        week: "cette semaine",
        month: "ce mois-ci",
        year: "cette année",
    };
    return periodMap[selectedPeriod.value];
});

// Configurations des graphiques
const chartColors = [
    "#3B82F6", // blue
    "#10B981", // green
    "#14B8A6", // teal
    "#06B6D4", // cyan
    "#0EA5E9", // sky
    "#0369A1", // darker blue
];

// Options pour les graphiques en anneau
const doughnutOptions = {
    responsive: true,
    maintainAspectRatio: false,
    cutout: "60%",
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
            usePointStyle: true,
            boxWidth: 8,
            boxHeight: 8,
            boxPadding: 6,
            cornerRadius: 8,
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

// Options pour les graphiques en barres
const barOptions = {
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
            cornerRadius: 8,
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

// Options pour les graphiques en ligne
const lineOptions = {
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
            cornerRadius: 8,
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

// Données pour le graphique des types de consultation
const consultationTypeData = computed(() => {
    const types = props.stats.consultationsByType || {};
    return {
        labels: Object.keys(types).map((key) =>
            key === "visite_aptitude"
                ? "Visite d'aptitude"
                : "Consultation maladie"
        ),
        datasets: [
            {
                data: Object.values(types),
                backgroundColor: chartColors.slice(
                    0,
                    Object.keys(types).length
                ),
                borderWidth: 2,
                borderColor: "#ffffff",
            },
        ],
    };
});

// Éléments pour le suivi médical
const medicalFollowUpItems = computed(() => {
    const followUp = props.stats.medicalFollowUp || {};
    return [
        { label: "Ordonnances", value: followUp.ordonnances || 0 },
        { label: "Demandes d'examens", value: followUp.examens || 0 },
        { label: "Lettres de référence", value: followUp.references || 0 },
        { label: "Certificats médicaux", value: followUp.certificats || 0 },
    ];
});

// Données pour le graphique de suivi médical
const medicalFollowUpData = computed(() => {
    const followUp = props.stats.medicalFollowUp || {};
    return {
        labels: ["Ordonnances", "Examens", "Références", "Certificats"],
        datasets: [
            {
                data: [
                    followUp.ordonnances || 0,
                    followUp.examens || 0,
                    followUp.references || 0,
                    followUp.certificats || 0,
                ],
                backgroundColor: chartColors.slice(0, 4),
                borderWidth: 0,
                borderRadius: 5,
            },
        ],
    };
});

// Données pour le graphique des tendances mensuelles
const monthlyTrendsData = computed(() => {
    const trends = props.stats.monthlyTrends || [];
    return {
        labels: trends.map((item) => item.month),
        datasets: [
            {
                label: "Consultations",
                data: trends.map((item) => item.count),
                borderColor: chartColors[0],
                backgroundColor: "rgba(59, 130, 246, 0.1)",
                tension: 0.4,
                fill: true,
            },
        ],
    };
});

// Colonnes pour le tableau des consultations récentes
const recentConsultationsColumns = [
    {
        title: "Patient",
        dataIndex: ["patient", "nom_complet"],
        key: "patient",
        className: "font-medium text-xs text-gray-500",
    },
    {
        title: "Date",
        dataIndex: "date_consultation",
        key: "date",
        className: "font-medium text-start text-xs text-gray-500",
    },
    {
        title: "Type",
        dataIndex: "type",
        key: "type",
        className: "text-start",
    },
    {
        title: "Action",
        key: "action",
        className: "text-start",
    },
];



// Observer les changements de période
watch(selectedPeriod, (newValue) => {
    // Vérification du changement pour éviter les requêtes inutiles
    if (newValue !== props.period) {
        console.log("Période changée:", newValue);
        isLoading.value = true;

        // Requête vers le backend pour obtenir les nouvelles données
        router.visit(route("medecin.dashboard"), {
            method: "get",
            data: { period: newValue },
            preserveState: true,
            preserveScroll: true,
            only: ["stats", "period"],
        });
    }
});
</script>

<style scoped>
/* Styles pour le dashboard */
.chart-container {
    @apply relative w-full;
    height: 300px;
}

/* Styles pour le sélecteur de période */
.period-segmented {
    @apply bg-gray-100 rounded-lg shadow-sm;
}

.period-segmented :deep(.ant-segmented-item) {
    @apply hover:bg-gray-100 m-0.5 rounded-md transition-all duration-500;
    min-width: 100px;
}

.period-segmented :deep(.ant-segmented-item-selected) {
    @apply bg-blue-600 text-white shadow-md !important;
}

.period-segmented :deep(.ant-segmented-item-label) {
    @apply py-2 px-4 text-sm font-medium;
}

/* Styles pour les tableaux */
.custom-table :deep(.ant-table) {
    @apply bg-transparent rounded-lg overflow-hidden;
}

.custom-table :deep(.ant-table-thead > tr > th) {
    @apply bg-blue-500 text-white font-medium border-b border-blue-400 whitespace-nowrap px-4 py-2.5;
}

.custom-table :deep(.ant-table-tbody > tr > td) {
    @apply border-b border-gray-200 whitespace-nowrap px-4 py-3 transition-colors duration-150;
}

.custom-table :deep(.ant-table-tbody > tr:hover > td) {
    @apply bg-blue-50 transition-colors duration-500;
}

/* Style alterné pour les lignes */
.custom-table :deep(.ant-table-tbody > tr:nth-child(even) > td) {
    @apply bg-gray-50;
}

.custom-table :deep(.ant-table-tbody > tr:nth-child(even):hover > td) {
    @apply bg-blue-50;
}

/* Styles pour les cartes de statistiques */
.bg-gradient-to-br {
    position: relative;
    transition: all 0.8s ease;
}

.bg-gradient-to-br:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.bg-gradient-to-br::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.15));
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
</style>
