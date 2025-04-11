<template>
    <div
        class="bg-gradient-to-br from-white to-blue-50/20 mx-auto rounded-md p-8 shadow-xl shadow-blue-100/50 max-w-3xl"
    >
        <header
            class="text-center flex flex-col items-center justify-center space-y-2 mb-6"
        >
            <h1 class="text-xl font-bold text-gray-600 mb-2 animate-slide-in">
                <fonta icon="cog" class="mr-3 text-blue-500" />
                <span class="text-blue-500">Paramètres</span>
            </h1>
            <p class="text-gray-400 font-medium text-sm">
                Gestion des utilisateurs et préférences
            </p>
        </header>

        <!-- Système d'onglets -->
        <div class="mb-8">
            <a-tabs v-model:activeKey="activeTab" type="card" >
                <a-tab-pane key="newUser" tab="Nouvel Utilisateur">
                    <div class="pt-4">
                        <form @submit.prevent="handleSubmit" class="space-y-6">
                            <!-- Section Informations Personnelles -->
                            <section
                                class="bg-white p-6 rounded-md border border-gray-200/60 hover:shadow-sm transition-shadow"
                            >
                                <div class="flex items-center mb-5 space-x-3">
                                    <div
                                        class="px-3 py-2 bg-blue-100 rounded-md mb-2"
                                    >
                                        <fonta
                                            icon="info-circle"
                                            class="text-blue-600 text-lg"
                                        />
                                    </div>
                                    <h2
                                        class="text-lg font-semibold text-gray-600"
                                    >
                                        Informations Personnelles
                                    </h2>
                                </div>

                                <div
                                    class="bg-gray-50 p-5 rounded-lg border border-gray-200"
                                >
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-5"
                                    >
                                        <div>
                                            <InputLabel
                                                for="name"
                                                value="Nom"
                                            />
                                            <BaseInput
                                                id="name"
                                                type="text"
                                                v-model="form.name"
                                                required
                                            />
                                            <InputError
                                                class="mt-1"
                                                :message="form.errors.name"
                                            />
                                        </div>
                                        <div>
                                            <InputLabel
                                                for="prenom"
                                                value="Prénom"
                                            />
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
                                        <div class="md:col-span-2">
                                            <InputLabel
                                                for="email"
                                                value="Email"
                                            />
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
                            </section>

                            <!-- Sélection du Rôle -->
                            <section
                                class="bg-white p-6 rounded-md border border-gray-200/60 shadow-sm hover:shadow-sm transition-shadow"
                            >
                                <div class="flex items-center mb-5 space-x-3">
                                    <div
                                        class="p-2 bg-purple-100 rounded-md mb-2"
                                    >
                                        <fonta
                                            icon="user-tag"
                                            class="text-purple-600 text-lg"
                                        />
                                    </div>
                                    <h2
                                        class="text-lg font-semibold text-gray-600"
                                    >
                                        Rôle Utilisateur
                                    </h2>
                                </div>

                                <div
                                    class="bg-gray-50 p-4 rounded-lg border border-gray-200"
                                >
                                    <a-segmented
                                        v-model:value="form.role"
                                        :options="roleOptions"
                                        block
                                        class="custom-role-segmented"
                                    />
                                </div>
                            </section>

                            <!-- Section Médecin (Conditionnelle) -->
                            <transition name="slide-fade">
                                <section
                                    v-if="showMedecinFields"
                                    class="bg-white p-6 rounded-md border border-gray-200/60 hover:shadow-sm transition-all"
                                >
                                    <div
                                        class="flex items-center mb-5 space-x-3"
                                    >
                                        <div
                                            class="p-2 bg-indigo-100 rounded-md mb-2"
                                        >
                                            <fonta
                                                icon="stethoscope"
                                                class="text-indigo-600 text-lg"
                                            />
                                        </div>
                                        <h2
                                            class="text-lg font-semibold text-gray-600"
                                        >
                                            Informations sur le Médecin
                                        </h2>
                                    </div>

                                    <div
                                        class="bg-gray-50 p-5 rounded-lg border border-gray-200"
                                    >
                                        <div
                                            class="grid grid-cols-1 md:grid-cols-2 gap-5"
                                        >
                                            <div>
                                                <InputLabel
                                                    for="type"
                                                    value="Type de Médecin"
                                                    class="text-gray-600"
                                                />
                                                <a-select
                                                    id="type"
                                                    v-model:value="form.type"
                                                    placeholder="Sélectionnez un type"
                                                    :style="{ height: '38px' }"
                                                    class="w-full rounded-lg"
                                                >
                                                    <a-select-option
                                                        value="GENERALISTE"
                                                        >Généraliste</a-select-option
                                                    >
                                                    <a-select-option
                                                        value="ENTREPRISE"
                                                        >Entreprise</a-select-option
                                                    >
                                                </a-select>
                                                <InputError
                                                    class="mt-1"
                                                    :message="form.errors.type"
                                                />
                                            </div>
                                            <div>
                                                <InputLabel
                                                    for="specialite"
                                                    value="Spécialité"
                                                    class="text-gray-600"
                                                />
                                                <a-select
                                                    id="specialite"
                                                    v-model:value="
                                                        form.specialite
                                                    "
                                                    placeholder="Choisir une spécialité"
                                                    :style="{ height: '40px' }"
                                                    class="w-full rounded-lg"
                                                >
                                                    <a-select-option
                                                        v-for="specialite in specialites"
                                                        :key="specialite.value"
                                                        :value="
                                                            specialite.value
                                                        "
                                                    >
                                                        {{ specialite.label }}
                                                    </a-select-option>
                                                </a-select>
                                                <InputError
                                                    class="mt-1"
                                                    :message="
                                                        form.errors.specialite
                                                    "
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </transition>

                            <!-- Section Sécurité -->
                            <section
                                class="bg-white p-6 rounded-md border border-gray-200/60 hover:shadow-sm transition-shadow"
                            >
                                <div class="flex items-center mb-5 space-x-3">
                                    <div
                                        class="py-2 px-3 bg-green-100 rounded-md mb-2"
                                    >
                                        <fonta
                                            icon="lock"
                                            class="text-green-600 text-lg"
                                        />
                                    </div>
                                    <h2
                                        class="text-lg font-semibold text-gray-600"
                                    >
                                        Sécurité
                                    </h2>
                                </div>

                                <div
                                    class="bg-gray-50 p-5 rounded-lg border border-gray-200"
                                >
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-5"
                                    >
                                        <div>
                                            <InputLabel
                                                for="password"
                                                value="Mot de passe"
                                            />
                                            <BaseInput
                                                id="password"
                                                type="password"
                                                v-model="form.password"
                                                required
                                            />
                                            <InputError
                                                class="mt-1"
                                                :message="form.errors.password"
                                            />
                                        </div>
                                        <div>
                                            <InputLabel
                                                for="password_confirmation"
                                                value="Confirmation du mot de passe"
                                            />
                                            <BaseInput
                                                id="password_confirmation"
                                                type="password"
                                                v-model="
                                                    form.password_confirmation
                                                "
                                                required
                                            />
                                            <InputError
                                                class="mt-1"
                                                :message="
                                                    form.errors
                                                        .password_confirmation
                                                "
                                            />
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- Boutons d'action -->
                            <div
                                class="flex flex-col sm:flex-row justify-end gap-4 mt-8 pt-4 border-t border-gray-200"
                            >
                                <Button
                                    @click="handleCancel"
                                    class="bg-white hover:bg-gray-50 border-gray-300 text-gray-700 hover:text-gray-900"
                                >
                                    Annuler
                                </Button>
                                <BaseButton type="submit">
                                    <span>Créer l'utilisateur</span>
                                    <fonta
                                        icon="paper-plane"
                                        class="ml-2 group-hover:translate-x-1 transition-transform duration-500"
                                    />
                                </BaseButton>
                            </div>
                        </form>
                    </div>
                </a-tab-pane>
                <a-tab-pane key="myProfile" tab="Mes Informations">
                    <div class="pt-4">
                        <form @submit.prevent="updateProfile" class="space-y-6">
                            <!-- Section Informations Personnelles -->
                            <section
                                class="bg-white p-6 rounded-md border border-gray-200/60 hover:shadow-sm transition-shadow"
                            >
                                <div class="flex items-center mb-5 space-x-3">
                                    <div
                                        class="px-3 py-2 bg-blue-100 rounded-md mb-2"
                                    >
                                        <fonta
                                            icon="user-circle"
                                            class="text-blue-600 text-lg"
                                        />
                                    </div>
                                    <h2
                                        class="text-lg font-semibold text-gray-600"
                                    >
                                        Mes Informations Personnelles
                                    </h2>
                                </div>

                                <div
                                    class="bg-gray-50 p-5 rounded-lg border border-gray-200"
                                >
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-5"
                                    >
                                        <div>
                                            <InputLabel
                                                for="profile_name"
                                                value="Nom"
                                            />
                                            <BaseInput
                                                id="profile_name"
                                                type="text"
                                                v-model="profileForm.name"
                                                required
                                            />
                                            <InputError
                                                class="mt-1"
                                                :message="
                                                    profileForm.errors.name
                                                "
                                            />
                                        </div>
                                        <div>
                                            <InputLabel
                                                for="profile_prenom"
                                                value="Prénom"
                                            />
                                            <BaseInput
                                                id="profile_prenom"
                                                type="text"
                                                v-model="profileForm.prenom"
                                                required
                                            />
                                            <InputError
                                                class="mt-1"
                                                :message="
                                                    profileForm.errors.prenom
                                                "
                                            />
                                        </div>
                                        <div class="md:col-span-2">
                                            <InputLabel
                                                for="profile_email"
                                                value="Email"
                                            />
                                            <BaseInput
                                                id="profile_email"
                                                type="email"
                                                v-model="profileForm.email"
                                                required
                                            />
                                            <InputError
                                                class="mt-1"
                                                :message="
                                                    profileForm.errors.email
                                                "
                                            />
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- Section Modifier le mot de passe -->
                            <section
                                class="bg-white p-6 rounded-md border border-gray-200/60 hover:shadow-sm transition-shadow"
                            >
                                <div class="flex items-center mb-5 space-x-3">
                                    <div
                                        class="py-2 px-3 bg-green-100 rounded-md mb-2"
                                    >
                                        <fonta
                                            icon="lock"
                                            class="text-green-600 text-lg"
                                        />
                                    </div>
                                    <h2
                                        class="text-lg font-semibold text-gray-600"
                                    >
                                        Modifier mon mot de passe
                                    </h2>
                                </div>

                                <div
                                    class="bg-gray-50 p-5 rounded-lg border border-gray-200"
                                >
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-5"
                                    >
                                        <div>
                                            <InputLabel
                                                for="current_password"
                                                value="Mot de passe actuel"
                                            />
                                            <BaseInput
                                                id="current_password"
                                                type="password"
                                                v-model="
                                                    profileForm.current_password
                                                "
                                            />
                                            <InputError
                                                class="mt-1"
                                                :message="
                                                    profileForm.errors
                                                        .current_password
                                                "
                                            />
                                        </div>
                                        <div
                                            class="md:col-span-2 border-t md:border-t-0 pt-4 md:pt-0"
                                        >
                                            <p
                                                class="text-xs  text-gray-500 mb-2 "
                                            >
                                                Laissez vide si vous ne
                                                souhaitez pas modifier votre mot
                                                de passe
                                            </p>
                                            <div
                                                class="grid grid-cols-1 md:grid-cols-2 gap-5"
                                            >
                                                <div>
                                                    <InputLabel
                                                        for="new_password"
                                                        value="Nouveau mot de passe"
                                                    />
                                                    <BaseInput
                                                        id="new_password"
                                                        type="password"
                                                        v-model="
                                                            profileForm.password
                                                        "
                                                    />
                                                    <InputError
                                                        class="mt-1"
                                                        :message="
                                                            profileForm.errors
                                                                .password
                                                        "
                                                    />
                                                </div>
                                                <div>
                                                    <InputLabel
                                                        for="new_password_confirmation"
                                                        value="Confirmer le nouveau mot de passe"
                                                    />
                                                    <BaseInput
                                                        id="new_password_confirmation"
                                                        type="password"
                                                        v-model="
                                                            profileForm.password_confirmation
                                                        "
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- Boutons d'action -->
                            <div
                                class="flex flex-col sm:flex-row justify-end gap-4 mt-8 pt-4 border-t border-gray-200"
                            >
                                <Button
                                    @click="resetProfileForm"
                                    class="bg-white hover:bg-gray-50 border-gray-300 text-gray-700 hover:text-gray-900"
                                    type="button"
                                >
                                    Annuler
                                </Button>
                                <BaseButton
                                    type="submit"
                                    :disabled="!profileChanged"
                                    :class="{
                                        'opacity-50 cursor-not-allowed':
                                            !profileChanged,
                                    }"
                                >
                                    Mettre à jour mon profil
                                    <fonta
                                        icon="save"
                                        class="ml-2 group-hover:translate-x-1 transition-transform duration-500"
                                    />
                                </BaseButton>
                            </div>
                        </form>
                    </div>
                </a-tab-pane>
            </a-tabs>
        </div>
    </div>
