<template>
    <div class="container mx-auto mb-6">
        <div
            class="bg-blue-500 px-10 relative mb-6 overflow-hidden flex items-center justify-between h-48 w-full shadow-sm rounded-xl col-span-full"
        >
            <div>
                <h1
                    class="font-bold text-[clamp(1.8rem,5vw,2.5rem)] text-white"
                >
                    Nouveau Consultation
                </h1>
                <Link :href="route('medecin.consultation.create')">
                    <BaseButton
                        bg-color="bg-green-600"
                        hover-color="bg-green-700"
                        focus-color="ring-green-400"
                        active-color="bg-green-800"
                    class="py-2"
                    >
                        <span>creer un consultation</span>
                        <ArrowRightOutlined
                            class="group-hover:translate-x-2 group-hover:shadow-sm transition-all duration-500"
                        />
                    </BaseButton>
                </Link>
            </div>
            <img
                src="../../../../assets/Consultation.svg"
                class="w-60 mt-2"
                alt=""
            />
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <!-- Carte Informations du Patient -->
            <div
                class="bg-white shadow-md rounded-md xl:col-span-1 col-span-full p-6 transition-all duration-300"
            >
                <div class="flex items-center gap-4 mb-6">
                    <div
                        class="bg-blue-100 rounded-lg p-2 flex items-center justify-center mb-2"
                    >
                        <fonta
                            class="text-2xl text-blue-600"
                            icon="user-injured"
                        />
                    </div>
                    <h2 class="text-2xl font-bold text-gray-600">
                        Profil du Patient
                    </h2>
                </div>

                <div class="space-y-4">
                    <div
                        v-for="(info, key) in patientInfos"
                        :key="key"
                        class="flex gap-4 items-center justify-between py-4 border-b border-gray-100 last:border-0"
                    >
                        <span class="col-span-1 text-gray-400 font-medium">{{
                            info.label
                        }}</span>
                        <span class="col-span-2 text-gray-700">{{
                            info.value
                        }}</span>
                    </div>
                </div>
            </div>

            <!-- Carte Historique des Consultations -->
            <div
                class="bg-white shadow-md rounded-md p-6 xl:col-span-2 col-span-full transition-all duration-300"
            >
                <div class="flex items-center gap-4 mb-6">
                    <div
                        class="bg-green-100 rounded-lg p-2 flex items-center justify-center mb-2"
                    >
                        <fonta
                            class="text-2xl text-green-600"
                            icon="calendar-check"
                        />
                    </div>
                    <h2 class="text-2xl font-bold text-gray-600">
                        Historique Médical
                    </h2>
                </div>

                <ul class="space-y-6">
                    <li
                        v-for="consultation in patient.consultations"
                        :key="consultation.id"
                        class="group cursor-pointer p-4 rounded-lg border-gray-100 border hover:shadow-sm hover:bg-blue-50 transition-all duration-500"
                        @click="viewConsultationDetails(consultation)"
                    >
                        <div class="flex justify-between items-start">
                            <div
                                class="flex flex-col items-start justify-between"
                            >
                                <p
                                    class="text-sm group-hover:text-blue-500 font-semibold group-hover:scale-105 transition-all duration-500 text-gray-400"
                                >
                                    {{
                                        formatDateLongue(
                                            consultation.date_consultation
                                        )
                                    }}
                                </p>
                                <p
                                    class="text-gray-500 flex flex-wrap items-center gap-2"
                                >
                                    <strong>Type de Consultation:</strong>
                                    <span
                                        :class="{
                                            'text-red-500 bg-red-100 border border-red-500 p-2 rounded-md':
                                                consultation.type === 'maladie',
                                            'text-blue-500 bg-blue-100 border border-blue-500 p-2 rounded-md':
                                                consultation.type ===
                                                'visite_aptitude',
                                        }"
                                        >{{
                                            consultation.type === "maladie"
                                                ? "maladie"
                                                : "visite d'aptitude"
                                        }}</span
                                    >
                                </p>
                            </div>
                            <span
                                class="text-gray-400 group-hover:text-blue-500"
                            >
                                <fonta icon="chevron-right" />
                            </span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <transition name="dropdown" mode="out-in">
        <div
            v-if="showConsultationDetails"
            :key="selectedConsultation.id"
            class="consultation_details_section space-y-8 bg-white rounded-md p-6 shadow-md"
        >
            <!-- Section Documents -->
            <div class="bg-gray-50 border border-gray-200 p-6 rounded-lg">
                <h3 class="text-lg font-semibold mb-8 text-blue-500">
                    Documents associés
                </h3>
                <div class="flex sm:flex-row flex-col gap-4 mt-2">
                    <button
                        class="flex items-center gap-1 px-2 py-2 bg-orange-100 text-yellow-700 rounded-md text-sm font-medium transition-all duration-500 hover:shadow-md transform hover:scale-105"
                        @click="
                            viewDocument(
                                'certificat_medical',
                                selectedConsultation
                            )
                        "
                    >
                        <fonta icon="file-medical" /> Certificat Médical
                    </button>
                    <button
                        v-if="selectedConsultation.ordonnance"
                        class="flex items-center gap-1 px-2 py-2 bg-green-100 text-green-500 rounded-md text-sm font-medium transition-all duration-500 hover:shadow-md transform hover:scale-105"
                        @click="
                            viewDocument('ordonnance', selectedConsultation)
                        "
                    >
                        <fonta icon="prescription" /> Ordonnance
                    </button>
                    <button
                        v-if="selectedConsultation.demande_examen"
                        class="flex items-center gap-1 px-2 py-2 bg-blue-100 text-blue-500 rounded-md text-sm font-medium transition-all duration-500 hover:shadow-md transform hover:scale-105"
                        @click="
                            viewDocument('demande_examen', selectedConsultation)
                        "
                    >
                        <fonta icon="microscope" /> Examen
                    </button>
                    <button
                        v-if="selectedConsultation.lettre_reference"
                        class="flex items-center gap-1 px-2 py-2 bg-purple-100 text-purple-500 rounded-md text-sm font-medium transition-all duration-500 hover:shadow-md transform hover:scale-105"
                        @click="
                            viewDocument(
                                'lettre_reference',
                                selectedConsultation
                            )
                        "
                    >
                        <fonta icon="envelope-open-text" /> Lettre de référence
                    </button>
                </div>
            </div>
            <!-- Section médicale -->
            <div
                class="bg-gray-50 p-6 rounded-xl border border-gray-200 shadow-sm"
            >
                <h3
                    class="text-lg font-semibold mb-8 text-blue-500 flex items-center"
                >
                    Détails médicaux
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Motif de consultation -->
                    <div class="space-y-2">
                        <label
                            class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2"
                        >
                            Motif de consultation
                        </label>
                        <a-textarea
                            v-model:value="form.motif"
                            placeholder="Décrire le motif de la consultation..."
                            :auto-size="{ minRows: 3, maxRows: 6 }"
                            class="mt-1 block w-full p-2 border rounded-lg border-gray-300 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                        />
                    </div>

                    <!-- Diagnostic médical -->
                    <div class="space-y-2">
                        <label
                            class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2"
                        >
                            Diagnostic médical
                        </label>
                        <a-textarea
                            v-model:value="form.diagnostic"
                            placeholder="Renseigner le diagnostic établi..."
                            :auto-size="{ minRows: 3, maxRows: 8 }"
                            class="mt-1 block w-full p-2 border rounded-lg border-gray-300 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                        />
                    </div>
                </div>
            </div>
            <!-- Bouton pour le Certificat Médical -->

            <!-- Actions sur le consultation -->
            <div class="flex justify-end gap-4 border-t pt-4 mt-6">
                <button
                    type="primary"
                    @click="confirmDelete(route('consultation.destroy',selectedConsultation.id))"
                    class="inline-flex items-center rounded-md border gap-2 border-transparent bg-red-500 transition-colors duration-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white ease-in-out ${bgColor} hover:${hoverColor} focus:${focusColor} focus:outline-none focus:ring-2 focus:ring-offset-2 active:${activeColor}"
                >
                    <DeleteFilled />
                    <span> Supprimer la consultation</span>
                </button>

                <BaseButton
                    @click="handleEdit(selectedConsultation)"
                    bg-color="bg-green-600"
                        hover-color="bg-green-700"
                        focus-color="ring-green-400"
                        active-color="bg-green-800"
                        class="py-2 disabled:bg-slate-500"
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
        width="700px"
        :closable="null"
        style="top: 15px"
        :footer="null"
    >
        <div
            id="document-content"
            class=" mt-4"
            v-html="previewDocumentContent"
        ></div>
        <div class="flex justify-end gap-4 border-t pt-4">
            <BaseButton
                @click="printDocument"
                class="py-2"

            >
                <fonta icon="print" />
                <span>imprimer</span>
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
                <span>Telecharger PDF</span>
            </BaseButton>
        </div>
    </a-modal>
