<template>
    <div class="container mx-auto mb-6 px-4">
        <!-- Bannière de consultation avec style du dashboard -->
        <div
            class="bg-gradient-to-br from-blue-500 to-blue-600 px-8 py-8 mb-6 flex items-center justify-between shadow-lg rounded-xl overflow-hidden border border-blue-400/50"
        >
            <div>
                <h1 class="text-xl font-bold text-white mb-3">
                    Nouvelle Consultation
                </h1>
                <p class="text-blue-100 mb-5 text-sm opacity-90">
                    Créer une consultation pour un patient
                </p>
                <Link :href="route('medecin.consultation.create')">
                    <BaseButton
                        bg-color="bg-green-600"
                        hover-color="bg-green-700"
                        focus-color="ring-green-800"
                        active-color="bg-green-700"
                        class="py-2"
                    >
                        <span>Créer une consultation</span>
                        <fonta icon="arrow-right" class="ml-2" />
                    </BaseButton>
                </Link>
            </div>
            <img
                src="../../../../assets/Consultation.svg"
                class="w-60 hidden md:block"
                alt="Illustration de consultation"
            />
        </div>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
            <!-- Carte Informations du Patient -->
            <div
                class="bg-white shadow-md rounded-xl lg:col-span-2 col-span-full overflow-hidden border border-gray-200/60"
            >
                <div
                    class="bg-gradient-to-br from-blue-500 to-blue-600 px-6 py-4 flex items-center justify-between"
                >
                    <h2 class="text-md font-medium text-white">
                        Profil du patient
                    </h2>
                    <span
                        v-if="patient.societe"
                        class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold shadow-sm"
                    >
                        {{ patient.societe.nom }}
                    </span>
                </div>
                <!-- QR Code pour le patient -->
                <div class="m-4">
                    <div
                        class="flex flex-col items-center bg-gray-50 p-4 rounded-lg border border-gray-200/60"
                    >
                        <a-qrcode
                            :value="qrCodeValue"
                            :size="150"
                            class="qr-code"
                            ref="qrCodeRef"
                            :bordered="false"
                            errorLevel="H"
                        />
                        <p class="text-xs text-gray-500 mt-2 text-center">
                            Scannez pour accéder aux<br />informations du
                            patient
                        </p>
                    </div>
                </div>
                <div class="p-6">
                    <!-- En-tête avec nom -->
                    <div class="mb-6 pb-4 border-b border-gray-100">
                        <h3 class="text-md font-semibold text-gray-600">
                            {{ patient.nom.toUpperCase() }} {{ patient.prenom }}
                        </h3>
                        <div
                            class="flex items-center gap-4 mt-3 text-sm text-gray-500"
                        >
                            <span class="flex items-center">
                                <i
                                    class="fas fa-birthday-cake text-blue-500 mr-2"
                                ></i>
                                {{ calculateAge(patient.date_naissance) }} ans
                            </span>
                            <span class="flex items-center">
                                <i
                                    class="fas fa-id-card text-blue-500 mr-2"
                                ></i>
                                {{ patient.numero }}
                            </span>
                        </div>
                    </div>

                    <!-- Informations principales -->
                    <div class="space-y-5 mb-7">
                        <div
                            class="flex items-center justify-between bg-gray-50 p-3 rounded-lg hover:bg-blue-50/50 transition-colors duration-300"
                        >
                            <span class="text-gray-600 font-medium text-sm"
                                >Type</span
                            >
                            <span
                                class="text-gray-800 font-medium px-3 py-1 bg-gray-100 rounded-md"
                                >{{
                                    patient.type === "SALARIE"
                                        ? "Salarié"
                                        : "Famille"
                                }}</span
                            >
                        </div>
                        <div
                            class="flex items-center justify-between bg-gray-50 p-3 rounded-lg hover:bg-blue-50/50 transition-colors duration-300"
                        >
                            <span class="text-gray-600 font-medium text-sm"
                                >Téléphone</span
                            >
                            <span class="text-gray-800">{{
                                patient.telephone || "Non renseigné"
                            }}</span>
                        </div>
                        <div
                            class="flex items-center justify-between bg-gray-50 p-3 rounded-lg hover:bg-blue-50/50 transition-colors duration-300"
                        >
                            <span class="text-gray-600 font-medium text-sm"
                                >Adresse</span
                            >
                            <span class="text-gray-800">{{
                                patient.adresse || "Non renseignée"
                            }}</span>
                        </div>
                    </div>

                    <!-- Données vitales -->
                    <div class="mb-6">
                        <h4
                            class="text-sm uppercase text-gray-600 font-medium mb-4 flex items-center"
                        >
                            <div class="p-1.5 bg-blue-100 rounded-md mr-2">
                                <fonta
                                    icon="heartbeat"
                                    class="text-blue-500 text-xs"
                                />
                            </div>
                            Constantes vitales
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div
                                class="bg-gray-50 p-4 rounded-lg border border-gray-200/60 hover:bg-blue-50/30 transition-all duration-300"
                            >
                                <p class="text-sm text-gray-600 font-medium">
                                    Poids
                                </p>
                                <p
                                    class="text-gray-800 mt-2 font-medium text-lg"
                                >
                                    {{ patient.poids || "-" }}
                                    <span class="text-xs text-gray-500"
                                        >kg</span
                                    >
                                </p>
                            </div>
                            <div
                                class="bg-gray-50 p-4 rounded-lg border border-gray-200/60 hover:bg-blue-50/30 transition-all duration-300"
                            >
                                <p class="text-sm text-gray-600 font-medium">
                                    Taille
                                </p>
                                <p
                                    class="text-gray-800 mt-2 font-medium text-lg"
                                >
                                    {{ patient.taille || "-" }}
                                    <span class="text-xs text-gray-500"
                                        >cm</span
                                    >
                                </p>
                            </div>
                            <div
                                class="bg-gray-50 p-4 rounded-lg border border-gray-200/60 hover:bg-blue-50/30 transition-all duration-300"
                            >
                                <p class="text-sm text-gray-600 font-medium">
                                    Fréquence cardiaque
                                </p>
                                <p
                                    class="text-gray-800 mt-2 font-medium text-lg"
                                >
                                    {{ patient.freq_card || "-" }}
                                    <span class="text-xs text-gray-500"
                                        >bpm</span
                                    >
                                </p>
                            </div>
                            <div
                                class="bg-gray-50 p-4 rounded-lg border border-gray-200/60 hover:bg-blue-50/30 transition-all duration-300"
                            >
                                <p class="text-sm text-gray-600 font-medium">
                                    IMC
                                </p>
                                <p
                                    class="text-gray-800 mt-2 font-medium text-lg"
                                >
                                    {{
                                        patient.poids && patient.taille
                                            ? (
                                                  patient.poids /
                                                  (patient.taille / 100) ** 2
                                              ).toFixed(1)
                                            : "-"
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte Historique des Consultations améliorée -->
            <div
                class="bg-white shadow-md rounded-xl p-6 lg:col-span-3 col-span-full border border-gray-200/60"
            >
                <div class="flex items-center gap-4 mb-6">
                    <div
                        class="bg-gradient-to-br from-green-100 to-green-200 rounded-lg p-3 flex items-center justify-center shadow-sm"
                    >
                        <fonta
                            class="text-xl text-green-600"
                            icon="calendar-check"
                        />
                    </div>
                    <h2 class="text-xl font-semibold text-gray-600">
                        Historique Médical
                    </h2>
                </div>

                <a-timeline>
                    <a-timeline-item
                        v-for="consultation in patient.consultations"
                        :key="consultation.id"
                        class="cursor-pointer"
                        @click="viewConsultationDetails(consultation)"
                    >
                        <template #dot>
                            <fonta
                                icon="calendar-alt"
                                class="text-green-600 text-lg"
                            />
                        </template>
                        <div
                            class="group flex items-center justify-between bg-gray-50 hover:bg-blue-50/50 py-3 px-5 shadow-sm rounded-lg border border-gray-200/60 hover:border-blue-200/70 mb-2"
                        >
                            <div class="flex flex-col">
                                <p
                                    class="text-sm text-gray-500 font-semibold transition-all duration-300 group-hover:text-blue-600"
                                >
                                    {{
                                        formatDateLongue(
                                            consultation.date_consultation
                                        )
                                    }}
                                </p>
                                <p
                                    class="text-gray-500 flex flex-wrap items-center gap-2 mt-1"
                                >
                                    <strong class="font-medium text-gray-400"
                                        >Type de Consultation:</strong
                                    >
                                    <span
                                        :class="{
                                            'text-red-600 bg-red-50 border border-red-200 px-3 py-1 rounded-full text-xs font-semibold':
                                                consultation.type === 'maladie',
                                            'text-blue-600 bg-blue-50 border border-blue-200 px-3 py-1 rounded-full text-xs font-semibold':
                                                consultation.type ===
                                                'visite_aptitude',
                                        }"
                                    >
                                        {{
                                            consultation.type === "maladie"
                                                ? "maladie"
                                                : "visite d'aptitude"
                                        }}
                                    </span>
                                </p>
                            </div>
                            <fonta icon="chevron-right" class="text-gray-400" />
                        </div>
                    </a-timeline-item>
                </a-timeline>
            </div>
        </div>
    </div>
    <transition name="dropdown" mode="out-in">
        <div
            v-if="showConsultationDetails"
            :key="selectedConsultation.id"
            class="consultation_details_section space-y-6 bg-gradient-to-br from-white to-blue-50/20 rounded-xl p-8 shadow-lg mt-8 border border-gray-200/60 mx-4"
        >
            <!-- Section Documents -->
            <div
                class="border border-gray-200/60 p-6 rounded-lg bg-white shadow-sm"
            >
                <h3
                    class="text-md font-semibold mb-6 text-gray-700 flex items-center gap-2"
                >
                    <div class="p-2 bg-blue-100 rounded-md">
                        <fonta icon="file-alt" class="text-blue-500 text-sm" />
                    </div>
                    Documents associés
                </h3>
                <div class="flex sm:flex-row flex-col gap-3 mt-2">
                    <button
                        class="flex items-center gap-2 px-4 py-2.5 bg-white text-gray-700 rounded-md text-sm font-medium transition-all duration-200 hover:bg-gray-50 border border-gray-300 shadow-sm"
                        @click="
                            viewDocument(
                                'certificat_medical',
                                selectedConsultation
                            )
                        "
                    >
                        <fonta icon="file-medical" class="text-blue-500" />
                        Certificat Médical
                    </button>
                    <button
                        v-if="selectedConsultation.ordonnance"
                        class="flex items-center gap-2 px-4 py-2.5 bg-white text-gray-700 rounded-md text-sm font-medium transition-all duration-200 hover:bg-gray-50 border border-gray-300 shadow-sm"
                        @click="
                            viewDocument('ordonnance', selectedConsultation)
                        "
                    >
                        <fonta icon="prescription" class="text-green-600" />
                        Ordonnance
                    </button>
                    <button
                        v-if="selectedConsultation.demande_examen"
                        class="flex items-center gap-2 px-4 py-2.5 bg-white text-gray-700 rounded-md text-sm font-medium transition-all duration-200 hover:bg-gray-50 border border-gray-300 shadow-sm"
                        @click="
                            viewDocument('demande_examen', selectedConsultation)
                        "
                    >
                        <fonta icon="microscope" class="text-blue-600" /> Examen
                    </button>
                    <button
                        v-if="selectedConsultation.lettre_reference"
                        class="flex items-center gap-2 px-4 py-2.5 bg-white text-gray-700 rounded-md text-sm font-medium transition-all duration-200 hover:bg-gray-50 border border-gray-300 shadow-sm"
                        @click="
                            viewDocument(
                                'lettre_reference',
                                selectedConsultation
                            )
                        "
                    >
                        <fonta
                            icon="envelope-open-text"
                            class="text-blue-600"
                        />
                        Lettre de référence
                    </button>
                </div>
            </div>
            <!-- Section médicale -->
            <div
                class="p-6 rounded-xl border border-gray-200/60 shadow-md bg-white"
            >
                <h3
                    class="text-md font-semibold mb-6 text-blue-500 flex items-center gap-2"
                >
                    <div class="p-2 bg-blue-100 rounded-md">
                        <fonta
                            icon="stethoscope"
                            class="text-blue-500 text-sm"
                        />
                    </div>
                    Détails médicaux
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Motif de consultation -->
                    <div
                        class="space-y-2 bg-gray-50 p-5 rounded-lg border border-gray-200/60"
                    >
                        <InputLabel
                            for="Motif de Consultation"
                            value="Motif de Consultation"
                        />
                        <textarea
                            v-model="form.motif"
                            placeholder="Décrire le motif de la consultation..."
                            class="mt-1 block w-full p-2 border rounded-lg border-gray-300 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200 shadow-inner"
                        />
                    </div>

                    <!-- Diagnostic médical -->
                    <div
                        class="space-y-2 bg-gray-50 p-5 rounded-lg border border-gray-200/60"
                    >
                        <InputLabel
                            for="Diagnostic médical"
                            value="Diagnostique médical"
                        />

                        <textarea
                            v-model="form.diagnostic"
                            placeholder="Renseigner le diagnostique établi..."
                            class="mt-1 block w-full p-2 border rounded-lg border-gray-300 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200 shadow-inner"
                        />
                    </div>
                </div>
            </div>

            <!-- Actions sur le consultation -->
            <div
                class="flex justify-end items-center flex-wrap gap-4 border-t pt-6 mt-6"
            >
                <BaseButton
                    bg-color="bg-red-600"
                    hover-color="bg-red-700"
                    focus-color="ring-red-400"
                    active-color="bg-red-800"
                    class="py-2"
                    @click="
                        confirmDelete(
                            route(
                                'consultation.destroy',
                                selectedConsultation.id
                            )
                        )
                    "
                >
                    <DeleteFilled />
                    <span> Supprimer la consultation</span>
                </BaseButton>

                <BaseButton
                    @click="handleEdit(selectedConsultation)"
                    bg-color="bg-green-600"
                    hover-color="bg-green-700"
                    focus-color="ring-green-400"
                    active-color="bg-green-800"
                    class="py-2"
                    :disabled="!isFormChanged"
                >
                    <EditFilled />
                    <span> Appliquer les modifications</span>
                </BaseButton>
            </div>
        </div></transition
    >
    <!-- Modal de prévisualisation du document -->
    <a-modal
        v-model:open="previewModalVisible"
        width="600px"
        :closable="null"
        style="top: 15px"
        :footer="null"
    >
        <iframe
            :src="documentPreviewUrl"
            style="width: 100%; height: 80vh; border: none"
        ></iframe>
        <div class="flex justify-end gap-4 border-t pt-4">
            <BaseButton @click="printDocument" class="py-2">
                <fonta icon="print" />
                <span>Imprimer</span>
            </BaseButton>
            <BaseButton
                @click="downloadPDF"
                bg-color="bg-green-600"
                hover-color="bg-green-700"
                focus-color="ring-green-400"
                active-color="bg-green-800"
                class="py-2"
            >
                <fonta icon="download" />
                <span>Télécharger PDF</span>
            </BaseButton>
        </div>
    </a-modal>
</template>

<script setup>
// Importer le logo avec un chemin correct
import BaseButton from "@/Components/BaseButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import useConfirmDialog from "@/composables/useConfirmDialog";
import TestLayout from "@/Layouts/TestLayout.vue";
import { DeleteFilled, EditFilled } from "@ant-design/icons-vue";
import { Link, useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { computed, nextTick, ref } from "vue";
const { confirmDelete } = useConfirmDialog();

defineOptions({ layout: TestLayout });
const props = defineProps({ patient: Object, medecin: Object });

// Fonction calcul âge
const calculateAge = (dateNaissance) => {
    const birthDate = new Date(dateNaissance);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();

    if (
        monthDiff < 0 ||
        (monthDiff === 0 && today.getDate() < birthDate.getDate())
    ) {
        age--;
    }
    return age;
};
const formatDateLongue = (date) => {
    return new Date(date).toLocaleDateString("fr-FR", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

const selectedConsultation = ref(null);
const showConsultationDetails = ref(false);
const viewConsultationDetails = (consultation) => {
    if (consultation) {
        showConsultationDetails.value = false;
        selectedConsultation.value = null;

        setTimeout(() => {
            selectedConsultation.value = consultation;
            form.motif = consultation.motif || "";
            form.diagnostic = consultation.diagnostic || "";
            // Mettre à jour les valeurs originales avec celles de la consultation chargée
            originalMotif.value = consultation.motif || "";
            originalDiagnostic.value = consultation.diagnostic || "";
            showConsultationDetails.value = true;
            nextTick(() => {
                const element = document.querySelector(
                    ".consultation_details_section"
                );
                element?.scrollIntoView({ behavior: "smooth", block: "start" });
                window.scrollBy({ top: "500", behavior: "smooth" });
            });
        }, 0);
    }
};
//pour modification du consultation
const form = useForm({
    motif: "",
    diagnostic: "",
});

// Stocker les valeurs d'origine pour le diagnostic et le motif
const originalDiagnostic = ref(form.diagnostic);
const originalMotif = ref(form.motif);

// Computed property pour vérifier si le diagnostic a changé
const isDiagnosticChanged = computed(() => {
    return form.diagnostic !== originalDiagnostic.value;
});

// Computed property pour vérifier si le motif a changé
const isMotifChanged = computed(() => {
    return form.motif !== originalMotif.value;
});

// Computed property pour vérifier si l'un ou l'autre a changé
const isFormChanged = computed(() => {
    return isDiagnosticChanged.value || isMotifChanged.value;
});

//Modification d'une consutlation
const handleEdit = () => {
    form.patch(route("consultation.update", selectedConsultation.value.id), {
        onSuccess: () => {
            showConsultationDetails.value = false;
            selectedConsultation.value = null;
            Swal.fire({
                title: "Mise à jour !",
                text: "Consultation mis à jour avec succès",
                icon: "success",
                confirmButtonColor: "#3b82f6",
            });
        },
        onError: (e) => {
            console.log(e);
        },
    });
};
// information du badge
const badge = computed(() => {
    return props.patient.badge;
});
// Ajouter une nouvelle propriété pour le QR code
const qrCodeValue = computed(() => {
    return JSON.stringify({
        id: props.patient.id,
        nom: props.patient.nom,
        prenom: props.patient.prenom,
        numero: props.patient.numero,
        badge: badge.value.qr_code,
    });
});
const qrCodeRef = ref(null);
//Pour l'affichage des modals des documents prêts à être imprimé ou telechargé
const previewModalVisible = ref(false);
const previewDocumentTitle = ref("");
const documentPreviewUrl = ref("");
const documentType = ref("");

/**
 * Ouvre le modal apperçu du contenu du document
 */
const viewDocument = async (docType, consultation) => {
    selectedConsultation.value = consultation;
    documentType.value = docType;
    // Définir le titre du document
    switch (docType) {
        case "ordonnance":
            previewDocumentTitle.value = "Ordonnance";
            break;
        case "demande_examen":
            previewDocumentTitle.value = "Demande d'examen";
            break;
        case "lettre_reference":
            previewDocumentTitle.value = "Lettre de référence";
            break;
        case "certificat_medical":
            previewDocumentTitle.value = "Certificat Médical";
            break;
        default:
            previewDocumentTitle.value = "Document";
    }
    // Construire l'URL de prévisualisation
    documentPreviewUrl.value = route("admin.document.preview", {
        type: docType,
        consultation: consultation.id,
    });

    // Afficher le modal
    previewModalVisible.value = true;
};

/**
 * Imprime le contenu du document
 */
const printDocument = () => {
    const iframe = document.querySelector("iframe");
    iframe.contentWindow.print();
};

/**
 * Télécharge le document PDF
 */
const downloadPDF = () => {
    // Fermer le modal de prévisualisation
    previewModalVisible.value = false;

    // Utiliser la route de téléchargement
    const downloadUrl = route("admin.document.download", {
        type: documentType.value,
        consultation: selectedConsultation.value.id,
    });

    // Créer un lien temporaire pour le téléchargement
    const link = document.createElement("a");
    link.href = downloadUrl;
    link.download = `${previewDocumentTitle.value}_${selectedConsultation.value.id}.pdf`; // Force le téléchargement
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};
</script>

<style scoped>
@keyframes slideIn {
    from {
        transform: translateY(20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateY(0);
        opacity: 1;
    }
    to {
        transform: translateY(20px);
        opacity: 0;
    }
}
.dropdown-enter-active {
    animation: slideIn 0.5s ease-out;
}

.dropdown-leave-active {
    animation: slideOut 2ms ease-in;
}
/* Animation personnalisée */
.group:hover .fa-chevron-right {
    transform: translateX(4px);
    transition: all 0.8s ease;
}

/* Style des séparateurs */
.border-gray-100 {
    border-color: #f3f4f6;
}
</style>
