<template>
    <div class="bg-white p-6 rounded-md shadow">
        <div
            class="flex items-center justify-between mb-4 gap-4 md:flex-row flex-col"
        >
            <div>
                <h1 class="font-bold text-2xl text-gray-600">
                    Liste des Medecins
                </h1>
            </div>
        </div>
        <div class="overflow-x-auto mb-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-500 text-center">
                    <tr>
                        <th
                            class="px-4 py-4 text-left texte-center text-sm font-bold text-gray-200 uppercase tracking-wider"
                        >
                            Nom Complet
                        </th>
                        <th
                            class="px-4 py-4 text-center text-sm font-bold text-gray-200 uppercase tracking-wider"
                        >
                            email
                        </th>
                        <th
                            class="px-4 py-4 text-center text-sm font-bold text-gray-200 uppercase tracking-wider"
                        >
                            nombre de consultation
                        </th>
                        <th
                            class="px-4 py-4 text-center text-sm font-bold text-gray-200 uppercase tracking-wider"
                        >
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="(medecin, index) in medecins"
                        :key="medecin.id"
                        :class="[index % 2 === 0 ? 'bg-gray-50' : 'bg-white']"
                    >
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-lg font-medium text-gray-600">
                                {{ medecin.user.name }}
                                {{ medecin.user.prenom }}
                                <!-- Accessing User's name and prenom -->
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div
                                class="text-sm font-bold text-gray-600 text-center"
                            >
                                {{ medecin.user.email }}
                                <!-- Accessing User's email -->
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div
                                class="text-sm font-bold text-gray-600 text-center"
                            >
                                {{ medecin.consultations_count }}
                                <!-- Displaying the count of consultations -->
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div
                                class="flex gap-1 sm:flex-row flex-col justify-center items-center"
                            >
                                <button
                                    @click="editMedecin(medecin)"
                                    class="text-blue-500 hover:bg-gray-100 group px-2 py-1 hover:scale-110 overflow-hidden hover:shadow-sm transition-all duration-300 rounded-md hover:underline"
                                >
                                    <EditFilled
                                        class="text-lg group-hover:scale-125 duration-500"
                                    />
                                </button>
                                <button
                                    @click="
                                        confirmDelete(
                                            route(
                                                'admin.medecin.delete',
                                                medecin.id
                                            )
                                        )
                                    "
                                    class="text-red-500 hover:bg-gray-100 group hover:scale-110 overflow-hidden px-2 py-1 hover:shadow-sm transition-all duration-500 rounded-md hover:underline"
                                >
                                    <DeleteFilled
                                        class="text-lg group-hover:scale-125 duration-500"
                                    />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import TestLayout from "@/Layouts/TestLayout.vue";
import { DeleteFilled, EditFilled } from "@ant-design/icons-vue";
import { defineOptions, defineProps } from "vue";

defineOptions({
    layout: TestLayout,
});
defineProps({
    medecins: Array,
});
</script>

<style></style>