</template>

<script setup>
// Importer le logo avec un chemin correct
import TestLayout from "@/Layouts/TestLayout.vue";
import {
    ArrowRightOutlined,
    DeleteFilled,
    EditFilled,
} from "@ant-design/icons-vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import { message, Modal } from "ant-design-vue";
import html2canvas from "html2canvas";
import jsPDF from "jspdf";
import { computed, nextTick, ref } from "vue";
import logo from "../../../../assets/medicare_dark.svg"; // Assurez-vous que le chemin est correct
import BaseButton from "@/Components/BaseButton.vue";
import useConfirmDialog from "@/composables/useConfirmDialog";
const {confirmDelete} = useConfirmDialog();

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

const patientInfos = computed(() => [
    {
        label: "Nom complet",
        value: `${props.patient.nom.toUpperCase()} ${props.patient.prenom}`,
    },
    { label: "Téléphone", value: props.patient.telephone },
    { label: "Société", value: props.patient.societe?.nom || "Non renseignée" },
    { label: "Numéro patient", value: props.patient.numero },
    {
        label: "Poids/Taille",
        value: `${props.patient.poids} kg / ${props.patient.taille} cm`,
    },
    {
        label: "IMC",
        value: (
            props.patient.poids /
            (props.patient.taille / 100) ** 2
        ).toFixed(2),
    },
    { label: "Frequence Cardiaque", value: `${props.patient.freq_card} bpm` },
]);
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
            message.success("La consultation a été mise à jour avec succès.");
        },
        onError: () => {
            message.error("Erreur lors de la modification du consultation");
        },
    });
};


