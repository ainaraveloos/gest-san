<template>
    <!-- Card Liste des Sociétés -->
    <div class="bg-white rounded-lg shadow p-6">
        <div
            class="flex items-center justify-between mb-4 gap-4 lg:flex-row flex-col"
        >
            <div>
                <h1 class="font-bold text-2xl text-gray-600">
                    Liste des Societes
                </h1>
            </div>
            <div class="flex items-center gap-4 flex-wrap">
                <!-- Sélecteur de tri avec Ant Design Vue -->
                <SortDropdown
                    :sort-options="sortOptions"
                    :current-sort="sortOrder"
                    route-name="admin.societe.index"
                    @sort-changed="handleSortChanged"
                    class="flex-1"
                />

                <!-- Bouton pour ouvrir le formulaire dans une modal -->
                <BaseButton @click="openModal">
                    <span>Ajouter un societe</span>
                    <AppstoreAddOutlined class="text-lg" />
                </BaseButton>
            </div>
        </div>

        <div class="overflow-x-auto mb-4">
            <table
                class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden"
            >
                <thead class="bg-blue-500 text-center">
                    <tr>
                        <th
                            class="px-4 py-4 text-left texte-center text-sm font-bold text-gray-200 uppercase tracking-wider"
                        >
                            Nom
                        </th>
                        <th
                            class="px-4 py-4 text-center hidden lg:table-cell text-sm font-bold text-gray-200 uppercase tracking-wider"
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
                        v-for="(societe, index) in societes.data"
                        :key="index"
                        :class="[index % 2 === 0 ? 'bg-gray-50' : 'bg-white']"
                    >
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-base font-semibold text-gray-600">
                                {{ societe.nom }}
                            </div>
                        </td>
                        <td
                            class="px-4 py-4 hidden lg:table-cell whitespace-nowrap"
                        >
                            <div
                                class="text-sm font-bold text-gray-600 text-center"
                            >
                                {{ societe.patients_count }}
                            </div>
                        </td>
                        <td
                            class="px-4 py-4 whitespace-nowrap place-items-center"
                        >
                            <div class="text-sm text-green-600">
                                {{ societe.date_adhesion }}
                            </div>
                        </td>
                        <td
                            class="px-4 py-4 whitespace-nowrap place-items-center text-sm text-gray-500"
                        >
                            <!-- Version desktop -->
                            <div class="hidden md:flex space-x-4 items-center">
                                <!-- Lien de modification -->
                                <button
                                    @click="editSociete(societe)"
                                    class="text-blue-500 hover:bg-gray-100 group px-2 py-1 hover:scale-110 overflow-hidden hover:shadow-sm transition-all duration-300 rounded-md hover:underline"
                                >
                                    <EditFilled
                                        class="text-lg group-hover:scale-125 duration-500"
                                    />
                                </button>
                                <!-- Bouton de suppression -->
                                <button
                                    @click="
                                        confirmDelete(
                                            route(
                                                'admin.societe.delete',
                                                societe.id
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

                            <!-- Version mobile avec dropdown -->
                            <div class="md:hidden flex justify-center">
                                <a-dropdown
                                    placement="bottomRight"
                                    :trigger="['click']"
                                >
                                    <button
                                        class="text-blue-500 hover:bg-blue-100 bg-blue-50 group px-2 py-1 hover:scale-110 overflow-hidden hover:shadow-sm transition-all duration-300 rounded-md hover:underline"
                                    >
                                        <fonta
                                            icon="ellipsis"
                                            class="text-lg text-gray-400"
                                        />
                                    </button>
                                    <template #overlay>
                                        <a-menu
                                            class="!min-w-[120px] space-y-4"
                                        >
                                            <a-menu-item
                                                @click="editSociete(societe)"
                                                class="!py-2"
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
                                                            'admin.societe.delete',
                                                            societe.id
                                                        )
                                                    )
                                                "
                                                class="!py-2"
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
            <div v-if="societes.data.length > 0 && societes.links.length > 3">
                <div class="flex flex-wrap mt-4 items-center justify-end">
                    <div
                        v-for="(link, linkIndex) in societes.links"
                        :key="linkIndex"
                    >
                        <div
                            v-if="link.url === null"
                            class="mr-1 mb-1 shadow-sm transition-colors duration-300 px-4 py-3 leading-4 text-gray-400 border rounded"
                            v-html="link.label"
                        ></div>
                        <Link
                            v-else
                            :preserve-state="true"
                            :preserve-scroll="true"
                            class="mr-1 shadow-sm transition-all duration-300 hover:scale-110 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-blue-500 hover:text-white focus:border-blue-500 inline-block focus:text-blue-500"
                            :class="{
                                'bg-slate-600 !text-white border-none':
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

    <!-- Modal Ant Design pour le formulaire -->
    <a-modal
        v-model:open="showModal"
        @cancel="handleCancel"
        :footer="null"
        width="700px"
        class="rounded-lg"
    >
        <div class="modal-header bg-blue-500 -mt-6 -mx-6 py-4 px-6 mb-6">
            <h1 class="text-white text-lg">
                {{ isEditing ? "Modifier la société" : "Ajouter une société" }}
            </h1>
        </div>

        <div class="p-4">
            <form @submit.prevent="submitForm" layout="vertical">
                <!-- Informations générales -->
                <div class="mb-6">
                    <h2 class="text-md text-gray-600 font-medium mb-4">
                        Informations générales
                    </h2>
                    <div
                        class="bg-gray-50 p-5 rounded-lg border border-gray-200"
                    >
                        <div>
                            <InputLabel for="nom" value="Nom de l'entreprise" />
                            <BaseInput
                                id="nom"
                                type="text"
                                v-model="form.nom"
                                required
                                autofocus
                            />
                            <InputError
                                class="mt-1"
                                :message="form.errors.nom"
                            />
                        </div>
                    </div>
                </div>

                <!-- Informations de contact -->
                <div class="mb-6">
                    <h2 class="text-md text-gray-600 font-medium mb-4">
                        Informations de contact
                    </h2>
                    <div
                        class="bg-gray-50 p-5 rounded-lg border border-gray-200"
                    >
                        <div>
                            <InputLabel for="email" value="Email" />
                            <BaseInput
                                id="email"
                                type="email"
                                v-model="form.email"
                                required
                            />
                            <InputError
                                class="mt-1"
                                :message="form.errors.email"
                            />
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <h2 class="text-md text-gray-600 font-medium mb-4">
                        Description de l'entreprise
                    </h2>
                    <div
                        class="bg-gray-50 p-5 rounded-lg border border-gray-200"
                    >
                        <div>
                            <InputLabel for="description" value="Description" />
                            <textarea
                                name="description"
                                id="description"
                                v-model="form.description"
                                class="mt-1 block w-full p-3 border rounded-lg border-gray-300 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                rows="4"
                            ></textarea>
                            <InputError
                                class="mt-1"
                                :message="form.errors.description"
                            />
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div
                    class="flex justify-end space-x-3 pt-4 border-t border-gray-200"
                >
                    <Button
                        @click="handleCancel"
                        class="bg-white hover:bg-gray-50 border-gray-300 text-gray-700 hover:text-gray-900"
                    >
                        Annuler
                    </Button>
                    <BaseButton @click="submitForm" class="px-6">
                        {{ isEditing ? "Mettre à jour" : "Ajouter" }}
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
    AppstoreAddOutlined,
    CalendarOutlined,
    DeleteFilled,
    EditFilled,
    SortAscendingOutlined,
    SortDescendingOutlined,
    UserOutlined,
} from "@ant-design/icons-vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { defineOptions, ref } from "vue";
const { confirmDelete } = useConfirmDialog();
//Layout global
defineOptions({
    layout: TestLayout,
});
//Props pour afficher les societes
const props = defineProps({
    societes: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});



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
//edition d'un societe
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
//Soumission du formulaire
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
            onError: (e) => {
                console.log(e);
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
            onError: (e) => {
                console.log(e);
            },
        });
    }
};
// Options de tri disponibles
const sortOptions = [
    { value: "newest", label: "Plus récentes", icon: CalendarOutlined },
    { value: "oldest", label: "Plus anciennes", icon: CalendarOutlined },
    { value: "name_asc", label: "Nom (A-Z)", icon: SortAscendingOutlined },
    { value: "name_desc", label: "Nom (Z-A)", icon: SortDescendingOutlined },
    {
        value: "patients_count",
        label: "Nombre de patients",
        icon: UserOutlined,
    },
];

// Tri par date - Initialisation sans valeur par défaut
const sortOrder = ref(props.filters?.sort || "");

// Handler pour le changement de tri
const handleSortChanged = (value) => {
    sortOrder.value = value;

    // Ajouter ce code pour naviguer avec le nouveau tri
    router.get(
        route("admin.societe.index"),
        { sort: value },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};
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

/* Improve visibility transition */
.invisible {
    visibility: hidden;
}

.group-hover\:visible {
    visibility: visible;
    transition-delay: 0s;
}

.group-hover\:opacity-100 {
    transition-delay: 0.05s;
}
</style>
