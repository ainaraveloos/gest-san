<template>
    <div class="px-2 py-6 bg-white rounded-lg shadow">
        <div
            class="flex flex-col md:flex-row justify-between items-center mb-6 gap-8"
        >
            <h1 class="font-bold text-2xl text-gray-600">
                Liste des Patients <fonta class="ml-4" icon="users" />
            </h1>
            <!-- Boutton ouvrir modal de registre patient -->
            <BaseButton class="py-2" @click="showFormModal">
                <span>Ajouter nouveau patient</span>
                <fonta icon="user-plus" class="text-lg" />
            </BaseButton>
        </div>
        <!-- Input recherche -->
        <div class="flex items-center mb-4 px-4">
            <!-- Recherche par nom, prénom ou numéro du patient -->
            <div class="relative w-full md:w-1/3">
                <BaseInput
                    id="searchTerm"
                    type="text"
                    placeholder="Rechercher un nom, prenom, numero..."
                    v-model="searchTerm"
                />
            </div>
        </div>

        <!-- Table patient -->
        <div class="flex items-center justify-center overflow-x-auto">
            <table class="min-w-[97%] divide-y divide-gray-200">
                <thead class="bg-blue-500">
                    <tr
                        class="text-sm text-gray-200 font-bold uppercase tracking-tight"
                    >
                        <th class="px-4 py-4 text-left">Numéro</th>
                        <th class="py-4 px-4 text-left">Nom Complet</th>
                        <th class="py-4 px-4 text-left">
                            <div
                                class="relative flex items-center justify-start gap-4"
                            >
                                <span class="mr-1">societe</span>
                                <a-dropdown
                                    v-model:open="dropdownFilterSocieteVisible"
                                    :trigger="['click']"
                                >
                                    <FilterFilled
                                        class="hover:text-white hover:shadow-md transition-colors duration-300 text-gray-300"
                                    />

                                    <template #overlay>
                                        <div
                                            class="bg-white rounded-lg shadow-xl border border-gray-100 w-64"
                                        >
                                            <div
                                                class="p-4 border-b border-gray-100"
                                            >
                                                <h4
                                                    class="font-semibold text-gray-800"
                                                >
                                                    Filtrer par société
                                                </h4>
                                            </div>

                                            <div
                                                class="p-2 max-h-60 overflow-y-auto"
                                            >
                                                <a-checkbox-group
                                                    v-model:value="
                                                        selectedSocietes
                                                    "
                                                    class="flex flex-col gap-2"
                                                >
                                                    <a-checkbox
                                                        v-for="societe in societes"
                                                        :key="societe.id"
                                                        :value="societe.id"
                                                        class="!flex items-center p-2 hover:bg-gray-50 rounded-md transition-colors"
                                                    >
                                                        <span
                                                            class="text-gray-700"
                                                            >{{
                                                                societe.nom
                                                            }}</span
                                                        >
                                                    </a-checkbox>
                                                </a-checkbox-group>
                                            </div>

                                            <div
                                                class="p-2 border-t border-gray-100 flex justify-end"
                                            >
                                                <a-button
                                                    type="link"
                                                    size="small"
                                                    @click="
                                                        selectedSocietes = []
                                                    "
                                                    class="!text-gray-500 hover:!text-red-500 !h-8"
                                                >
                                                    Réinitialiser
                                                </a-button>
                                            </div>
                                        </div>
                                    </template>
                                </a-dropdown>
                            </div>
                        </th>
                        <th class="py-4 px-4">
                            <div
                                class="relative flex items-center justify-center gap-4"
                            >
                                <span class="mr-1">Type</span>
                                <a-dropdown
                                    v-model:open="dropdownFilterTypeVisible"
                                    :trigger="['click']"
                                >
                                    <!-- Icône de filtre -->
                                    <FilterFilled
                                        class="hover:text-white hover:shadow-md transition-colors duration-300 text-gray-300"
                                    />

                                    <!-- Menu déroulant -->
                                    <template #overlay>
                                        <div
                                            class="bg-white rounded-lg shadow-xl border border-gray-100 w-52"
                                        >
                                            <!-- En-tête -->
                                            <div
                                                class="p-4 border-b border-gray-100"
                                            >
                                                <h4
                                                    class="font-semibold text-gray-800"
                                                >
                                                    Filtrer par type
                                                </h4>
                                            </div>

                                            <!-- Liste des checkboxes -->
                                            <div
                                                class="p-2 max-h-60 overflow-y-auto"
                                            >
                                                <a-checkbox-group
                                                    v-model:value="
                                                        selectedTypes
                                                    "
                                                    class="flex flex-col gap-2"
                                                >
                                                    <a-checkbox
                                                        v-for="type in patientTypes"
                                                        :key="type.value"
                                                        :value="type.value"
                                                        class="!flex items-center p-2 hover:bg-gray-50 rounded-md transition-colors"
                                                    >
                                                        <span
                                                            class="text-gray-700"
                                                            >{{
                                                                type.label
                                                            }}</span
                                                        >
                                                    </a-checkbox>
                                                </a-checkbox-group>
                                            </div>

                                            <!-- Footer avec reset -->
                                            <div
                                                class="p-2 border-t border-gray-100 flex justify-end"
                                            >
                                                <a-button
                                                    type="link"
                                                    size="small"
                                                    @click="resetSelection"
                                                    class="!text-gray-500 hover:!text-red-500 !h-8"
                                                >
                                                    Réinitialiser
                                                </a-button>
                                            </div>
                                        </div>
                                    </template>
                                </a-dropdown>
                            </div>
                        </th>
                        <th class="py-4 text-center">status</th>
                        <th class="py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="(patient, index) in patients.data"
                        :key="patient.id"
                        :class="[
                            ' transition-colors duration-300',
                            index % 2 === 0 ? 'bg-gray-50' : 'bg-white',
                        ]"
                    >
                        <td class="p-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ patient.numero }}
                            </div>
                        </td>
                        <td class="p-4 whitespace-nowrap text-left">
                            <div class="text-md font-bold text-gray-900">
                                {{ patient.nom }} {{ patient.prenom }}
                            </div>
                        </td>
                        <td class="p-4 whitespace-nowrap text-left">
                            <div class="text-md font-bold text-gray-900">
                                {{ patient.societe?.nom }}
                            </div>
                        </td>
                        <td class="p-4 whitespace-nowrap text-center">
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
                        <td class="text-center">
                            <span
                                :class="[
            'px-2 py-2 rounded-md shadow-md text-sm font-semibold',
            patient.badge.status === 'actif' ? 'bg-green-200 text-green-700' :
            patient.badge.status === 'desactive' ? 'bg-red-200 text-red-700' :
            patient.badge.status === 'en_renouvellement' ? 'bg-yellow-200 text-yellow-700' :
            'bg-gray-200 text-gray-700' // Valeur par défaut
        ]"
                            >
                                {{ patient.badge.status }}
                            </span>
                        </td>

                        <td
                            class="py-4 place-items-end  whitespace-nowrap text-sm font-medium"
                        >
                            <div class="flex gap-2">
                                <button
                                    @click="editingPatient(patient)"
                                    class="text-blue-500 hover:bg-gray-100 group px-2 py-1 hover:scale-110 overflow-hidden hover:shadow-sm transition-all duration-300 rounded-md hover:underline"
                                >
                                    <EditFilled
                                        class="text-lg group-hover:scale-125 duration-500"
                                    />
                                </button>

                                <button
                                    danger
                                    @click="confirmDelete(route('admin.patient.delete', patient))"
                                    class="text-red-500 hover:bg-gray-100 group hover:scale-110 overflow-hidden px-2 py-1 hover:shadow-sm transition-all duration-500 rounded-md hover:underline"
                                >
                                    <DeleteFilled
                                        class="text-lg group-hover:scale-125 duration-500"
                                    />
                                </button>
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
                        class="mr-1 shadow-sm transition-all duration-300 hover:scale-110 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-blue-500 hover:text-white focus:border-blue-500 inline-block px-4 py-3 focus:text-blue-500"
                        :class="{ 'bg-slate-600 text-white': link.active }"
                        :href="link.url"
                    >
                        <span v-html="link.label"></span>
                    </Link>
                </div>
            </div>
        </div>

        <!-- Modal registre de patient -->
        <a-modal
            :open="showModal"
            :width="800"
            :footer="null"
            style="top: 15px"
            @cancel="handleCloseModal"
        >
            <h1 class="mb-6 text-blue-500 font-bold text-xl text-center">
                {{ isEditing ? "Modifier Information" : "Ajouter un Patient" }}
            </h1>
            <form @submit.prevent="handleSubmit" class="!px-4">
                <transition name="fade">
                    <div
                        class="grid grid-cols-1 items-center md:grid-cols-2 gap-4 mb-4"
                    >
                        <div>
                            <InputLabel
                                for="type de patient"
                                value="Type de patient"
                                class="mb-1"
                            />
                            <a-segmented
                                v-model:value="form.type"
                                :options="patientOptions"
                                :disabled="isEditing"
                                block
                                class="custom-patient-segmented"
                            />
                        </div>
                        <div v-if="form.type === 'SALARIE'">
                            <InputLabel
                                for="societe"
                                value="Societe"
                                class="mb-1"
                            />
                            <a-select
                                v-model:value="form.societe_id"
                                placeholder="Sélectionner une société"
                                class="w-full societe_select"
                                :style="{ height: '42px' }"
                                :disabled="isEditing"
                            >
                                <a-select-option
                                    class="p-4"
                                    v-for="societe in societes"
                                    :key="societe.id"
                                    :value="societe.id"
                                >
                                    {{ societe.nom }}
                                </a-select-option>
                            </a-select>
                        </div>

                        <div v-if="form.type === 'FAMILLE'" class="relative">
                            <InputLabel
                                for="parent_id"
                                value="Salarié Référent"
                                class="mb-1"
                            />
                            <BaseInput
                                type="text"
                                v-model="employeeSearchTerm"
                                placeholder="Rechercher un salarié..."
                                @focus="dropdownVisible = true"
                                @input="searchSalaries"
                            />
                            <div
                                v-if="
                                    dropdownVisible &&
                                    filteredSalaries.length > 0
                                "
                                class="absolute z-10 w-full bg-white shadow-md p-4 flex flex-col gap-2 rounded-md mt-1 max-h-60 overflow-y-auto"
                            >
                                <div
                                    v-for="salarie in filteredSalaries"
                                    :key="salarie.id"
                                    @mousedown.prevent="selectSalarie(salarie)"
                                    class="px-3 py-2 hover:bg-blue-600 rounded-md hover:text-white transition-colors duration-500 cursor-pointer"
                                >
                                    {{ salarie.nom }} {{ salarie.prenom }} ({{
                                        salarie.numero
                                    }})
                                </div>
                            </div>
                            <input type="hidden" v-model="form.parent_id" />
                        </div>
                    </div>
                </transition>
                <div class="mb-4" v-if="form.type === 'FAMILLE'">
                    <InputLabel for="lien_parente" value="Lien de parente" />
                    <BaseInput
                        id="lient_parente"
                        type="text"
                        v-model="form.lien_parente"
                        required
                    />
                    <InputError
                        class="mt-1"
                        :message="form.errors.lien_parente"
                    />
                </div>
                <!-- Informations de base -->
                <!-- Informations Personnelles -->
                <transition name="fade">
                    <div class="mb-6 border-b pb-4">
                        <h2 class="text-lg font-semibold text-blue-500 mb-4">
                            Informations Personnelles
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <InputLabel for="nom" value="Nom" />
                                <BaseInput
                                    id="nom"
                                    type="text"
                                    v-model="form.nom"
                                    required
                                />
                                <InputError
                                    class="mt-1"
                                    :message="form.errors.nom"
                                />
                            </div>

                            <div>
                                <InputLabel for="prenom" value="Prénom" />
                                <BaseInput
                                    id="prenom"
                                    type="text"
                                    v-model="form.prenom"
                                    required
                                />
                                <InputError
                                    class="mt-1"
                                    :message="form.errors.prenom"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <InputLabel
                                    for="date_naissance"
                                    value="Date de naissance"
                                />
                                <BaseInput
                                    id="date_naissance"
                                    type="date"
                                    v-model="form.date_naissance"
                                    required
                                />
                                <InputError
                                    class="mt-1"
                                    :message="form.errors.date_naissance"
                                />
                            </div>

                            <div>
                                <InputLabel for="adresse" value="Adresse" />
                                <BaseInput
                                    id="adresse"
                                    type="text"
                                    v-model="form.adresse"
                                />
                                <InputError
                                    class="mt-1"
                                    :message="form.errors.adresse"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <InputLabel for="telephone" value="Téléphone" />
                                <BaseInput
                                    id="telephone"
                                    type="text"
                                    v-model="form.telephone"
                                />
                                <InputError
                                    class="mt-1"
                                    :message="form.errors.telephone"
                                />
                            </div>

                            <div>
                                <InputLabel for="email" value="Email" />
                                <BaseInput
                                    id="email"
                                    type="email"
                                    v-model="form.email"
                                />
                                <InputError
                                    class="mt-1"
                                    :message="form.errors.email"
                                />
                            </div>
                        </div>
                    </div>
                </transition>

                <!-- Informations Professionnelles (Uniquement pour les salariés) -->
                <transition name="fade">
                    <div v-if="form.type === 'SALARIE'" class="border-b pb-4">
                        <h2 class="text-lg font-semibold text-blue-500 mb-4">
                            Informations Professionnelles
                        </h2>
                        <div
                            v-if="form.type === 'SALARIE'"
                            class="w-full col-span-full mb-4"
                        >
                            <InputLabel for="poste" value="Poste occupe" />
                            <BaseInput
                                id="poste"
                                type="text"
                                v-model="form.poste"
                            />
                            <InputError
                                class="mt-1"
                                :message="form.errors.poste"
                            />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <InputLabel
                                    for="date_entree_entreprise"
                                    value="Date d'entrée"
                                />
                                <BaseInput
                                    id="date_entree_entreprise"
                                    type="date"
                                    v-model="form.date_entree_entreprise"
                                    :disabled="isEditing"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    for="statut_emploi"
                                    value="Statut d'emploi"
                                    class="mb-1"
                                />
                                <a-select
                                    v-model:value="form.statut_emploi"
                                    class="w-full"
                                    :style="{ height: '42px' }"
                                    placeholder="Sélectionner un statut"
                                >
                                    <a-select-option value="Permanent"
                                        >Permanent</a-select-option
                                    >
                                    <a-select-option value="Temporaire"
                                        >Temporaire</a-select-option
                                    >
                                    <a-select-option value="CDD"
                                        >CDD</a-select-option
                                    >
                                    <a-select-option value="CDI"
                                        >CDI</a-select-option
                                    >
                                </a-select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div
                                v-if="
                                    ['Temporaire', 'CDD'].includes(
                                        form.statut_emploi
                                    )
                                "
                            >
                                <InputLabel
                                    for="date_fin_contrat"
                                    value="Date fin contrat"
                                />
                                <BaseInput
                                    id="date_fin_contrat"
                                    type="date"
                                    v-model="form.date_fin_contrat"
                                />
                            </div>
                        </div>
                    </div>
                </transition>

                <!-- Boutons d'action, disposés de manière responsive -->
                <div
                    class="flex flex-col mt-4 sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-4"
                >
                    <Button @click="handleCloseModal"> Annuler </Button>
                    <BaseButton type="submit">
                        {{ isEditing ? "Modifier" : "Enregistrer" }}
                    </BaseButton>
                </div>
            </form>
        </a-modal>
    </div>