//Pour l'affichage des modals des documents prêts à être imprimé ou telechargé
const previewModalVisible = ref(false);
const previewDocumentContent = ref("");
const previewDocumentTitle = ref("");
/**
 * Contenu HTML pour une ordonnance
 */
const getOrdonnanceContent = (consultation) => {
    const medicaments = consultation.ordonnance?.medicaments || [];
    const medicamentRows = medicaments
        .map(
            (med) => `
        <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 8px; text-align: left;">${med.designation}</td>
            <td style="padding: 8px; text-align: left;">${med.forme}</td>
            <td style="padding: 8px; text-align: left;">${med.posologie}</td>
            <td style="padding: 8px; text-align: center;">${med.quantite}</td>
        </tr>
    `
        )
        .join("");

    return `
    <div style="font-family: Arial, sans-serif; padding: 15px; border: 1px solid #ddd;">
        <div style="text-align: center; margin-bottom: 1rem;">
            <h1 style="font-size: 20px; font-weight: bold;display: flex; align-items: center justify-content: center; margin: 0;"><img src="${logo}" alt="Logo" style="height: 40px; vertical-align: middle; margin-right: 10px;" /><span style="margin-top:8px;">MEDICARE</span></h1>
            <p style="font-size: 13px; margin: 2px 0;">Centre Médical Agréé</p>
            <p style="font-size: 13px; margin: 2px 0;">123 Rue de la Santé, Adresse - Tél: 0000000000</p>
        </div>
        <!-- Date et informations médecin -->
        <div style="text-align: right; font-size: 13px; margin-bottom: 1rem;">
            <p style="margin: 2px 0;">Le ${formatDateLongue(
                new Date(consultation.date_consultation)
            )}</p>
            <div style="margin-top: 0.5rem;">
                <p style="margin: 2px 0; font-weight: bold;">Dr. ${props.medecin.nom.toUpperCase()} ${
        props.medecin.prenom
    }</p>
                <p style="margin: 2px 0;">Médecin ${props.medecin.type.toLowerCase()}</p>
                <p style="margin: 2px 0;">N° RPPS : ________________</p>
            </div>
        </div>
        <h3 style="text-align: center; margin-bottom: 15px; font-weight: bold; font-size: 16px;">Ordonnance</h3>

         <!-- Informations patient -->
        <div style="margin-bottom: 15px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <tr style="background-color: #f3f4f6;">
                    <th colspan="2" style="padding: 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">
                        Informations du Patient
                    </th>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600; width: 150px;">Nom et Prénom</td>
                    <td style="padding: 8px;">${props.patient.nom} ${
        props.patient.prenom
    }</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600;">Âge</td>
                    <td style="padding: 8px;">${calculateAge(
                        props.patient.date_naissance
                    )} ans</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600;">Numéro de dossier</td>
                    <td style="padding: 8px;">${
                        props.patient.numero || "N/A"
                    }</td>
                </tr>
            </table>
        </div>

        <hr style="margin: 15px 0;">

        <h4 style="margin-bottom: 10px; font-size: 14px;">Médicaments prescrits :</h4>

        <div style="max-width: 100%; overflow-x: auto; margin-bottom: 15px; border: 1px solid #e5e7eb; border-radius: 8px;">
            <table style="width: 100%; border-collapse: collapse; white-space: nowrap; min-width: 100%; font-size: 13px;">
                <thead>
                    <tr style="background-color: #f3f4f6;">
                        <th style="padding: 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Désignation</th>
                        <th style="padding: 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Forme</th>
                        <th style="padding: 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Posologie</th>
                        <th style="padding: 8px; text-align: center; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Quantité</th>
                    </tr>
                </thead>
                <tbody>
                    ${medicamentRows}
                </tbody>
            </table>
        </div>

        ${
            consultation.ordonnance.nbr_jours_repos
                ? `
        <div style="margin: 15px 0; padding: 8px; background-color: #f3f4f6; border-radius: 8px; border: 1px solid #e5e7eb; font-size: 13px;">
            <p style="margin: 0;"><strong>Repos prescrit :</strong> ${consultation.ordonnance.nbr_jours_repos} jours</p>
        </div>
        `
                : ""
        }

        <hr style="margin: 15px 0;">

        <!-- Signature -->
            <div style="margin-top: 2rem; text-align: right;">
                <div style="display: inline-block; text-align: center; border-top: 1px solid #666; padding-top: 6px;">
                    <p style="margin: 2px 0;">
                        Signature et cachet du médecin
                    </p>
                    <p style="margin: 2px 0; font-style: italic; font-size: 11px; color: #666;">
                        (Article R.4127-76 du code de la santé publique)
                    </p>
                </div>
            </div>
    </div>
    `;
};