</template>

<script setup>
import BaseButton from "@/Components/BaseButton.vue";
import BaseInput from "@/Components/BaseInput.vue";
import Button from "@/Components/Button.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Swal from "sweetalert2";

import TestLayout from "@/Layouts/TestLayout.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { computed, defineOptions, onMounted, ref, watch } from "vue";

defineOptions({
    layout: TestLayout,
});

// Onglet actif
const activeTab = ref("newUser");

// Formulaire pour créer un utilisateur
const form = useForm({
    name: "",
    prenom: "",
    email: "",
    password: "",
    password_confirmation: "",
    role: "admin",
    type: "",
    specialite: "",
});

// Formulaire pour mettre à jour le profil
const profileForm = useForm({
    name: "",
    prenom: "",
    email: "",
    current_password: "",
    password: "",
    password_confirmation: "",
});

// Récupérer les informations de l'utilisateur connecté
const user = usePage().props.auth.user;

// Stocker les valeurs initiales
const originalUserData = ref({
    name: "",
    prenom: "",
    email: "",
});

onMounted(() => {
    // Pré-remplir le formulaire avec les informations de l'utilisateur
    if (user) {
        profileForm.name = user.name || "";
        profileForm.prenom = user.prenom || "";
        profileForm.email = user.email || "";

        // Stocker les valeurs originales pour comparaison
        originalUserData.value = {
            name: user.name || "",
            prenom: user.prenom || "",
            email: user.email || "",
        };
    }
});

