<template>
    <!-- Card Liste des Sociétés -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4 gap-4 md:flex-row flex-col">
            <div>
                <h1 class="font-bold text-2xl text-gray-600">
                    Liste des Societes
                </h1>
            </div>
            <!-- Boutton pour ouvrir le formulaire d'ajout de societe -->
            <div>
                <!-- Bouton pour ouvrir le formulaire dans une modal -->
                <BaseButton
                @click="openModal"
                class="py-2"
                >
                    <span>Ajouter une société</span>
                    <AppstoreAddOutlined class="text-lg" />
                </BaseButton>
            </div>
        </div>

        <div class="overflow-x-auto mb-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-500 text-center">
                    <tr>
                        <th
                            class="px-4 py-4 text-left texte-center text-sm font-bold text-gray-200 uppercase tracking-wider"
                        >
                            Nom
                        </th>
                        <th
                            class="px-4 py-4 text-center text-sm font-bold text-gray-200 uppercase tracking-wider"
                        >
                            Nombre de patients
                        </th>
                        <th
                            class="px-4 py-4 text-center text-sm font-bold text-gray-200 uppercase tracking-wider"
                        >
                            Date d'adhésion
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
                        v-for="(societe, index) in societes"
                        :key="index"
                        :class="[index % 2 === 0 ? 'bg-gray-50' : 'bg-white']"
                    >
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-lg font-medium text-gray-900">
                                {{ societe.nom }}
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div
                                class="text-sm font-bold text-gray-900 text-center"
                            >
                                {{ societe.patients_count }}
                            </div>
                        </td>
                        <td
                            class="px-4 py-4 whitespace-nowrap place-items-center"
                        >
                            <div class="text-md text-green-600">
                                {{ societe.date_adhesion }}
                            </div>
                        </td>
                        <td
                            class="px-4 py-4 whitespace-nowrap place-items-center text-sm text-gray-500"
                        >
                            <div class="flex space-x-4 items-center">
                                <!-- Lien de modification -->
                                <button
                                    @click="editSociete(societe)"
                                    class="text-blue-500 hover:bg-gray-100 group px-2 py-1 hover:scale-110 overflow-hidden  hover:shadow-sm transition-all duration-300 rounded-md hover:underline"
                                >
                                    <EditFilled class="text-lg group-hover:scale-125 duration-500" />
                                </button>
                                <!-- Bouton de suppression -->
                                <button
                                    @click="confirmDelete(route('admin.societe.delete',societe.id))"
                                    class="text-red-500 hover:bg-gray-100 group hover:scale-110 overflow-hidden px-2 py-1  hover:shadow-sm transition-all duration-500 rounded-md hover:underline"
                                >
                                    <DeleteFilled class="text-lg group-hover:scale-125 duration-500" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Ant Design pour le formulaire -->
    <a-modal
        v-model:open="showModal"

        @ok="submitForm"
        @cancel="handleCancel"
        cancelText="Annuler"
        :footer="null"
        class="p-4"
    >
    <h1 class="mb-6 text-blue-500 font-bold text-xl text-center">{{ isEditing?'Modifier information':'Ajouter une entreprise' }}</h1>
        <div class="p-4">
            <form @submit.prevent="submitForm" layout="vertical">
                <InputLabel for="nom" value="Nom de l'entreprise" />
                <BaseInput
                    id="nom"
                    type="text"

                    v-model="form.nom"
                    required
                    autofocus
                />
                <InputError class="mt-1" :message="form.errors.nom" />

                <InputLabel for="email" value="Email" class="mt-4" />
                <BaseInput
                    id="email"
                    type="email"

                    v-model="form.email"
                    required
                    autofocus
                />
                <InputError class="mt-1" :message="form.errors.email" />

                <InputLabel
                    for="nom"
                    value="Nom de l'entreprise"
                    class="mt-4 mb-1"
                />
                <textarea
                    name="description"
                    id="description"
                    v-model="form.description"
                    class="mt-1 block w-full p-2 border rounded-lg border-gray-300 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                />
                <InputError class="mt-1" :message="form.errors.description" />

                <!-- Boutons d'action, disposés de manière responsive -->
                <div
                    class="flex flex-col mt-4 sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-4"
                >
                    <Button @click="handleCancel"


                    >
                        Annuler
                    </Button>
                    <BaseButton @click="submitForm"
                    >
                        {{ isEditing ? "Modifier" : "Ajouter" }}

                    </BaseButton>
                </div>
            </form>
        </div>
    </a-modal>
</template>

<script setup>
import BaseButton from "@/Components/BaseButton.vue";
import BaseInput from "@/Components/BaseInput.vue";
import Button from "@/Components/Button.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TestLayout from "@/Layouts/TestLayout.vue";
import {
    AppstoreAddOutlined,
    DeleteFilled,
    EditFilled,
} from "@ant-design/icons-vue";
import { router, useForm } from "@inertiajs/vue3";
import { defineOptions, ref } from "vue";
import useConfirmDialog from "@/composables/useConfirmDialog";
import Swal from "sweetalert2";
const {confirmDelete} = useConfirmDialog();
//Variable d'affichage du modal
const showModal = ref(false);
//Variable pour edition de societe
const isEditing = ref(false);
const editingSocieteId = ref(null);

// Formulaire pour ajouter une société
const form = useForm({
    nom: "",
    email: "",
    description: "",
});
//Ouvrir Modal
const openModal = () => {
    isEditing.value = false;
    form.reset();
    showModal.value = true;
};




const editSociete = (societe) => {
    isEditing.value = true;
    editingSocieteId.value = societe.id;
    form.nom = societe.nom;
    form.email = societe.email;
    form.description = societe.description;
    showModal.value = true;
};

//Fermeture du modal
const handleCancel = () => {
    form.reset();
    showModal.value = false;
};

//Formulaire
const submitForm = () => {
    if (isEditing.value) {
        form.patch(route("admin.societe.update", editingSocieteId.value), {
            onSuccess: () => {
                Swal.fire({
                            title: "Mis à jour !",
                            text: "Information du société mis à jour avec succès",
                            icon: "success",
                            confirmButtonColor: "#3b82f6",
                        });
                form.reset();
                showModal.value = false;

            },
            onError: () => {
                console.log(e)
            },
        });
    } else {
        form.post(route("admin.societe.store"), {
            onSuccess: () => {
                 Swal.fire({
                            title: "Ajout !",
                            text: "Société ajouter avec succès",
                            icon: "success",
                            confirmButtonColor: "#3b82f6",
                        });
                form.reset();
                showModal.value = false;
            },
            onError: () => {
                console.log(e)
            },
        });
    }
};
//Layout
defineOptions({
    layout: TestLayout,
});
//Props pour afficher les societes
defineProps({
    societes: {
        type: Array,
        required: true,
    },
});
</script>

<style></style>