/**
 * Contenu HTML pour une demande d'examen
 */
const getDemandeExamenContent = (consultation) => {
    const examens = JSON.parse(consultation.demande_examen.liste_examens);
    const examensList = examens
        .map(
            (examen) => `
        <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 8px;font-wh: bold; text-align: left;">${examen.toUpperCase()}</td>
        </tr>
    `
        )
        .join("");

    return `
    <div style="font-family: Arial, sans-serif; padding: 15px; border: 1px solid #ddd;">
        <div style="text-align: center; margin-bottom: 1rem;">
            <h1 style="font-size: 20px; font-weight: bold;display: flex; align-items: center justify-content: center; margin: 0;"><img src="${logo}" alt="Logo" style="height: 40px; vertical-align: middle; margin-right: 10px;" /><span style="margin-top:8px;">MEDICARE</span></h1>
            <p style="font-size: 13px; margin: 2px 0;">Centre Médical Agréé</p>
            <p style="font-size: 13px; margin: 2px 0;">123 Rue de la Santé, Adresse - Tél: 0000000000</p>
        </div>
        <!-- Date et informations médecin -->
        <div style="text-align: right; font-size: 13px; margin-bottom: 1rem;">
            <p style="margin: 2px 0;">Le ${formatDateLongue(
                new Date(consultation.date_consultation)
            )}</p>
            <div style="margin-top: 0.5rem;">
                <p style="margin: 2px 0; font-weight: bold;">Dr. ${props.medecin.nom.toUpperCase()} ${
        props.medecin.prenom
    }</p>
                <p style="margin: 2px 0;">Médecin ${props.medecin.type.toLowerCase()}</p>
                <p style="margin: 2px 0;">N° RPPS : ________________</p>
            </div>
        </div>
        <h3 style="text-align: center; margin-bottom: 15px; font-weight: bold; font-size: 16px;">Demande d'Examen</h3>

        <!-- Informations patient -->
        <div style="margin-bottom: 15px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <tr style="background-color: #f3f4f6;">
                    <th colspan="2" style="padding: 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">
                        Informations du Patient
                    </th>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600; width: 150px;">Nom et Prénom</td>
                    <td style="padding: 8px;">${props.patient.nom} ${
        props.patient.prenom
    }</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600;">Âge</td>
                    <td style="padding: 8px;">${calculateAge(
                        props.patient.date_naissance
                    )} ans</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600;">Numéro de dossier</td>
                    <td style="padding: 8px;">${
                        props.patient.numero || "N/A"
                    }</td>
                </tr>
            </table>
        </div>

        <hr style="margin: 15px 0;">

        <h4 style="margin-bottom: 10px; font-size: 14px;">Examens demandés :</h4>
        <div style="max-width: 100%; overflow-x: auto; margin-bottom: 15px; border: 1px solid #e5e7eb; border-radius: 8px;">
            <table style="width: 100%; border-collapse: collapse; min-width: 100%; font-size: 13px;">
                <thead>
                    <tr style="background-color: #f3f4f6;">
                        <th style="padding: 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">
                            Désignation de l'examen
                        </th>
                    </tr>
                </thead>
                <tbody>
                    ${examensList}
                </tbody>
            </table>
        </div>

        ${
            consultation.demande_examen.remarques
                ? `
        <div style="margin: 15px 0; padding: 8px; background-color: #f3f4f6; border-radius: 8px; border: 1px solid #e5e7eb; font-size: 13px;">
            <p style="margin: 0;"><strong>Remarques :</strong></p>
            <p style="margin: 8px 0 0 0; line-height: 1.4;">
                ${consultation.demande_examen.remarques}
            </p>
        </div>
        `
                : ""
        }

        <hr style="margin: 15px 0;">

        <!-- Signature -->
            <div style="margin-top: 2rem; text-align: right;">
                <div style="display: inline-block; text-align: center; border-top: 1px solid #666; padding-top: 6px;">
                    <p style="margin: 2px 0;">
                        Signature et cachet du médecin
                    </p>
                    <p style="margin: 2px 0; font-style: italic; font-size: 11px; color: #666;">
                        (Article R.4127-76 du code de la santé publique)
                    </p>
                </div>
            </div>
    </div>
    `;
};
/**
 * Génère le contenu HTML pour une lettre de référence
 */
