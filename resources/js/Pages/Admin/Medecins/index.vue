<template>
    <!-- Card Liste des Médecins -->
    <div
        class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 p-6"
    >
        <div
            class="flex items-center justify-between mb-4 gap-4 md:flex-row flex-col"
        >
            <div>
                <h1 class="font-bold text-xl text-gray-600">
                    Liste des Médecins
                </h1>
            </div>
            <!-- Sélecteur de tri -->
            <div class="flex items-center gap-4">
                <SortDropdown
                    :sort-options="sortOptions"
                    :current-sort="sortOrder"
                    route-name="admin.medecins.list"
                    @sort-changed="handleSortChanged"
                />
            </div>
        </div>

        <div class="overflow-x-auto mb-4">
            <table
                class="min-w-full divide-y divide-gray-200/60 border border-gray-100/60 rounded-lg overflow-hidden shadow-sm"
            >
                <thead
                    class="bg-gradient-to-br from-blue-500 to-blue-600 text-center"
                >
                    <tr>
                        <th
                            class="px-4 py-2.5 text-start text-sm font-bold text-white"
                        >
                            Nom Complet
                        </th>
                        <th
                            class="px-4 py-2.5 text-start sm:table-cell hidden text-sm font-bold text-white"
                        >
                            Email
                        </th>
                        <th
                            class="px-4 py-2.5 text-start text-sm font-bold text-white sm:table-cell hidden"
                        >
                            Spécialité
                        </th>
                        <th
                            class="px-4 py-2.5 text-start text-sm lg:table-cell hidden font-bold text-white"
                        >
                            Consultations
                        </th>
                        <th
                            class="px-4 py-2.5 text-start text-sm font-bold text-white"
                        >
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200/60">
                    <tr
                        v-for="(medecin, index) in medecins.data"
                        :key="medecin.id"
                        :class="[
                            index % 2 === 0 ? 'bg-gray-50/80' : 'bg-white',
                        ]"
                        class="hover:bg-blue-50/50 transition-colors duration-300"
                    >
                        <td class="px-4 py-2 whitespace-nowrap">
                            <div class="font-semibold text-sm text-gray-600">
                                {{ medecin.user.name }}
                                {{ medecin.user.prenom }}
                            </div>
                            <div
                                class="text-xs text-gray-500"
                                v-if="medecin.type"
                            >
                                {{
                                    medecin.type === "GENERALISTE"
                                        ? "Généraliste"
                                        : "Médecin d'Entreprise"
                                }}
                            </div>
                        </td>
                        <td
                            class="px-4 py-2 sm:table-cell text-start hidden whitespace-nowrap"
                        >
                            <div class="text-sm text-gray-600 font-medium">
                                {{ medecin.user.email }}
                            </div>
                        </td>
                        <td class="px-4 py-2 text-start whitespace-nowrap sm:table-cell hidden">
                            <div class="text-sm">
                                <span
                                    class="px-3 py-2 inline-flex text-xs leading-5  font-semibold rounded-full bg-gradient-to-br from-blue-100 to-blue-200 text-blue-800 border border-blue-200/60 shadow-sm"
                                >
                                    {{ medecin.specialite }}
                                </span>
                            </div>
                        </td>
                        <td
                            class="px-4 py-2 text-start lg:table-cell hidden whitespace-nowrap"
                        >
                            <div class="text-sm font-bold text-gray-600">
                                {{ medecin.consultations_count }}
                            </div>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            <!-- Version desktop -->
                            <div
                                class="hidden md:flex space-x-4 items-center text-sm text-start"
                            >
                                <!-- Bouton de modification -->
                                <button
                                    @click="editMedecin(medecin)"
                                    class="text-blue-500 hover:bg-blue-50/80 group px-2 py-1 hover:scale-105 overflow-hidden hover:shadow-sm transition-all duration-300 rounded-md"
                                >
                                    <EditFilled
                                        class="text-base group-hover:scale-110 duration-300"
                                    />
                                </button>
                                <!-- Bouton de suppression -->
                                <button
                                    @click="
                                        confirmDelete(
                                            route(
                                                'admin.medecin.delete',
                                                medecin.id
                                            )
                                        )
                                    "
                                    class="text-red-500 hover:bg-red-50/80 group hover:scale-105 overflow-hidden px-2 py-1 hover:shadow-sm transition-all duration-300 rounded-md"
                                >
                                    <DeleteFilled
                                        class="text-base group-hover:scale-110 duration-300"
                                    />
                                </button>
                            </div>

                            <!-- Version mobile avec dropdown -->
                            <div class="md:hidden flex justify-center">
                                <a-dropdown :trigger="['click']">
                                    <button
                                        class="text-blue-500 hover:bg-blue-100/80 bg-blue-50/70 group px-2 py-1 hover:scale-105 overflow-hidden hover:shadow-sm transition-all duration-300 rounded-md"
                                    >
                                        <fonta
                                            icon="ellipsis"
                                            class="text-lg text-gray-400"
                                        />
                                    </button>
                                    <template #overlay>
                                        <a-menu
                                            class="!min-w-[120px] space-y-2"
                                        >
                                            <a-menu-item
                                                @click="editMedecin(medecin)"
                                            >
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <EditFilled
                                                        class="text-blue-500 text-lg"
                                                    />
                                                    <span
                                                        class="text-blue-500 font-medium"
                                                        >Modifier</span
                                                    >
                                                </div>
                                            </a-menu-item>
                                            <a-menu-item
                                                @click="
                                                    confirmDelete(
                                                        route(
                                                            'admin.medecin.delete',
                                                            medecin.id
                                                        )
                                                    )
                                                "
                                            >
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <DeleteFilled
                                                        class="text-red-500 text-lg"
                                                    />
                                                    <span
                                                        class="text-red-500 font-medium"
                                                        >Supprimer</span
                                                    >
                                                </div>
                                            </a-menu-item>
                                        </a-menu>
                                    </template>
                                </a-dropdown>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- Pagination -->
            <div v-if="medecins.data.length > 0 && medecins.links.length > 3">
                <div class="flex flex-wrap mt-6 items-center justify-end">
                    <div
                        v-for="(link, linkIndex) in medecins.links"
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

    <!-- Modal Ant Design pour le formulaire de médecin -->
    <a-modal
        v-model:open="showModal"
        @cancel="handleCancel"
        :footer="null"
        width="700px"
        class="rounded-lg"
    >
        <div
            class="modal-header bg-gradient-to-br from-blue-500 to-blue-600 -mt-6 -mx-6 py-4 px-6 mb-6"
        >
            <h1 class="text-white text-lg">
                Modifier les informations du médecin
            </h1>
        </div>

        <div class="p-4">
            <form @submit.prevent="submitForm" layout="vertical">
                <!-- Information personnelles -->
                <div class="mb-6">
                    <h2 class="text-md text-gray-600 font-medium mb-4">
                        Informations personnelles
                    </h2>
                    <div
                        class="bg-gray-50/80 p-5 rounded-lg border border-gray-200/60"
                    >
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="name" value="Nom" />
                                <BaseInput
                                    id="name"
                                    type="text"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    class="transition-all duration-300 hover:border-blue-300 focus:border-blue-500"
                                />
                                <InputError
                                    class="mt-1"
                                    :message="form.errors.name"
                                />
                            </div>

                            <div>
                                <InputLabel for="prenom" value="Prénom" />
                                <BaseInput
                                    id="prenom"
                                    type="text"
                                    v-model="form.prenom"
                                    required
                                    class="transition-all duration-300 hover:border-blue-300 focus:border-blue-500"
                                />
                                <InputError
                                    class="mt-1"
                                    :message="form.errors.prenom"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Information de contact -->
                <div class="mb-6">
                    <h2 class="text-md text-gray-600 font-medium mb-4">
                        Informations de contact
                    </h2>
                    <div
                        class="bg-gray-50/80 p-5 rounded-lg border border-gray-200/60"
                    >
                        <div class="mb-4">
                            <InputLabel for="email" value="Email" />
                            <BaseInput
                                id="email"
                                type="email"
                                v-model="form.email"
                                required
                                class="transition-all duration-300 hover:border-blue-300 focus:border-blue-500"
                            />
                            <InputError
                                class="mt-1"
                                :message="form.errors.email"
                            />
                        </div>
                    </div>
                </div>

                <!-- Information professionnelles -->
                <div class="mb-6">
                    <h2 class="text-md text-gray-600 font-medium mb-4">
                        Informations professionnelles
                    </h2>
                    <div
                        class="bg-gray-50/80 p-5 rounded-lg border border-gray-200/60"
                    >
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <InputLabel
                                    for="specialite"
                                    value="Spécialité"
                                />
                                <BaseInput
                                    id="specialite"
                                    type="text"
                                    v-model="form.specialite"
                                    required
                                    class="transition-all duration-300 hover:border-blue-300 focus:border-blue-500"
                                />
                                <InputError
                                    class="mt-1"
                                    :message="form.errors.specialite"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    for="type"
                                    value="Type de médecin"
                                    class="mb-1"
                                />
                                <a-select
                                    v-model:value="form.type"
                                    class="w-full !rounded-md"
                                    :bordered="true"
                                    size="large"
                                >
                                    <a-select-option value="GENERALISTE"
                                        >Généraliste</a-select-option
                                    >
                                    <a-select-option value="ENTREPRISE"
                                        >Entreprise</a-select-option
                                    >
                                </a-select>
                                <InputError
                                    class="mt-1"
                                    :message="form.errors.type"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mot de passe -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-md text-gray-600 font-medium">
                            Sécurité
                        </h2>
                        <a-switch
                            v-model:checked="changePassword"
                            checked-children="Modifier le mot de passe"
                            un-checked-children="Conserver le mot de passe"
                        />
                    </div>
                    <div
                        v-if="changePassword"
                        class="bg-gray-50/80 p-5 rounded-lg border border-gray-200/60"
                    >
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <InputLabel
                                    for="new_password"
                                    value="Nouveau mot de passe"
                                />
                                <BaseInput
                                    id="new_password"
                                    type="password"
                                    v-model="form.password"
                                    class="transition-all duration-300 hover:border-blue-300 focus:border-blue-500"
                                />
                                <InputError
                                    class="mt-1"
                                    :message="form.errors.password"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    for="new_password_confirmation"
                                    value="Confirmation du mot de passe"
                                />
                                <BaseInput
                                    id="new_password_confirmation"
                                    type="password"
                                    v-model="form.password_confirmation"
                                    class="transition-all duration-300 hover:border-blue-300 focus:border-blue-500"
                                />
                                <InputError
                                    class="mt-1"
                                    :message="form.errors.password_confirmation"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div
                    class="flex justify-end space-x-3 pt-4 border-t border-gray-200/60"
                >
                    <Button
                        @click="handleCancel"
                        class="bg-white hover:bg-gray-50 border-gray-300/70 text-gray-700 hover:text-gray-900"
                    >
                        Annuler
                    </Button>
                    <BaseButton
                        @click="submitForm"
                        class="px-6 transition-transform duration-300 hover:scale-105"
                    >
                        Enregistrer les modifications
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
import SortDropdown from "@/Components/SortDropdown.vue";
import useConfirmDialog from "@/composables/useConfirmDialog";
import TestLayout from "@/Layouts/TestLayout.vue";
import {
    DeleteFilled,
    EditFilled,
    SortAscendingOutlined,
    SortDescendingOutlined,
} from "@ant-design/icons-vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { defineOptions, ref } from "vue";