const roleOptions = [
    {
        label: "Administrateur",
        value: "admin",
    },
    {
        label: "Médecin",
        value: "medecin",
    },
];
const showMedecinFields = ref(form.role === "medecin");
const specialites = [
    { value: "cardiologie", label: "Cardiologie" },
    { value: "dermatologie", label: "Dermatologie" },
    { value: "pediatrie", label: "Pédiatrie" },
    { value: "neurologie", label: "Neurologie" },
];
watch(
    () => form.role,
    (newRole) => {
        showMedecinFields.value = newRole === "medecin";
        // Réinitialiser les champs spécifiques aux médecins
        if (newRole === "admin") {
            form.specialite = "";
            form.type = "";
        }
    }
);

const handleSubmit = () => {
    form.post(route("register.store"), {
        onSuccess: () => {
            form.reset();

            Swal.fire({
                title: "Ajout !",
                text: "Utilisateur ajouté avec succès",
                icon: "success",
                confirmButtonColor: "#3b82f6",
            });
        },
        onError: (errors) => {
            form.reset("password", "password_confirmation");
            console.log(errors);
        },
        preserveScroll: true,
    });
};
const handleCancel = () => {
    form.reset();
};

const updateProfile = () => {
    profileForm.patch(route("admin.profile.update"), {
        onSuccess: () => {
            profileForm.reset(
                "current_password",
                "password",
                "password_confirmation"
            );

            Swal.fire({
                title: "Mis à jour !",
                text: "Votre profil a été mis à jour avec succès",
                icon: "success",
                confirmButtonColor: "#3b82f6",
            });
        },
        onError: (errors) => {
            console.log(errors);
        },
        preserveScroll: true,
    });
};