const getLettreReferenceContent = (consultation) => {
    return `
    <div style="font-family: Arial, sans-serif; padding: 15px; border: 1px solid #ddd;">
        <!-- En-tête -->
        <div style="text-align: center; margin-bottom: 1rem;">
            <h1 style="font-size: 20px; font-weight: bold;display: flex; align-items: center justify-content: center; margin: 0;"><img src="${logo}" alt="Logo" style="height: 40px; vertical-align: middle; margin-right: 10px;" /><span style="margin-top:8px;">MEDICARE</span></h1>
            <p style="font-size: 13px; margin: 2px 0;">Centre Médical Agréé</p>
            <p style="font-size: 13px; margin: 2px 0;">123 Rue de la Santé, Adresse - Tél: 0000000000</p>
        </div>
        <!-- Date et informations médecin -->
        <div style="text-align: right; font-size: 13px; margin-bottom: 1rem;">
            <p style="margin: 2px 0;">Le ${formatDateLongue(
                new Date(consultation.date_consultation)
            )}</p>
            <div style="margin-top: 0.5rem;">
                <p style="margin: 2px 0; font-weight: bold;">Dr. ${props.medecin.nom.toUpperCase()} ${
        props.medecin.prenom
    }</p>
                <p style="margin: 2px 0;">Médecin ${props.medecin.type.toLowerCase()}</p>
                <p style="margin: 2px 0;">N° RPPS : ________________</p>
            </div>
        </div>
         <h3 style="text-align: center; margin-bottom: 15px; font-weight: bold; font-size: 16px;">Lettre de Référence</h3>
        <!-- Date et lieu -->


        <!-- Informations patient -->
        <div style="margin-bottom: 15px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <tr style="background-color: #f3f4f6;">
                    <th colspan="2" style="padding: 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">
                        Informations du Patient
                    </th>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600; width: 150px;">Nom et Prénom</td>
                    <td style="padding: 8px;">${props.patient.nom} ${
        props.patient.prenom
    }</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600;">Âge</td>
                    <td style="padding: 8px;">${calculateAge(
                        props.patient.date_naissance
                    )} ans</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600;">Numéro de dossier</td>
                    <td style="padding: 8px;">${
                        props.patient.numero || "N/A"
                    }</td>
                </tr>
            </table>
        </div>

        <!-- Destinataire -->
        <div style="margin-bottom: 15px; font-size: 13px;">
            <p style="margin: 3px 0;"><strong>Docteur</strong></p>
            <p style="margin: 3px 0;">${
                consultation.lettre_reference?.medecin_ref?.nom ||
                "_________________"
            }</p>
            <p style="margin: 3px 0;">${
                consultation.lettre_reference?.medecin_ref?.specialite ||
                "_________________"
            }</p>
        </div>

        <!-- Objet et Introduction -->
        <div style="margin-bottom: 15px; font-size: 13px;">
            <p style="margin: 6px 0;"><strong>Objet :</strong> Lettre de référence médicale</p>
            <p style="margin: 6px 0;">Cher Confrère, Chère Consœur,</p>
            <p style="margin: 6px 0;">Je me permets de vous adresser en consultation le patient cité ci-dessus pour les raisons suivantes :</p>
        </div>

        <!-- Informations médicales -->
        <div style="margin-bottom: 15px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <tr style="background-color: #f3f4f6;">
                    <th colspan="2" style="padding: 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">
                        Informations Médicales
                    </th>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600; width: 150px;">Motif de référence</td>
                    <td style="padding: 8px;">${
                        consultation.lettre_reference?.motif_ref ||
                        "Non spécifié"
                    }</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600;">Diagnostic actuel</td>
                    <td style="padding: 8px;">${
                        consultation.diagnostic || "Non spécifié"
                    }</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600;">Antécédents notables</td>
                    <td style="padding: 8px;">${
                        props.patient?.antecedents || "Aucun antécédent notable"
                    }</td>
                </tr>
            </table>
        </div>

        <!-- Conclusion -->
        <div style="margin-bottom: 15px; font-size: 13px; line-height: 1.4;">
            <p style="margin: 6px 0;">Je vous remercie de votre attention et vous prie de me tenir informé de vos conclusions.</p>
            <p style="margin: 6px 0;">Confraternellement,</p>
        </div>

       <!-- Signature -->
            <div style="margin-top: 2rem; text-align: right;">
                <div style="display: inline-block; text-align: center; border-top: 1px solid #666; padding-top: 6px;">
                    <p style="margin: 2px 0;">
                        Signature et cachet du médecin
                    </p>
                    <p style="margin: 2px 0; font-style: italic; font-size: 11px; color: #666;">
                        (Article R.4127-76 du code de la santé publique)
                    </p>
                </div>
            </div>
    </div>
    `;
};