const { confirmDelete } = useConfirmDialog();

// Layout global
defineOptions({
    layout: TestLayout,
});

// Props pour afficher les médecins
const props = defineProps({
    medecins: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

// Fonction pour naviguer entre les pages
const navigateToPage = (url) => {
    router.get(
        url,
        { sort: sortOrder.value },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};
// Gestion du modal
const showModal = ref(false);
const editingMedecinId = ref(null);
const changePassword = ref(false);

// Formulaire pour modifier un médecin
const form = useForm({
    name: "",
    prenom: "",
    email: "",
    specialite: "",
    type: "GENERALISTE",
    password: "",
    password_confirmation: "",
});

// Ouvrir le modal pour modifier un médecin
const editMedecin = (medecin) => {
    editingMedecinId.value = medecin.id;
    changePassword.value = false;

    form.name = medecin.user.name;
    form.prenom = medecin.user.prenom;
    form.email = medecin.user.email;
    form.specialite = medecin.specialite;
    form.type = medecin.type;
    form.password = "";
    form.password_confirmation = "";

    showModal.value = true;
};

// Fermer le modal
const handleCancel = () => {
    form.reset();
    showModal.value = false;
};

// Soumettre le formulaire de modification
const submitForm = () => {
    // Si le changement de mot de passe n'est pas requis, supprimer les champs du mot de passe
    if (!changePassword.value) {
        form.delete("password");
        form.delete("password_confirmation");
    }

    form.patch(route("admin.medecin.update", editingMedecinId.value), {
        onSuccess: () => {
            Swal.fire({
                title: "Mis à jour !",
                text: "Les informations du médecin ont été mises à jour avec succès",
                icon: "success",
                confirmButtonColor: "#3b82f6",
            });
            form.reset();
            showModal.value = false;
        },
        onError: (e) => {
            console.error(e);
        },
    });
};

// Options de tri disponibles
const sortOptions = [
    { value: "name_asc", label: "Nom (A-Z)", icon: SortAscendingOutlined },
    { value: "name_desc", label: "Nom (Z-A)", icon: SortDescendingOutlined },
];

// Tri initial (s'assurer que c'est une chaîne)
const sortOrder = ref(props.filters?.sort || "");

// Handler pour le changement de tri
const handleSortChanged = (value) => {
    sortOrder.value = value;

    // Ajouter ce code pour naviguer avec le nouveau tri
    router.get(
        route("admin.medecins.list"),
        { sort: value },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};
</script>

<style>
/* Animations plus fluides */
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
</style>