// Calculer si le profil a été modifié
const profileChanged = computed(() => {
    // Vérifier si les champs principaux ont changé
    const basicInfoChanged =
        profileForm.name !== originalUserData.value.name ||
        profileForm.prenom !== originalUserData.value.prenom ||
        profileForm.email !== originalUserData.value.email;

    // Vérifier si un nouveau mot de passe a été saisi
    const newPasswordEntered =
        profileForm.password || profileForm.password_confirmation;

    // Un changement est considéré valide seulement si:
    // - Les informations de base ont changé, OU
    // - Un nouveau mot de passe a été saisi (pas seulement le mot de passe actuel)
    return basicInfoChanged || newPasswordEntered;
});

const resetProfileForm = () => {
    profileForm.reset("current_password", "password", "password_confirmation");

    // Réinitialiser avec les données originales de l'utilisateur
    profileForm.name = originalUserData.value.name;
    profileForm.prenom = originalUserData.value.prenom;
    profileForm.email = originalUserData.value.email;
};
</script>

<style>
@keyframes slideIn {
    0% {
        transform: translateY(-20px);
        opacity: 0;
    }
    100% {
        transform: translateY(0);
        opacity: 1;
    }
}

.animate-slide-in {
    animation: slideIn 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
}

.custom-role-segmented .ant-segmented-item-label {
    @apply py-2 px-6 text-base font-medium transition-all duration-300;
}