const getCertificatMedicalContent = (consultation) => {
    const joursRepos = consultation.ordonnance?.nbr_jours_repos;
    return `
    <div style="font-family: Arial, sans-serif; padding: 15px; border: 1px solid #ddd;">
        <!-- En-tête -->
        <div style="text-align: center; margin-bottom: 1rem; border-bottom: 1px solid #666; padding-bottom: 0.5rem;">
            <h1 style="font-size: 20px; font-weight: bold;display: flex; align-items: center justify-content: center; margin: 0;"><img src="${logo}" alt="Logo" style="height: 40px; vertical-align: middle; margin-right: 10px;" /><span style="margin-top:8px;">MEDICARE</span></h1>
            <p style="font-size: 13px; margin: 2px 0;">Centre Médical Agréé</p>
            <p style="font-size: 13px; margin: 2px 0;">123 Rue de la Santé, Adresse - Tél: 0000000000</p>
        </div>

        <!-- Date et informations médecin -->
        <div style="text-align: right; font-size: 13px; margin-bottom: 1rem;">
            <p style="margin: 2px 0;">Le ${formatDateLongue(
                new Date(consultation.date_consultation)
            )}</p>
            <div style="margin-top: 0.5rem;">
                <p style="margin: 2px 0; font-weight: bold;">Dr. ${props.medecin.nom.toUpperCase()} ${
        props.medecin.prenom
    }</p>
                <p style="margin: 2px 0;">Médecin ${props.medecin.type.toLowerCase()}</p>
                <p style="margin: 2px 0;">N° RPPS : ________________</p>
            </div>
        </div>

        <!-- Titre -->
        <div style="text-align: center; margin: 1rem 0;">
            <h2 style="font-size: 16px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; padding: 8px 15px; background-color: #263238 ; color: white; display: inline-block;">
                Certificat Médical
            </h2>
        </div>

        <!-- Corps du certificat -->
        <div style="font-size: 13px; line-height: 1.4;">
            <p style="margin: 0.5rem 0;">
                Je soussigné, <strong>Docteur ${props.medecin.nom.toUpperCase()} ${
        props.medecin.prenom
    }</strong>,
                certifie avoir examiné ce jour :
            </p>

            <!-- Informations patient -->
            <div style="margin: 0.75rem 0; padding: 0.75rem; background-color: #f8fafc;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                    <tr>
                        <td style="padding: 4px 0; width: 150px;">Nom</td>
                        <td style="padding: 4px 0;"><strong>${props.patient.nom.toUpperCase()}</strong></td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0;">Prénom</td>
                        <td style="padding: 4px 0;"><strong>${
                            props.patient.prenom
                        }</strong></td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0;">Né(e) le</td>
                        <td style="padding: 4px 0;">${formatDateLongue(
                            props.patient.date_naissance
                        )} (${calculateAge(
        props.patient.date_naissance
    )} ans)</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0;">Adresse</td>
                        <td style="padding: 4px 0;">${
                            props.patient.adresse || "Non renseigné"
                        }</td>
                    </tr>
                </table>
            </div>

           ${
               joursRepos
                   ? `
        <p style="margin: 0.75rem 0; text-align: justify;">
            Après examen clinique complet réalisé ce jour, je certifie que l'état de santé
            du/de la patient(e) nécessite une <strong>interruption temporaire de travail</strong>
            d'une durée de <strong>${joursRepos} jour${
                         joursRepos > 1 ? "s" : ""
                     }</strong> à compter de ce jour.
        </p>
        `
                   : ""
           }

            <p style="margin: 0.75rem 0; text-align: justify;">
                Ce certificat est établi à la demande de l'intéressé(e) et lui est remis en main propre
                pour faire valoir ce que de droit.
            </p>

            <!-- Signature -->
            <div style="margin-top: 2rem; text-align: right;">
                <div style="display: inline-block; text-align: center; border-top: 1px solid #666; padding-top: 6px;">
                    <p style="margin: 2px 0;">
                        Signature et cachet du médecin
                    </p>
                    <p style="margin: 2px 0; font-style: italic; font-size: 11px; color: #666;">
                        (Article R.4127-76 du code de la santé publique)
                    </p>
                </div>
            </div>
        </div>
    </div>
    `;
};

