<template>
    <div class="px-2 py-6 bg-white rounded-lg shadow">
        <!-- Entête avec filtres et bouton d'ajout -->
        <div
            class="flex flex-col md:flex-row justify-between items-center mb-6 gap-8"
        >
            <h1 class="font-bold text-2xl text-gray-600">
                Liste des Patients <fonta class="ml-4" icon="users" />
            </h1>
            <!-- Bouton d'ajout stylisé -->
            <a-button
                type="primary"
                class="flex items-center font-medium text-base gap-2 !p-5 mb-2 !bg-blue-600 hover:!bg-blue-500"
                @click="showFormModal()"
            >
                <fonta icon="user-plus" />
                <span>Ajouter nouveau patient</span>
            </a-button>
        </div>
        <div class="flex item-center flex-col  justify-center mb-4 md:flex-row gap-3">
            <!-- Input de recherche avec a-input -->
            <div class="relative space-y-2 w-full md:w-auto">
                <div class="relative">
                    <input
                        type="text "
                        Class="
                w-full px-3 py-2 border border-gray-300 outline-none focus:outline-none transition-colors duration-500 rounded-md focus:!border-blue-600 focus:ring-1 focus:!ring-blue-600 placeholder-gray-500 peer pr-10"
                        Placeholder="Rechercher..."
                    />

                    <fonta
                        icon="magnifying-glass"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"
                    />
                </div>
            </div>
            <div class="relative space-y-2 w-full md:w-auto">
                <div class="relative">
                    <input
                        type="text "
                        Class="
                w-full px-3 py-2 border border-gray-300 outline-none focus:outline-none transition-colors duration-500 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-600 placeholder-gray-500 peer pr-10"
                        Placeholder="Rechercher..."
                    />

                    <fonta
                        icon="magnifying-glass"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"
                    />
                </div>
            </div>
            <div class="relative space-y-2 w-full md:w-auto">
                <div class="relative">
                    <input
                        type="text "
                        Class="
                w-full px-3 py-2 border border-gray-300 outline-none focus:outline-none transition-colors duration-500 rounded-md focus:border-blue-500 focus:ring-1 focus:ring-blue-600 placeholder-gray-500 peer pr-10"
                        Placeholder="Rechercher..."
                    />

                    <fonta
                        icon="magnifying-glass"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"
                    />
                </div>
            </div>
        </div>

        <!-- Table patient -->
        <div class="flex items-center justify-center">
            <table class="min-w-[96%] divide-y divide-gray-200">
                <thead class="bg-slate-600">
                    <tr
                        class="text-sm text-white font-bold uppercase tracking-tight"
                    >
                        <th class="px-4 py-4 text-left">Numéro</th>
                        <th class="py-4 text-left">Nom Complet</th>
                        <th class="py-4 text-center">Société</th>
                        <th class="py-4 text-cent">Type</th>

                        <th class="py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="(patient, index) in patients.data"
                        :key="patient.id"
                        :class="[
                            'hover:bg-gray-100 transition-colors duration-300',
                            index % 2 === 0 ? 'bg-gray-50' : 'bg-white',
                        ]"
                    >
                        <td class="p-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ patient.numero }}
                            </div>
                        </td>
                        <td class="py-4 whitespace-nowrap text-left">
                            <div class="text-md font-bold text-gray-900">
                                {{ patient.nom }} {{ patient.prenom }}
                            </div>
                        </td>
                        <td class="py-4 whitespace-nowrap text-center">
                            <div class="text-md font-bold text-gray-900">
                                {{ patient.societe?.nom }}
                            </div>
                        </td>
                        <td class="py-4 whitespace-nowrap text-center">
                            <span
                                :class="[
                                    'px-2 py-1 text-xs font-semibold rounded-full',
                                    patient.type === 'SALARIE'
                                        ? 'bg-blue-100 text-blue-800'
                                        : 'bg-green-100 text-green-800',
                                ]"
                            >
                                {{ patient.type.toUpperCase() }}
                            </span>
                        </td>

                        <td
                            class="py-4 place-items-center whitespace-nowrap text-sm font-medium"
                        >
                            <div class="flex gap-2">
                                <!-- Boutons Ant Design -->
                                <button
                                    @click="editingPatient(patient)"
                                    class="text-blue-500 hover:bg-gray-100 px-2 py-1 hover:scale-125 hover:shadow-sm transition-all duration-300 rounded-md hover:underline"
                                >
                                    <EditFilled class="!text-2xl" />
                                </button>

                                <button
                                    danger
                                    @click="handleDelete(patient.id)"
                                    class="text-red-500 hover:bg-gray-100 px-2 py-1 hover:scale-125 hover:shadow-sm transition-all duration-300 rounded-md hover:underline"
                                >
                                    <DeleteFilled class="!text-2xl" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div v-if="patients.links.length > 0">
            <div class="flex flex-wrap mt-4 items-center justify-end">
                <div
                    v-for="(link, linkIndex) in patients.links"
                    :key="linkIndex"
                >
                    <div
                        v-if="link.url === null"
                        class="mr-1 mb-1 shadow-sm transition-colors duration-300 px-4 py-3 leading-4 text-gray-400 border rounded"
                        v-html="link.label"
                    ></div>
                    <Link
                        v-else
                        preserve-scroll
                        class="mr-1 shadow-sm transition-all duration-300 hover:scale-110 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-blue-600 hover:text-white focus:border-blue-600 inline-block px-4 py-3 focus:text-blue-600"
                        :class="{ 'bg-slate-600 text-white': link.active }"
                        :href="link.url"
                    >
                        <span v-html="link.label"></span>
                    </Link>
                </div>
            </div>
        </div>

        <!-- Modal de formulaire -->
        <!-- <PatientForm
            :visible="showModal"
            :patient="selectedPatient"
            :societes="societes"
            :salaries="salaries"
            @submit="handleSubmit"
            @close="showModal = false"
        /> -->
        <a-modal
            :visible="showModal"
            :width="600"
            :footer="null"
            @cancel="handleCloseModal"
        >
            <h1 class="mb-4 text-lg font-bold text-gray-500">
                {{
                    selectedPatient
                        ? "Modifier Information"
                        : "Ajouter un Patient"
                }}
            </h1>
            <a-form
                layout="vertical"
                :model="form"
                @finish="handleSubmit(form)"
                class="!px-4"
            >
                <!-- Section Type et Référence -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <a-form-item
                        label="Type de patient"
                        name="type"
                        :rules="[
                            {
                                required: true,
                                message: 'Ce champ est obligatoire',
                            },
                        ]"
                    >
                        <a-select
                            v-model:value="form.type"
                            @change="handleTypeChange"
                            placeholder="Sélectionner un type"
                            class="rounded-lg  !border-2 transition-all duration-300 focus:!border-blue-600 focus:!ring-2 focus:!ring-blue-600"
                        >
                            <a-select-option value="SALARIE"
                                >Salarié</a-select-option
                            >
                            <a-select-option value="FAMILLE"
                                >Bénéficiaire</a-select-option
                            >
                        </a-select>
                    </a-form-item>
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-4"
                        v-if="form.type === 'SALARIE'"
                    >
                        <a-form-item
                            label="Société"
                            name="societe_id"
                            :rules="[
                                {
                                    required: true,
                                    message: 'Ce champ est obligatoire',
                                },
                            ]"
                        >
                            <a-select
                                v-model:value="form.societe_id"
                                placeholder="Sélectionner une société"
                                class="rounded-lg border-gray-300 border-1"
                            >
                                <a-select-option
                                    class="p-2"
                                    v-for="societe in societes"
                                    :key="societe.id"
                                    :value="societe.id"
                                >
                                    {{ societe.nom }}
                                </a-select-option>
                            </a-select>
                        </a-form-item>

                        <a-form-item label="Poste occupé" name="poste">
                            <a-input
                                v-model:value="form.poste"
                                class="rounded-lg border-gray-300 border-1 !p-1"
                            />
                        </a-form-item>
                    </div>

                    <a-form-item
                        v-if="form.type === 'FAMILLE'"
                        label="Salarié Référent"
                        name="parent_id"
                        :rules="[
                            {
                                required: true,
                                message: 'Ce champ est obligatoire',
                            },
                        ]"
                    >
                        <a-select
                            v-model:value="form.parent_id"
                            show-search
                            option-filter-prop="label"
                            placeholder="Rechercher un salarié..."
                            class="rounded-lg border-gray-300 border-1"
                        >
                            <a-select-option
                                v-for="salarie in salaries"
                                :key="salarie.id"
                                :value="salarie.id"
                                :label="`${salarie.nom} ${salarie.prenom}`"
                            >
                                {{ salarie.nom }} {{ salarie.prenom }} ({{
                                    salarie.numero
                                }})
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </div>

                <!-- Informations de base -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a-form-item
                        label="Nom"
                        name="nom"
                        :rules="[
                            {
                                required: true,
                                message: 'Ce champ est obligatoire',
                            },
                        ]"
                    >
                        <a-input
                            v-model:value="form.nom"
                            class="rounded-lg border-gray-300 border-1"
                        />
                    </a-form-item>

                    <a-form-item
                        label="Prénom"
                        name="prenom"
                        :rules="[
                            {
                                required: true,
                                message: 'Ce champ est obligatoire',
                            },
                        ]"
                    >
                        <a-input
                            v-model:value="form.prenom"
                            class="rounded-lg border-gray-300 border-1"
                        />
                    </a-form-item>
                </div>

                <!-- Informations supplémentaires -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a-form-item
                        label="Date de naissance"
                        name="date_naissance"
                        :rules="[
                            {
                                required: true,
                                message: 'Ce champ est obligatoire',
                            },
                        ]"
                    >
                        <a-date-picker
                            v-model:value="form.date_naissance"
                            format="YYYY-MM-DD"
                            class="rounded-lg !w-full hover:border-blue-600 transition-colors duration-300 border-gray-300 border-2 p-2"
                        />
                    </a-form-item>

                    <a-form-item
                        label="Adresse"
                        name="adresse"
                        :rules="[
                            {
                                required: true,
                                message: 'Ce champ est obligatoire',
                            },
                        ]"
                    >
                        <a-input
                            v-model:value="form.adresse"
                            class="rounded-lg border-gray-300 border-1"
                        />
                    </a-form-item>
                </div>
                <a-form-item
                    label="Lien de parenté"
                    name="lien_parente"
                    v-if="form.type === 'FAMILLE'"
                    :rules="[
                        { required: true, message: 'Ce champ est obligatoire' },
                    ]"
                >
                    <a-input
                        v-model:value="form.lien_parente"
                        class="rounded-lg border-gray-300 border-1"
                    />
                </a-form-item>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a-form-item
                        label="Téléphone"
                        name="telephone"
                        :rules="[
                            {
                                required: false,
                                message:
                                    'Veuillez entrer un numéro de téléphone valide',
                                pattern: /^[0-9]{10}$/,
                            },
                        ]"
                    >
                        <a-input
                            v-model:value="form.telephone"
                            class="rounded-lg border-gray-300 border-1"
                        />
                    </a-form-item>

                    <a-form-item
                        label="Email"
                        name="email"
                        :rules="[
                            {
                                required: false,
                                message: 'Veuillez entrer un email valide',
                            },
                        ]"
                    >
                        <a-input
                            v-model:value="form.email"
                            class="rounded-lg border-gray-300 border-1"
                        />
                    </a-form-item>
                </div>

                <!-- Informations société -->

                <!-- Actions -->
                <div class="flex justify-end gap-4 mt-6">
                    <a-button @click="handleCloseModal">Annuler</a-button>
                    <a-button
                        type="primary"
                        html-type="submit"
                        :loading="processing"
                    >
                        {{ patient ? "Modifier" : "Créer" }}
                    </a-button>
                </div>
            </a-form>
        </a-modal>
    </div>
