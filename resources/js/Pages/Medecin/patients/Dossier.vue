<template>
    <div>
        <div
            class="bg-white rounded-xl sm:px-6 lg:px-2 py-8 shadow-sm hover:shadow-md transition-all duration-300"
        >
            <!-- En-tête -->
            <div class="flex justify-center flex-col mx-4 mb-2">
                <div class="flex items-center gap-4 mb-4">
                    <div
                        class="bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg p-2 flex items-center justify-center mb-2 shadow-sm"
                    >
                        <fonta
                            class="text-xl text-blue-600"
                            icon="hospital-user"
                        />
                    </div>
                    <h2 class="text-2xl font-bold text-gray-600">
                        Liste des patients
                    </h2>
                </div>
                <p class="text-base text-gray-400">
                    Les patients que vous avez consulté
                </p>
            </div>

            <div class="mb-1 p-6">
                <div class="flex justify-between items-center gap-4">
                    <div class="relative flex-1">
                        <BaseInput
                            type="text"
                            v-model="search"
                            class="mt-1 pl-10 transition-all duration-300 hover:border-blue-300 focus:border-blue-500"
                            placeholder="Rechercher un nom, prenom, numero ..."
                        />
                        <div
                            class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none"
                        >
                            <fonta
                                class="fas fa-search text-blue-500 transition-transform duration-300 hover:scale-110"
                                icon="magnifying-glass"
                            />
                        </div>
                    </div>

                    <!-- Sélecteur de tri - Remplacer par dropdown Ant Design -->
                    <div class="flex items-center justify-end">
                        <SortDropdown
                            :sort-options="sortOptions"
                            :current-sort="String(currentSort)"
                            route-name="patient.dossier"
                            :additional-params="{ search: search.value }"
                            @sort-changed="handleSortChanged"
                        />
                    </div>
                </div>
            </div>

            <!-- Table Container avec scroll horizontal -->
            <div class="mx-4">
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <table
                            class="min-w-full divide-y divide-gray-200/60 rounded-lg overflow-hidden shadow-sm"
                        >
                            <thead
                                class="bg-gradient-to-br from-blue-500 to-blue-600 text-white"
                            >
                                <tr>
                                    <th
                                        scope="col"
                                        class="py-2.5 pl-4 text-start text-sm font-bold sm:pl-6"
                                    >
                                        Patient
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-4 py-2.5 text-start text-sm lg:table-cell hidden font-bold"
                                    >
                                        Numéro
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-4 py-2.5 text-start text-sm font-bold hidden xl:table-cell"
                                    >
                                        Societe
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-4 py-2.5 text-start text-sm hidden md:table-cell font-bold"
                                    >
                                        Dernière consultation
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-4 py-2.5 text-start text-sm font-bold"
                                    >
                                        Nombre de consultations
                                    </th>
                                    <th
                                        scope="col"
                                        class="relative py-2.5 pl-3"
                                    >
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200/60 bg-white">
                                <tr
                                    v-for="(patient, index) in patients.data"
                                    :key="index"
                                    :class="
                                        index % 2 === 0
                                            ? 'bg-white'
                                            : 'bg-gray-50/80'
                                    "
                                    class="hover:bg-blue-50/50 transition-colors duration-300"
                                >
                                    <td
                                        class="whitespace-nowrap text-start py-2 pl-4 text-sm"
                                    >
                                        <div class="flex items-center">
                                            <div
                                                class="h-10 w-10 flex-shrink-0"
                                            >
                                                <span
                                                    class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-300 to-blue-400 shadow-sm transform transition-transform duration-300 hover:scale-110"
                                                >
                                                    <span
                                                        class="font-medium leading-none text-white"
                                                    >
                                                        {{
                                                            getInitials(
                                                                patient.nom,
                                                                patient.prenom
                                                            )
                                                        }}
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="ml-4 flex flex-col">
                                                <div
                                                    class="font-bold text-gray-600 gap-1"
                                                >
                                                    {{ patient.nom }}
                                                    {{ patient.prenom }}
                                                </div>
                                                <div
                                                    class="text-xs text-gray-400"
                                                >
                                                    {{ patient.telephone }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-3 py-2 text-start hidden lg:table-cell text-sm"
                                    >
                                        <span
                                            class="p-1 bg-green-50/80 rounded-full text-green-600 text-xs border border-green-600/60"
                                            >{{ patient.numero }}</span
                                        >
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-3 py-2 text-start hidden xl:table-cell font-medium text-gray-500"
                                    >
                                        {{ patient.societe?.nom || "N/A" }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-3 py-2 text-start text-sm hidden md:table-cell text-gray-500"
                                    >
                                        {{
                                            patient.consultations[0]
                                                ? formatDateLongue(
                                                      patient.consultations[0]
                                                          .date_consultation
                                                  )
                                                : "Aucune"
                                        }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-3 py-2 text-start text-md text-gray-500"
                                    >
                                        {{ patient.consultations.length }}
                                    </td>
                                    <td
                                        class="relative whitespace-nowrap py-2 text-start text-sm font-medium"
                                    >
                                        <button
                                            @click="viewDossier(patient)"
                                            class="inline-flex items-center gap-2 px-2 py-1 rounded-full bg-gradient-to-br from-blue-50 to-blue-100/80 text-blue-600 font-semibold hover:from-blue-100 hover:to-blue-200/80 hover:scale-105 transition-all duration-300 border border-blue-200/60"
                                        >
                                            <fonta
                                                icon="folder-open"
                                                class="text-blue-500"
                                            />
                                            <span>Dossier</span>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="patients.data.length > 0 && patients.links.length > 3">
                <div class="flex flex-wrap mt-6 items-center justify-end">
                    <div
                        v-for="(link, linkIndex) in patients.links"
                        :key="linkIndex"
                    >
                        <div
                            v-if="link.url === null"
                            class="mr-1 mb-1 shadow-sm transition-colors duration-300 px-4 py-2 leading-4 text-gray-400 border border-gray-300/60 rounded"
                            v-html="link.label"
                        ></div>
                        <Link
                            v-else
                            :preserve-state="true"
                            :preserve-scroll="true"
                            class="mr-1 shadow-sm transition-all duration-300 hover:scale-105 mb-1 px-4 py-2 text-sm leading-4 border border-gray-300/60 rounded hover:bg-blue-500 hover:text-white focus:border-blue-500 inline-block focus:text-blue-500"
                            :class="{
                                'bg-gradient-to-br from-blue-500 to-blue-600 !text-white border-none':
                                    link.active,
                            }"
                            :href="link.url"
                            @click.prevent="navigateToPage(link.url)"
                        >
                            <span v-html="link.label"></span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import BaseInput from "@/Components/BaseInput.vue";
import SortDropdown from "@/Components/SortDropdown.vue";
import TestLayout from "@/Layouts/TestLayout.vue";
import {
    ArrowDownOutlined,
    ArrowUpOutlined,
    CalendarOutlined,
    SortAscendingOutlined,
    SortDescendingOutlined,
} from "@ant-design/icons-vue";
import { Link, router } from "@inertiajs/vue3";
import { debounce } from "lodash";
import { ref, watch } from "vue";

defineOptions({
    layout: TestLayout,
});

const props = defineProps({
    patients: Object,
    filters: Object,
});

const search = ref(props.filters?.search || "");
const currentSort = ref(props.filters?.sort || "consultations_desc");

// Options de tri disponibles
const sortOptions = [
    { value: "name_asc", label: "Nom (A-Z)", icon: SortAscendingOutlined },
    { value: "name_desc", label: "Nom (Z-A)", icon: SortDescendingOutlined },
    {
        value: "date_asc",
        label: "Consultation plus ancienne",
        icon: CalendarOutlined,
    },
    {
        value: "date_desc",
        label: "Consultation plus recente",
        icon: CalendarOutlined,
    },
    {
        value: "consultations_asc",
        label: "Nombre de consultations (croissant)",
        icon: ArrowUpOutlined,
    },
    {
        value: "consultations_desc",
        label: "Nombre de consultations (décroissant)",
        icon: ArrowDownOutlined,
    },
];

// Méthode unique pour gérer les requêtes
const updateResults = debounce(() => {
    router.get(
        route("patient.dossier"),
        {
            search: search.value,
            sort: currentSort.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
}, 300); // Debounce de 300ms

// Watcher unifié
watch([search, currentSort], () => {
    updateResults();
});

// Fonction pour obtenir les initiales
const getInitials = (nom, prenom) => {
    return `${nom.charAt(0)}${prenom.charAt(0)}`.toUpperCase();
};

// Fonction pour formater la date
const formatDateLongue = (date) => {
    return new Date(date).toLocaleDateString("fr-FR", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

// Fonction pour voir le dossier d'un patient
const viewDossier = (patient) => {
    router.get(route("patient.dossier.details", { id: patient.id }));
};

// Fonction pour gérer le changement de tri
const handleSortChanged = (value) => {
    currentSort.value = value;
    updateResults();
};

// Fonction pour naviguer à une page donnée
const navigateToPage = (url) => {
    router.get(url, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        data: {
            search: search.value,
            sort: currentSort.value,
        },
    });
};
</script>

<style scoped>
/* Animation de pulsation pour les badges */
@keyframes pulse-green {
    0% {
        box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.7);
    }
    70% {
        box-shadow: 0 0 0 6px rgba(74, 222, 128, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(74, 222, 128, 0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateX(-5px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.3s ease-in-out;
}

/* Améliorations visuelles */
.group:hover .group-hover\:rotate-180 {
    transform: rotate(180deg);
}

.group:hover .group-hover\:border-blue-200 {
    border-color: rgba(191, 219, 254, 1);
}

.group:hover .group-hover\:text-blue-500,
.group:hover .group-hover\:text-blue-600 {
    transition-delay: 0.05s;
}

.group:hover .group-hover\:scale-110 {
    transition-delay: 0.05s;
}
</style>