const getDocumentContent = (docType, consultation) => {
    if (docType === "ordonnance") {
        previewDocumentTitle.value = "Ordonnance";
        return getOrdonnanceContent(consultation);
    } else if (docType === "demande_examen") {
        previewDocumentTitle.value = "Demande d'examen";
        return getDemandeExamenContent(consultation);
    } else if (docType === "lettre_reference") {
        previewDocumentTitle.value = "Lettre de référence";
        return getLettreReferenceContent(consultation);
    } else if (docType === "certificat_medical") {
        previewDocumentTitle.value = "Certificat Médical";
        return getCertificatMedicalContent(consultation);
    } else {
        return "";
    }
};

/**
 * Ouvre le modal apperçu du contenu du document
 */
const viewDocument = (docType, consultation) => {
    previewDocumentContent.value = getDocumentContent(docType, consultation);
    previewModalVisible.value = true;
};

/**
 * Imprime le contenu du document dans une nouvelle fenêtre
 */
const printDocument = () => {
    const printWindow = window.open("", "_blank");
    printWindow.document.write(`
    <html>
      <head>
        <title>${previewDocumentTitle.value}</title>
        <style>
          body { font-family: Arial, sans-serif; margin: 20px; }
          h2 { text-align: center; }
        </style>
      </head>
      <body>${previewDocumentContent.value}</body>
    </html>
  `);
    printWindow.document.close();
    // Attendre que le contenu soit chargé avant d'imprimer
    setTimeout(() => {
        printWindow.focus();
        printWindow.print();
        // Fermer la fenêtre après l'impression
        printWindow.onafterprint = function () {
            printWindow.close();
        };
    }, 200);
};

