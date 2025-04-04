<template>
    <!-- Card Liste des Sociétés -->
    <div class="bg-white rounded-lg shadow p-6">
        <div
            class="flex items-center justify-between mb-4 gap-4 md:flex-row flex-col"
        >
            <div>
                <h1 class="font-bold text-2xl text-gray-600">
                    Liste des Societes
                </h1>
            </div>
            <!-- Boutton pour ouvrir le formulaire d'ajout de societe et sélecteur de tri -->
            <div class="flex items-center gap-4">
                <!-- Sélecteur de tri avec Ant Design Vue -->
                <a-dropdown
                    :trigger="['click']"
                    overlay-class-name="sort-dropdown"
                >
                    <div
                        class="flex items-center gap-2 bg-gray-50 hover:bg-gray-100 transition-all duration-300 px-4 py-2.5 rounded-lg border border-gray-200 cursor-pointer shadow-sm hover:shadow"
                    >
                        <SortAscendingOutlined
                            class="text-blue-500 transition-transform duration-300"
                        />
                        <span
                            class="text-gray-500 font-normal hidden md:inline"
                            >{{ getSortLabel() }}</span
                        >
                        <DownOutlined
                            class="text-gray-400 text-xs transition-all duration-300"
                        />
                    </div>

                    <template #overlay>
                        <a-menu class="sort-menu">
                            <div
                                class="p-3 border-b border-gray-100 bg-gray-50"
                            >
                                <h4 class="font-semibold text-gray-700">
                                    Trier par
                                </h4>
                            </div>
                            <a-menu-item
                                v-for="option in sortOptions"
                                :key="option.value"
                                @click="changeSortOrder(option.value)"
                                :class="
                                    sortOrder === option.value
                                        ? 'ant-menu-item-active bg-blue-50'
                                        : ''
                                "
                            >
                                <div class="flex items-center gap-2">
                                    <component
                                        :is="getSortIcon(option.value)"
                                        class="text-sm"
                                        :class="
                                            sortOrder === option.value
                                                ? 'text-blue-500'
                                                : 'text-gray-400'
                                        "
                                    />
                                    <span
                                        :class="
                                            sortOrder === option.value
                                                ? 'text-blue-600 font-medium'
                                                : 'text-gray-600'
                                        "
                                    >
                                        {{ option.label }}
                                    </span>
                                    <CheckOutlined
                                        v-if="sortOrder === option.value"
                                        class="ml-auto text-blue-500"
                                    />
                                </div>
                            </a-menu-item>
                        </a-menu>
                    </template>
                </a-dropdown>

                <!-- Bouton pour ouvrir le formulaire dans une modal -->
                <BaseButton
                    @click="openModal"
                    class="shadow-md hover:shadow-lg transition-shadow"
                >
                    <span>Ajouter un societe</span>
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
                                        <a-menu class="!min-w-[120px]">
                                            <a-menu-item
                                                @click="editSociete(societe)"
                                                class="!py-2"
                                            >
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <EditFilled
                                                        class="text-blue-500"
                                                    />
                                                    <span class="text-blue-500"
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
                                                        class="text-red-500"
                                                    />
                                                    <span class="text-red-500"
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
                            class="mr-1 shadow-sm transition-all duration-300 hover:scale-110 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-blue-500 hover:text-white focus:border-blue-500 inline-block px-4 py-3 focus:text-blue-500"
                            :class="{ 'bg-slate-600 !text-white border-none': link.active }"
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
        @ok="submitForm"
        @cancel="handleCancel"
        cancelText="Annuler"
        :footer="null"
        class="p-4"
    >
        <h1 class="mb-6 text-blue-500 font-bold text-xl text-center">
            {{ isEditing ? "Modifier information" : "Ajouter une entreprise" }}
        </h1>
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
                    value="Description"
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
                    <Button @click="handleCancel"> Annuler </Button>
                    <BaseButton @click="submitForm">
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
import useConfirmDialog from "@/composables/useConfirmDialog";
import TestLayout from "@/Layouts/TestLayout.vue";
import {
    AppstoreAddOutlined,
    CalendarOutlined,
    CheckOutlined,
    DeleteFilled,
    DownOutlined,
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
            onError: () => {
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
            onError: () => {
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
const sortOrder = ref(props.filters?.sort || null);
// Fonction pour obtenir le lable du tri actuel
const getSortLabel = () => {
    if (!sortOrder.value) return "Trier par";
    const option = sortOptions.find((opt) => opt.value === sortOrder.value);
    return option ? option.label : "Trier par";
};
// Fonction pour obtenir l'icône correspondant au type de tri
const getSortIcon = (value) => {
    const option = sortOptions.find((opt) => opt.value === value);
    return option ? option.icon : SortAscendingOutlined;
};
// Fonction pour changer l'ordre de tri
const changeSortOrder = (value) => {
    // Mettons à jour la valeur localement avant de faire la requête
    sortOrder.value = value;

    router.get(
        route("admin.societe.index"),
        { sort: value },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            onSuccess: () => {
                // Force la mise à jour après la navigation
                sortOrder.value = value;
            },
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

/* Styles pour le dropdown de tri */
:deep(.sort-dropdown .ant-dropdown-menu) {
    padding: 0;
    border-radius: 0.75rem;
    min-width: 220px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

:deep(.sort-dropdown .ant-menu-item) {
    height: auto !important;
    line-height: 1.5;
    padding: 8px 12px !important;
    margin: 4px !important;
    border-radius: 6px;
    transition: all 0.2s ease;
}

:deep(.sort-dropdown .ant-menu-item:hover) {
    background-color: #f5f7fa;
}

:deep(.sort-dropdown .ant-menu-item.bg-blue-50) {
    background-color: rgb(239, 246, 255) !important;
}

:deep(.sort-dropdown .ant-menu-item.ant-menu-item-active) {
    background-color: rgb(239, 246, 255);
}
</style>
