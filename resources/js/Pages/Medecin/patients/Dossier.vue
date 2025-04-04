<template>
    <div>
        <div class="bg-white rounded-md sm:px-6 lg:px-2 py-8">
            <!-- En-tête -->
            <div class="flex justify-center flex-col mx-4 mb-4">
                <div class="flex items-center gap-4 mb-6">
                    <div
                        class="bg-blue-100 rounded-lg p-2 flex items-center justify-center mb-2"
                    >
                        <fonta
                            class="text-2xl text-blue-600"
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
                <div class="relative w-full md:w-1/3">
                    <input
                        type="text"
                        v-model="search"
                        class="mt-1 block w-full pl-10 p-2 border rounded-lg border-gray-300 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                        placeholder="Rechercher un nom, prenom, numero ..."
                    />
                    <div
                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none"
                    >
                        <fonta
                            class="fas fa-search text-blue-500"
                            icon="magnifying-glass"
                        />
                    </div>
                </div>
            </div>

            <!-- Table Container avec scroll horizontal -->
            <div class="flow-root">
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full py-2 align-middle">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead>
                                <tr class="bg-blue-500 text-white">
                                    <th
                                        scope="col"
                                        class="py-4 pl-4 pr-3 text-left text-md font-semibold sm:pl-6"
                                    >
                                        Patient
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-4 py-3.5 text-left text-md lg:table-cell hidden font-semibold"
                                    >
                                        Numéro
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-4 py-3.5 text-left text-md font-semibold hidden xl:table-cell"
                                    >
                                        Societe
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-4 py-3.5 text-center text-md hidden md:table-cell font-semibold"
                                    >
                                        Dernière consultation
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-4 py-3.5 text-center text-md font-semibold cursor-pointer hover:bg-blue-600/25 duration-500 transition-colors"
                                        @click="toggleSort"
                                    >
                                        <div
                                            class="flex items-center justify-center gap-2"
                                        >
                                            <span>Nombre de consultations</span>
                                            <fonta
                                                :icon="
                                                    sortDirection === 'desc'
                                                        ? 'chevron-down'
                                                        : 'chevron-up'
                                                "
                                                class="text-xs transition-transform"
                                            />
                                        </div>
                                    </th>
                                    <th
                                        scope="col"
                                        class="relative py-3.5 pl-3 pr-4 sm:pr-6"
                                    >
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr
                                    v-for="(patient, index) in patients.data"
                                    :key="index"
                                    :class="
                                        index % 2 === 0
                                            ? 'bg-white'
                                            : 'bg-gray-50'
                                    "
                                    class="hover:bg-gray-100 transition-colors duration-300"
                                >
                                    <td
                                        class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6"
                                    >
                                        <div class="flex items-center">
                                            <div
                                                class="h-10 w-10 flex-shrink-0"
                                            >
                                                <span
                                                    class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-blue-300"
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
                                        class="whitespace-nowrap px-3 hidden lg:table-cell py-4 text-sm"
                                    >
                                        <span
                                            class="p-1 bg-green-50 rounded-full  text-green-600 text-xs border border-green-600"
                                            >{{ patient.numero }}</span
                                        >
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-3 hidden xl:table-cell py-4 font-medium text-gray-500"
                                    >
                                        {{ patient.societe?.nom || "N/A" }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-3 py-4 text-sm text-center hidden md:table-cell text-gray-500"
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
                                        class="whitespace-nowrap px-3 py-4 text-md text-center text-gray-500"
                                    >
                                        {{ patient.consultations.length }}
                                    </td>
                                    <td
                                        class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"
                                    >
                                        <button
                                            @click="viewDossier(patient)"
                                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 text-blue-600 font-semibold hover:bg-blue-100 hover:scale-110 transition-all duration-500 ease-in-out border border-blue-200"
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
            <div
                v-if="patients.data.length > 0 && patients.links.length > 3"
                class="bg-white border-t border-gray-200"
            >
                <div
                    class="flex flex-wrap px-4 py-3 items-center justify-between sm:px-6"
                >
                    <div class="flex items-center space-x-2">
                        <span class="text-base font-semibold text-gray-500">
                            Page {{ patients.current_page }} sur
                            {{ patients.last_page }}
                        </span>
                    </div>
                    <div class="flex">
                        <div
                            v-for="(link, linkIndex) in patients.links"
                            :key="linkIndex"
                        >
                            <div
                                class="flex flex-wrap mt-4 items-center justify-end"
                            >
                                <div
                                    v-if="link.url === null"
                                    class="mr-1 mb-1 shadow-sm transition-colors duration-300 px-4 py-3 leading-4 text-gray-400 border rounded"
                                    v-html="link.label"
                                ></div>
                                <Link
                                    v-else
                                    preserve-scroll
                                    class="mr-1 shadow-sm transition-all duration-300 hover:scale-110 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-green-700 hover:text-white focus:border-green-500 inline-block px-4 py-3 focus:text-green-500"
                                    :class="{
                                        'bg-blue-500 text-white': link.active,
                                    }"
                                    :href="link.url"
                                >
                                    <span v-html="link.label"></span>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import TestLayout from "@/Layouts/TestLayout.vue";
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
const sortDirection = ref("desc");

// Méthode unique pour gérer les requêtes
const updateResults = debounce(() => {
    router.get(
        route("patient.dossier"),
        {
            search: search.value,
            sort: sortDirection.value,
        },
        {
            preserveState: true,
            replace: true,
        }
    );
}, 300); // Debounce de 300ms

// Watcher unifié
watch([search, sortDirection], () => {
    updateResults();
});

// toggleSort simplifié
const toggleSort = () => {
    sortDirection.value = sortDirection.value === "desc" ? "asc" : "desc";
};

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
</script>

<style scoped></style>