.custom-role-segmented .ant-segmented-item-selected {
    @apply bg-blue-500 text-white shadow-md !important;
}

.slide-fade-enter-active {
    transition: all 0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55);
}

.slide-fade-leave-active {
    transition: all 0.3s cubic-bezier(0.55, 0.085, 0.68, 0.53);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    opacity: 0;
    transform: translateY(-10px) scale(0.98);
}

.ant-select-selector {
    @apply rounded-lg !important;
}

.ant-select-item-option-selected {
    @apply bg-blue-50/50 !important;
}



/* Style pour les onglets */
.ant-tabs-card.ant-tabs-top > .ant-tabs-nav .ant-tabs-tab {
    @apply px-4 py-2 rounded-none bg-gray-100;
}
.ant-tabs-card.ant-tabs-top > .ant-tabs-nav .ant-tabs-tab:last-child {
    @apply !rounded-tr-3xl;
}
.ant-tabs-card.ant-tabs-top > .ant-tabs-nav .ant-tabs-tab:first-child {
    @apply rounded-tl-3xl !important;
}

.ant-tabs .ant-tabs-tab-btn {
    @apply text-base font-semibold !important;
}
.ant-tabs .ant-tabs-tab.ant-tabs-tab-active .ant-tabs-tab-btn {
    @apply text-white !important;
}
.ant-tabs-card > .ant-tabs-nav .ant-tabs-tab-active,
:where(.css-dev-only-do-not-override-1p3hq3p).ant-tabs-card
    > div
    > .ant-tabs-nav
    .ant-tabs-tab-active {
    @apply !bg-blue-500 text-white transition-all duration-500 !important;
}
.ant-tabs > .ant-tabs-nav .ant-tabs-nav-list {
    @apply gap-1 !important;
}
</style>