</template>

<script setup>
import BaseButton from "@/Components/BaseButton.vue";
import BaseInput from "@/Components/BaseInput.vue";
import Button from "@/Components/Button.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TestLayout from "@/Layouts/TestLayout.vue";
import { DeleteFilled, EditFilled, FilterFilled } from "@ant-design/icons-vue";
import useConfirmDialog from "@/composables/useConfirmDialog";
import { Link, router, useForm } from "@inertiajs/vue3";
import { debounce } from "lodash";
import Swal from "sweetalert2";
import { defineOptions, ref, watch } from "vue";
const {confirmDelete} = useConfirmDialog();

defineOptions({
    layout: TestLayout,
});
const props = defineProps({
    patients: Object,
    societes: Array,
    salaries: Array,
    filters: Object,
    badge: Array,
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
    date_entree_entreprise: null,
    statut_emploi: "CDI",
    date_fin_contrat: null,
});
const showModal = ref(false);
const isEditing = ref(false);
const editingPatientId = ref(null);

const patientOptions = ref([
    {
        value: "SALARIE",
        label: "Salarié",
        // Style personnalisé avec Tailwind
        class: "!text-blue-800 hover:!bg-blue-50",
    },
    {
        value: "FAMILLE",
        label: "Bénéficiaire",
        // Style personnalisé avec Tailwind
        class: "!text-green-800 hover:!bg-green-50",
    },
]);
const patientTypes = [
    { value: "SALARIE", label: "Salarié" },
    { value: "FAMILLE", label: "Bénéficiaire" },
];
// Variables pour la recherche de salarié dans modal
const employeeSearchTerm = ref("");
const dropdownVisible = ref(false);
const filteredSalaries = ref([]);
const selectedTypes = ref(props.filters.types || []);
const resetSelection = () => {
    selectedTypes.value = [];
};
function selectSalarie(salarie) {
    form.parent_id = salarie.id;
    employeeSearchTerm.value = `${salarie.nom} ${salarie.prenom}`;
    dropdownVisible.value = false;
}
const searchSalaries = async () => {
    if (employeeSearchTerm.value.length < 2) {
        filteredSalaries.value = []; // Clear results if input is too short
        return;
    }

    const response = await fetch(
        `/admin/salaries/search?search=${employeeSearchTerm.value}`
    );
    const data = await response.json();
    filteredSalaries.value = data; // Update the filtered salaries
};

