<template>
    <!-- Card Liste des Sociétés -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="font-bold text-2xl text-gray-600">Liste des Societes</h1>
            </div>
            <!-- Boutton pour ouvrir le formulaire d'ajout de societe -->
            <div>
                <!-- Bouton pour ouvrir le formulaire dans une modal -->
                <a-button
                    type="primary"
                    class="flex items-center font-medium text-base  !p-5 !bg-blue-600 hover:!bg-blue-500"
                    @click="openModal"
                >
                    <AppstoreAddOutlined class="!text-lg" />
                    <span>Ajouter une société</span>
                </a-button>
            </div>
        </div>

        <div class="overflow-x-auto mb-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100 text-center ">
                    <tr >
                        <th
                            class="px-4 py-4  text-left texte-center text-md font-bold text-gray-500 uppercase tracking-wider"
                        >
                            Nom
                        </th>
                        <th
                            class="px-4 py-4 text-center   text-md font-bold text-gray-500 uppercase tracking-wider"
                        >
                            Nombre de patients
                        </th>
                        <th
                            class="px-4 py-4 text-center text-md font-bold text-gray-500 uppercase tracking-wider"
                        >
                            Date d'adhésion
                        </th>
                        <th
                            class="px-4 py-4  text-center text-md font-bold text-gray-500 uppercase tracking-wider"
                        >
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y  divide-gray-200">
                    <tr v-for="societe in societes" :key="societe.id">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-lg font-medium text-gray-900">
                                {{ societe.nom }}
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900 text-center">
                                {{ societe.patients_count }}
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap place-items-center">
                            <div class="text-md text-green-600">
                                {{ societe.date_adhesion }}
                            </div>
                        </td>
                        <td
                            class="px-4 py-4 whitespace-nowrap place-items-center text-sm text-gray-500"
                        >
                            <div class="flex space-x-4 items-center">
                                <!-- Lien de modification -->
                                <Button
                                    @click="editSociete(societe)"
                                    class="text-blue-500 hover:bg-gray-100 px-2 py-1 hover:scale-125 hover:shadow-sm transition-all duration-300 rounded-md hover:underline"
                                >
                                    <EditFilled class="!text-2xl"/>
                                </Button>
                                <!-- Bouton de suppression -->
                                <button
                                    @click="deleteSociete(societe.id)"
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
    </div>

    <!-- Modal Ant Design pour le formulaire -->
    <a-modal
        v-model:visible="showModal"
        :title="isEditing ? 'Modifier la Société' : 'Ajouter une Société'"
        @ok="submitForm"
        @cancel="handleCancel"

        cancelText="Annuler"
        :footer="null"
        class="p-4"
    >
        <div class="p-4">
            <a-form :model="form"  layout="vertical">
                <a-form-item
                    label="Nom de la Société"
                    :rules="[
                        {
                            required: true,
                            message: 'Veuillez entrer le nom de la société',
                        },
                    ]"
                    class="mb-4"
                >
                    <a-input
                        v-model:value="form.nom"
                        placeholder="Nom de la société"
                        class="rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    />
                </a-form-item>
                <a-form-item
                    label="Email"
                    :rules="[
                        {
                            required: true,
                            type: 'email',
                            message: 'Veuillez entrer un email valide',
                        },
                    ]"
                    class="mb-4"
                >
                    <a-input
                        v-model:value="form.email"
                        placeholder="Email de la société"
                        class="rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    />
                </a-form-item>

                <a-form-item
                    label="Description"
                    class="mb-4"
                >
                    <a-textarea
                        v-model:value="form.description"
                        placeholder="Description de la société"
                        :rows="4"
                        class="rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    />
                </a-form-item>
                <!-- Boutons d'action, disposés de manière responsive -->
                <div
                    class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-4"
                >
                    <a-button
                        type="default"
                        @click="handleCancel"
                        class="!bg-gray-200  hover:bg-gray-300 hover:!text-blue-600 hover:!border-blue-600 transition-colors duration-300 text-gray-800"
                    >
                        Annuler
                    </a-button>
                    <a-button
                        type="primary"
                        @click="submitForm"
                        class="!bg-blue-600 hover:!bg-blue-500 transition-colors duration-300 text-white"
                    >
                    {{ isEditing ? 'Modifier' : 'Ajouter' }}
                    </a-button>
                </div>
            </a-form>
        </div>
    </a-modal>
</template>

<script setup>
import TestLayout from "@/Layouts/TestLayout.vue";
import { HomeFilled,AppstoreAddOutlined,DeleteOutlined,EditFilled,EditOutlined,DeleteRowOutlined,DeleteFilled } from "@ant-design/icons-vue";
import { useForm,Link,router } from "@inertiajs/vue3";
import { message } from "ant-design-vue";
import { defineOptions, ref } from "vue";

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
                message.success("Société mise à jour avec succès");
                form.reset();
                showModal.value = false;
                 message.success("Société mise à jour avec succès");
            },
            onError: () => {
                message.error("Erreur lors de la mise à jour de la société");
            },
        });
    } else {
        form.post(route("admin.societe.store"), {
            onSuccess: () => {
                message.success("Société ajoutée avec succès");
                form.reset();
                showModal.value = false;
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
})
//Suppimer societe
const deleteSociete = (societe) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette société ?')) {
        router.delete(route('admin.societe.delete', societe), {
            preserveScroll: true,
            onSuccess: () => {
                message.success('Société supprimée avec succès');
            },
            onError: () => {
                message.error('Une erreur est survenue lors de la suppression');
            }
        });
    }
};

</script>

<style></style>
