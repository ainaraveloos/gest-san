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
                                >Tableau de bord
                            </span>
                        </div>
                        <div
                            class="ml-3 flex items-center bg-white bg-opacity-10 border border-white border-opacity-30 px-3 py-1 rounded-md shadow-sm"
                        >
                            <span
                                class="text-xs text-white font-medium tracking-wide uppercase"
                                >Administration</span
                            >
                        </div>
                    </div>
                    <p class="text-blue-100 text-sm mt-3 flex items-center">
                        <span
                            class="w-1 h-1 bg-blue-200 rounded-full mr-2 opacity-70"
                        ></span>
                        Aperçu des activités et statistiques médicales
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
        <div
            class="p-6"
            v-if="localStats && Object.keys(localStats).length > 0"
        >
            <!-- Quick Stats Cards -->
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6"
            >
                <!-- Total Patients -->
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

                <!-- Total Consultations -->
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

                <!-- Badges -->
                <div
                    class="bg-gradient-to-br from-teal-500 to-teal-700 rounded-xl shadow-xl p-6 border-0 relative overflow-hidden group hover:shadow-xl transition-all duration-300"
                >
                    <div
                        class="absolute -right-4 -top-4 opacity-10 group-hover:opacity-20 transition-opacity duration-300"
                    >
                        <IdcardOutlined class="text-white text-9xl" />
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-2 h-2 rounded-full bg-teal-200"></div>
                            <p
                                class="text-sm font-medium text-teal-100 tracking-wide uppercase"
                            >
                                Badges actifs
                            </p>
                        </div>
                        <h3
                            class="text-3xl font-bold text-white mt-1 flex items-center gap-2"
                        >
                            {{ localStats.badgesStatus?.actif || 0 }}
                            <span class="text-teal-200 text-sm font-normal"
                                >badges</span
                            >
                        </h3>
                        <div
                            class="mt-4 pt-3 border-t border-teal-400 border-opacity-30"
                        >
                            <div class="flex items-center text-teal-100">
                                <span class="text-xs flex items-center">
                                    <span
                                        class="w-2 h-2 bg-blue-300 rounded-full mr-1"
                                    ></span>
                                    {{
                                        localStats.badgesStatus?.desactive || 0
                                    }}
                                    badges désactivés
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Médecins -->
                <div
                    class="bg-gradient-to-br from-cyan-500 to-cyan-700 rounded-xl shadow-xl p-6 border-0 relative overflow-hidden group hover:shadow-xl transition-all duration-300"
                >
                    <div
                        class="absolute -right-4 -top-4 opacity-10 group-hover:opacity-20 transition-opacity duration-300"
                    >
                        <TeamOutlined class="text-white text-9xl" />
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-2 h-2 rounded-full bg-cyan-200"></div>
                            <p
                                class="text-sm font-medium text-cyan-100 tracking-wide uppercase"
                            >
                                Médecins actifs
                            </p>
                        </div>
                        <h3
                            class="text-3xl font-bold text-white mt-1 flex items-center gap-2"
                        >
                            {{ localStats.medicalRestDays?.length || 0 }}
                            <span class="text-cyan-200 text-sm font-normal"
                                >médecins</span
                            >
                        </h3>
                        <div
                            class="mt-4 pt-3 border-t border-cyan-400 border-opacity-30"
                        >
                            <div class="flex items-center text-cyan-100">
                                <a-button
                                    type="link"
                                    class="text-cyan-100 hover:text-white p-0 h-auto flex items-center text-xs"
                                    @click="
                                        router.visit(
                                            route('admin.medecins.list')
                                        )
                                    "
                                >
                                    <ArrowRightOutlined class="mr-1" />
                                    Gérer les médecins
                                </a-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Dashboard Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Monthly Trends Chart - Spanning 2 columns -->
                <div
                    class="bg-white rounded-xl shadow-md p-6 border border-gray-200 lg:col-span-2"
                >
                    <h2
                        class="text-md font-semibold text-gray-600 mb-4 flex items-center"
                    >
                        <span
                            class="w-1.5 h-5 bg-gradient-to-b from-blue-400 to-blue-600 rounded-sm mr-2"
                        ></span>
                        Tendance des consultations des 6 derniers mois
                    </h2>
                    <div class="chart-container">
                        <Line
                            :data="monthlyTrendsData"
                            :options="monthlyTrendsOptions"
                        />
                    </div>
                </div>

                <!-- Badge Status Chart -->
                <div
                    class="bg-white rounded-xl shadow-md p-6 border border-gray-200"
                >
                    <h2
                        class="text-md font-semibold text-gray-600 mb-4 flex items-center"
                    >
                        <span
                            class="w-1.5 h-5 bg-gradient-to-b from-blue-400 to-blue-600 rounded-sm mr-2"
                        ></span>
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
                                class="flex items-center bg-gray-50 px-3 py-2 rounded-lg border border-gray-100"
                            >
                                <div
                                    class="w-3 h-3 rounded-full mr-2"
                                    :style="{
                                        backgroundColor: [
                                            chartColors.blue,
                                            chartColors.lightblue,
                                        ][index % 2],
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
                    <div class="mt-4 flex justify-center">
                        <a-button
                            type="primary"
                            @click="checkExpiredBadges"
                            :loading="checkingBadges"
                            class="bg-blue-500 border-blue-600 hover:bg-blue-600 hover:border-blue-700"
                        >
                            Vérifier les badges expirés
                        </a-button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Consultation Types -->
                <div
                    class="bg-white rounded-xl shadow-md p-6 border border-gray-200"
                >
                    <div class="flex items-center justify-between mb-4">
                        <h2
                            class="text-md font-semibold text-gray-600 tracking-tight flex items-center"
                        >
                            <span
                                class="w-1.5 h-5 bg-gradient-to-b from-blue-400 to-blue-600 rounded-sm mr-2"
                            ></span>
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
                                class="flex items-center bg-gray-50 px-3 py-2 rounded-lg border border-gray-100"
                            >
                                <div
                                    class="w-3 h-3 rounded-full mr-2"
                                    :style="{
                                        backgroundColor: [
                                            chartColors.oldblue,
                                            chartColors.oldgreen,
                                        ][index % 2],
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

                <!-- Medical Follow-Up -->
                <div
                    class="bg-white rounded-xl shadow-md p-6 border border-gray-200"
                >
                    <h2
                        class="text-md font-semibold text-gray-600 mb-4 flex items-center"
                    >
                        <span
                            class="w-1.5 h-5 bg-gradient-to-b from-blue-400 to-blue-600 rounded-sm mr-2"
                        ></span>
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
                                class="flex items-center bg-gray-50 px-3 py-2 rounded-lg border border-gray-100"
                            >
                                <div
                                    class="w-3 h-3 rounded-full mr-2"
                                    :style="{
                                        backgroundColor: [
                                            chartColors.blue,
                                            chartColors.green,
                                            chartColors.teal,
                                            chartColors.cyan,
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
            </div>

            <!-- Tables Section -->
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                <!-- Recent Consultations -->
                <div
                    class="bg-white rounded-xl shadow-md p-6 border border-gray-200 xl:col-span-7"
                >
                    <div class="flex items-center justify-between mb-4">
                        <h2
                            class="text-md font-semibold text-gray-600 flex items-center"
                        >
                            <span
                                class="w-1.5 h-5 bg-gradient-to-b from-blue-400 to-blue-600 rounded-sm mr-2"
                            ></span>
                            {{ recentConsultationsTitle }}
                        </h2>
                        <a-button
                            type="default"
                            class="border border-blue-500 text-blue-600 bg-blue-50/50 hover:bg-blue-100 hover:border-blue-600 hover:text-blue-700"
                            @click="router.visit(route('admin.patient.index'))"
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
                    class="bg-white rounded-xl shadow-md p-6 border border-gray-200 xl:col-span-5"
                >
                    <div class="flex items-center justify-between mb-4">
                        <h2
                            class="text-md font-semibold text-gray-600 flex items-center"
                        >
                            <span
                                class="w-1.5 h-5 bg-gradient-to-b from-blue-400 to-blue-600 rounded-sm mr-2"
                            ></span>
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
                        >
                            <template #bodyCell="{ column, record }">
                                <template v-if="column.key === 'action'">
                                    <a-button
                                        type="default"
                                        size="small"
                                        @click="
                                            router.visit(
                                                route('admin.patient.view', {
                                                    patient: record.id,
                                                })
                                            )
                                        "
                                        class="border border-blue-400 text-blue-600 bg-blue-50/50 hover:bg-blue-100 hover:border-blue-600 flex items-center justify-center"
                                    >
                                        <span>Voir</span>
                                    </a-button>
                                </template>
                                <template
                                    v-else-if="column.key === 'consultations'"
                                >
                                    <span
                                        class="px-2.5 py-1 bg-blue-100 text-blue-700 border border-blue-200 rounded-full text-xs font-semibold"
                                    >
                                        {{ record.consultations_count }}
                                    </span>
                                </template>
                                <template v-else-if="column.key === 'name'">
                                    <div class="">
                                        <span>{{ record.nom_complet }}</span>
                                    </div>
                                </template>
                            </template>
                        </a-table>
                    </div>
                </div>
            </div>

            <!-- Medical Rest Days by Doctor -->
            <div
                class="bg-white rounded-xl shadow-md p-6 border border-gray-200 mt-6"
            >
                <h2
                    class="text-md font-semibold text-gray-600 mb-4 flex items-center"
                >
                    <span
                        class="w-1.5 h-5 bg-gradient-to-b from-blue-400 to-blue-600 rounded-sm mr-2"
                    ></span>
                    Activités médicales par médecin
                </h2>

                <div class="table-responsive">
                    <a-table
                        :columns="medicalRestColumns"
                        :data-source="localStats.medicalRestDays"
                        :pagination="false"
                        class="custom-table"
                        :scroll="{ x: true }"
                    >
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.key === 'patients'">
                                <span
                                    class="px-2.5 py-1 bg-green-100 text-green-700 border border-green-200 rounded-full text-xs font-semibold"
                                >
                                    {{ record.patients }}
                                </span>
                            </template>
                            <template v-else-if="column.key === 'totalJours'">
                                <span
                                    class="px-2.5 py-1 bg-blue-100 text-blue-700 border border-blue-200 rounded-full text-xs font-semibold"
                                >
                                    {{ record.totalJours }}
                                </span>
                            </template>
                            <template v-else-if="column.key === 'moyenne'">
                                <span
                                    class="px-2.5 py-1 bg-teal-100 text-teal-700 border border-teal-200 rounded-full text-xs font-semibold"
                                >
                                    {{ parseFloat(record.moyenne).toFixed(1) }}
                                </span>
                            </template>
                        </template>
                    </a-table>
                </div>
            </div>

            <!-- Quick Access Tools -->
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6"
            >
                <div
                    @click="router.visit(route('admin.patient.index'))"
                    class="bg-blue-50 hover:bg-blue-100 rounded-xl shadow-md transition-all duration-300 flex flex-col items-center justify-center p-6 border border-blue-200 cursor-pointer"
                >
                    <UserOutlined class="text-blue-500 text-4xl mb-3" />
                    <h3 class="text-blue-700 font-semibold">
                        Gestion des patients
                    </h3>
                    <p class="text-sm text-blue-600 mt-1 text-center">
                        Accédez à la liste des patients
                    </p>
                </div>

                <div
                    @click="router.visit(route('admin.societe.index'))"
                    class="bg-green-50 hover:bg-green-100 rounded-xl shadow-md transition-all duration-300 flex flex-col items-center justify-center p-6 border border-green-200 cursor-pointer"
                >
                    <BankOutlined class="text-green-500 text-4xl mb-3" />
                    <h3 class="text-green-700 font-semibold">Sociétés</h3>
                    <p class="text-sm text-green-600 mt-1 text-center">
                        Gérer les sociétés partenaires
                    </p>
                </div>

                <div
                    @click="router.visit(route('admin.medecins.list'))"
                    class="bg-teal-50 hover:bg-teal-100 rounded-xl shadow-md transition-all duration-300 flex flex-col items-center justify-center p-6 border border-teal-200 cursor-pointer"
                >
                    <TeamOutlined class="text-teal-500 text-4xl mb-3" />
                    <h3 class="text-teal-700 font-semibold">Équipe médicale</h3>
                    <p class="text-sm text-teal-600 mt-1 text-center">
                        Gérer les médecins
                    </p>
                </div>

                <div
                    @click="router.visit(route('admin.parametres'))"
                    class="bg-cyan-50 hover:bg-cyan-100 rounded-xl shadow-md transition-all duration-300 flex flex-col items-center justify-center p-6 border border-cyan-200 cursor-pointer"
                >
                    <SettingOutlined class="text-cyan-500 text-4xl mb-3" />
                    <h3 class="text-cyan-700 font-semibold">Paramètres</h3>
                    <p class="text-sm text-cyan-600 mt-1 text-center">
                        Configuration du système
                    </p>
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
import {
    ArrowRightOutlined,
    BankOutlined,
    CalendarOutlined,
    IdcardOutlined,
    SettingOutlined,
    TeamOutlined,
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
import { computed, onMounted, ref, watch } from "vue";
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

// Variable pour l'état de chargement de la vérification des badges
const checkingBadges = ref(false);

// Fonction pour récupérer les données filtrées
const fetchFilteredData = () => {
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

// Configurations des graphiques
const chartColors = {
    blue: "#3B82F6",
    oldblue: "rgba(147, 197, 253, 0.8)",
    oldgreen: "rgba(134, 239, 172, 0.8)",
    green: "#10B981",
    teal: "#14B8A6",
    cyan: "#06B6D4",
    lightblue: "#38BDF8",
    darkblue: "#1D4ED8",
    gray: "#d1d5db",
    red: "#38BDF8", // Remplacé le rouge par une teinte de bleu
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
            title: "Action",
            key: "action",
            align: "center",
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
                backgroundColor: [chartColors.oldblue, chartColors.oldgreen],
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
                    (key) =>
                        key === "actif" ? chartColors.blue : chartColors.red
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
                    chartColors.teal,
                    chartColors.cyan,
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
const recentConsultationsColumns = [
    {
        title: "Patient",
        dataIndex: ["patient", "nom_complet"],
        key: "patient",
        className: "font-medium text-xs text-gray-500",
    },
    {
        title: "Médecin",
        dataIndex: ["medecin", "nom_complet"],
        key: "medecin",
        className: "text-blue-600 font-medium text-xs ",
    },
    {
        title: "Date",
        dataIndex: "date_consultation",
        key: "date",
        className: "font-medium text-xs text-gray-500",
    },
];

// Colonnes pour le tableau des patients fréquents
const frequentPatientsColumns = [
    {
        title: "Patient",
        dataIndex: "nom_complet",
        key: "name",
        className: "font-medium text-xs text-gray-500",
    },
    {
        title: "Consultations",
        dataIndex: "consultations_count",
        key: "consultations",
        className: "text-center font-semibold",
        align: "center",
    },
    {
        title: "Action",
        key: "action",
        align: "center",
        className: "text-right",
    },
];

// Colonnes pour les jours de repos médicaux
const medicalRestColumns = [
    {
        title: "Médecin",
        dataIndex: "medecin",
        key: "medecin",
        className: "font-medium text-xs text-gray-500",
    },
    {
        title: "Patients",
        dataIndex: "patients",
        key: "patients",
        align: "center",
        className: "text-center",
    },
    {
        title: "Total jours de repos",
        dataIndex: "totalJours",
        key: "totalJours",
        align: "center",
        className: "text-center",
    },
    {
        title: "Moyenne par patient",
        dataIndex: "moyenne",
        key: "moyenne",
        align: "center",
        className: "text-center",
    },
];

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

// Fonction pour vérifier les badges expirés
const checkExpiredBadges = () => {
    checkingBadges.value = true;
    router.post(
        route("admin.badge.checkExpired"),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                // Actualiser les données après la vérification
                fetchFilteredData();
            },
            onFinish: () => {
                checkingBadges.value = false;
            },
        }
    );
};

// Add any necessary component setup code here
</script>

<style scoped>
/* Styles communs pour les tableaux */
.custom-table :deep(.ant-table) {
    @apply bg-transparent rounded-lg overflow-hidden;
}

.custom-table :deep(.ant-table-thead > tr > th) {
    @apply bg-blue-500 text-white font-medium  whitespace-nowrap px-4 py-2.5;
}

.custom-table :deep(.ant-table-thead > tr:first-child > th:first-child) {
    @apply rounded-tl-lg;
}

.custom-table :deep(.ant-table-thead > tr:first-child > th:last-child) {
    @apply rounded-tr-lg;
}

.custom-table :deep(.ant-table-tbody > tr > td) {
    @apply border-b border-gray-200 whitespace-nowrap px-4 py-3 transition-colors duration-150;
}

.custom-table :deep(.ant-table-tbody > tr:last-child > td:first-child) {
    @apply rounded-bl-lg;
}

.custom-table :deep(.ant-table-tbody > tr:last-child > td:last-child) {
    @apply rounded-br-lg;
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

/* Style pour les boutons d'action */
.action-btn {
    @apply px-3 py-1.5 transition-all border border-blue-400 text-blue-600 bg-blue-50/50 hover:bg-blue-100 hover:border-blue-600 hover:text-blue-700 flex items-center justify-center;
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
    @apply bg-green-600 text-white shadow-md !important;
}

.period-segmented :deep(.ant-segmented-item-label) {
    @apply py-2 px-4 text-sm font-medium;
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

    /* Tables responsives sur mobile */
    .custom-table :deep(.ant-table-tbody > tr > td),
    .custom-table :deep(.ant-table-thead > tr > th) {
        @apply px-2 py-2 text-sm;
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

/* Styles responsifs pour les cartes */
@media (max-width: 768px) {
    .text-3xl {
        @apply text-2xl;
    }

    .text-9xl {
        @apply text-8xl;
    }
}

/* Style pour les titres de sections */
h2.text-lg.font-semibold {
    @apply flex items-center border-b border-gray-100 pb-3 mb-4;
    font-family: "Inter", sans-serif;
}

h2.text-lg.font-semibold .w-1\.5 {
    @apply h-5 bg-gradient-to-b from-blue-400 to-blue-600 rounded-sm mr-2;
}

/* Transition pour les survols des tableaux */
.custom-table :deep(.ant-table-tbody > tr:hover > td) {
    @apply bg-blue-50 transition-colors duration-500;
}
</style>