//Fermeture du Modal
const handleCloseModal = () => {
    showModal.value = false;
    form.reset();
};
//gestion de Modification
const editingPatient = (patient) => {
    isEditing.value = true;
    editingPatientId.value = patient.id;
    if (patient.type === "SALARIE") {
        form.societe_id = patient.societe_id;
        form.poste = patient.poste;
    } else if (patient.type === "FAMILLE") {
        form.parent_id = patient.parent_id;
        form.lien_parente = patient.lien_parente;
    }
    form.type = patient.type;
    form.nom = patient.nom;
    form.prenom = patient.prenom;
    form.adresse = patient.adresse;
    form.telephone = patient.telephone;
    form.email = patient.email;
    form.date_naissance = patient.date_naissance;
    showModal.value = true;
};
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
                Swal.fire({
                            title: "Mis à jour !",
                            text: "Information du patient mis à jour avec succès",
                            icon: "success",
                            confirmButtonColor: "#3b82f6",
                        });
            },
            onError: (e) => {
                console.log(e);
            },
        });
    } else {
        form.post(route("admin.patient.store"), {
            onSuccess: () => {
                Swal.fire({
                            title: "Ajout !",
                            text: "Patient ajouter avec succès",
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
const handleDelete = (patient) => {
    Modal.confirm({
        title: "Confirmer la suppression",
        content: `Voulez-vous vraiment supprimer le patient  ${patient.numero} ?`,
        okText: "Supprimer",
        okType: "danger",
        cancelText: "Annuler",
        onOk() {
            router.delete(route("admin.patient.delete", patient), {
                preserveScroll: true,
                onSuccess: () => {
                    message.success("le patient a été supprimé");
                },
                onError: () => {
                    message.error("Erreur lors de la suppression");
                },
            });
        },
    });
};

// Initialisation des filtres à partir des valeurs envoyées par le contrôleur (s'il y en a)
const searchTerm = ref(props.filters.search || "");
const selectedSocietes = ref(props.filters.societes || []);
const dropdownFilterTypeVisible = ref(false);
const dropdownFilterSocieteVisible = ref(false);

// Déclenche une nouvelle requête côté serveur dès qu'un filtre change
watch(
    [searchTerm, selectedSocietes, selectedTypes],
    debounce(([newSearch, newSociete, newTypes]) => {
        router.get(
            route("admin.patient.index"),
            {
                search: newSearch,
                societes: newSociete,
                types: newTypes, // Utilisez 'types' au lieu de 'type'
            },
            {
                preserveState: true,
                replace: true,
            }
        );
    }, 800)
);
// Fonction pour voir le dossier d'un patient
const viewDossier = (patient) => {
    router.get(route("admin.patient.view", { id: patient.id }));
};
</script>

<style>
.ant-select-selector {
    @apply !h-full;
}
.ant-select-selection-item {
    @apply !text-base mt-1 text-gray-500;
}
.ant-modal-close-x {
    @apply flex items-center justify-center   !important;
}
.ant-modal-close-x svg {
    @apply text-base;
}
.ant-segmented .ant-segmented-item-label {
    @apply py-2 text-base font-semibold;
}
.ant-segmented .ant-segmented-item-selected {
    @apply bg-blue-500 text-white transition-all duration-500;
}
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
    opacity: 0;
}
</style>