</template>

<script setup>
import TestLayout from "@/Layouts/TestLayout.vue";

import { DeleteFilled, EditFilled } from "@ant-design/icons-vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import { defineOptions, ref } from "vue";
import { message } from "ant-design-vue";
defineOptions({
    layout: TestLayout,
});
const props = defineProps({
    patients: Object,
    societes: Array,
    salaries: Array,
});
const form = useForm({
    type: "SALARIE",
    parent_id: null,
    societe_id: null,
    lien_parente: "",
    nom: "",
    prenom: "",
    date_naissance: null,
    adresse: "",
    telephone: "",
    email: "",
    poste: "",
});
const showModal = ref(false);
const isEditing = ref(false);
const editingPatientId = ref(null);

//Fermeture du Modal
const handleCloseModal = () => {
    showModal.value = false;
    form.reset();
};
//gestion de Modif
const editingPatient = (patient) => {
    isEditing.value = true;
    editingPatientId.value = patient.id;
    form.type = patient.type;
    form.nom = patient.nom;
    form.prenom = patient.prenom;
    form.adresse = patient.adresse;
    showModal.value = true;
}
//gestion du formulaire d'Ajout
const showFormModal = () => {
    isEditing.value = false;
    form.reset();
    showModal.value = true;
};
//Soumission de donées (ajout ou modification)
const handleSubmit = () => {
    if (isEditing.value) {
        form.patch(route("admin.patient.update", editingPatientId.value), {
            onSuccess: () => {

                form.reset();
                showModal.value = false;
                message.success("Patient mise à jour avec succès");
            },
            onError: () => {
                message.error("Erreur lors de la mise à jour de la société");
            },
        });
    } else {
        form.post(route("admin.patient.store"), {
            onSuccess: () => {
                message.success("Patient ajoutée avec succès");
                form.reset();
                showModal.value = false;
            },

        });
    }
};
const handleDelete = (patient) => {
    if (confirm("Confirmer la suppression?")) {
        router.delete(route("admin.patient.delete", patient)),{preserveScroll:true,
            onSuccess: ()=>{
                message.success('patient retiré avec succes')
            },
            onError: () => {
                message.error('Une erreur est survenue lors de la suppression');
            }
        }
    }
};
</script>

<style>
.ant-select-selector {
    @apply !h-full;
}
.ant-select-selection-item {
    @apply !text-base mt-1 text-gray-500;
}

</style>