/**
 * Télécharge le contenu du document en PDF
 */
const downloadPDF = async () => {
    try {
        const loadingMessage = message.loading(
            "Génération du PDF en cours...",
            0
        );

        // Capture le contenu HTML
        const element = document.getElementById("document-content");
        const canvas = await html2canvas(element, {
            scale: 3,
            useCORS: true,
            logging: true,
            backgroundColor: "#FFFFFF",
        });

        // Création du PDF
        const doc = new jsPDF({
            format: "a4",
            unit: "mm",
            hotfixes: ["px_scaling"],
        });

        const imgData = canvas.toDataURL("image/png", 1.0);
        const imgWidth = doc.internal.pageSize.getWidth();
        const imgHeight = (canvas.height * imgWidth) / canvas.width;

        doc.addImage(imgData, "PNG", 0, 0, imgWidth, imgHeight);

        // Métadonnées du PDF
        const patientNumber = props.patient?.numero || "unknown";
        const docType = previewDocumentTitle.value
            .toLowerCase()
            .replace(" ", "_");
        const consultationDate = selectedConsultation.value?.date_consultation
            ? formatDateLongue(selectedConsultation.value.date_consultation)
            : "unknown";
        const filename = `Consultation_${consultationDate}_${docType}_${patientNumber}.pdf`;

        doc.setProperties({
            title: `${previewDocumentTitle.value} - ${selectedConsultation.id}- ${patientNumber}`,
            subject: "Document médical officiel",
            author: "Medicare",
            keywords: "medical,certificat,medecin,ordonnance",
        });

        loadingMessage();
        message.success("PDF généré avec succès !");
        previewModalVisible.value = false;

        // Téléchargement
        doc.save(filename);
    } catch (error) {
        message.error("Erreur lors de la génération du PDF");
        console.error("Erreur de génération PDF :", error);
    }
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
    transform: translateX(5px);
    transition: transform 0.8s ease;
}

/* Style des séparateurs */
.border-gray-100 {
    border-color: #f3f4f6;
}
</style>
