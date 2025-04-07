<template>
    <div
        class="bg-gradient-to-br from-white to-blue-50/20 mx-auto rounded-lg p-8 shadow-xl shadow-blue-100/50 max-w-[800px]"
    >
        <header class="mb-6">
            <div class="flex items-center gap-3 mb-2">
                <div class="animate-slide-in">
                    <fonta
                        icon="user-md"
                        class="text-3xl text-blue-500 transition-transform hover:scale-105"
                    />
                </div>
                <h1 class="font-bold text-2xl text-gray-600">
                    <span class="text-blue-500">Paramètres du Compte</span>
                </h1>
            </div>
            <p class="text-gray-400 font-medium">
                Gestion des informations professionnelles
            </p>
        </header>

        <!-- Informations médecin -->
        <div class="mb-6">
            <h2
                class="text-md text-gray-600 font-semibold mb-4 flex items-center"
            >
                <div class="p-2 bg-indigo-100 rounded-md mr-2">
                    <fonta icon="info-circle" class="text-blue-500 text-lg" />
                </div>
                Informations Médecin
            </h2>
            <div
                class="bg-indigo-50/30 p-5 rounded-lg border border-indigo-200 backdrop-blur-sm hover:shadow-md transition-all duration-500 hover:-translate-y-1"
            >
                <div class="flex flex-wrap items-center justify-between gap-6">
                    <!-- Nom -->
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <fonta
                                icon="id-card"
                                class="text-blue-600 text-lg"
                            />
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Médecin</p>
                            <p class="text-base font-semibold text-gray-600">
                                {{ doctor.nom.toUpperCase() }}
                                {{ doctor.prenom }}
                            </p>
                        </div>
                    </div>

                    <!-- Spécialité -->
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <fonta
                                icon="stethoscope"
                                class="text-purple-600 text-lg"
                            />
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Spécialité</p>
                            <p class="text-base font-semibold text-gray-600">
                                {{ doctor.specialite }}
                            </p>
                        </div>
                    </div>

                    <!-- Type -->
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-cyan-100 rounded-lg">
                            <fonta
                                icon="user-tag"
                                class="text-cyan-600 text-lg"
                            />
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Type</p>
                            <p
                                class="text-base font-semibold text-gray-600 capitalize"
                            >
                                {{ doctor.type }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire -->
        <form @submit.prevent="updateParameters" class="space-y-6">
            <!-- Email -->
            <div class="mb-6">

                <div
                    class="bg-white p-5 rounded-lg border border-gray-200/60 hover:shadow-sm transition-shadow"
                >
                    <div>
                        <InputLabel
                            for="email"
                            value="Email"
                            class="text-gray-700 font-medium"
                        />
                        <div class="relative">
                            <fonta
                                icon="envelope"
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm z-10"
                            />
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                class="mt-1 block w-full pl-10 p-2 border rounded-lg border-gray-300 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                            />
                            <InputError
                                :message="form.errors.email"
                                class="mt-1"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mot de passe -->
            <div class="mb-6">
                <h2
                    class="text-md text-gray-600 font-semibold mb-4 flex items-center"
                >
                    <div class="p-2 bg-green-100 rounded-md mr-2">
                        <fonta icon="lock" class="text-green-600 text-lg" />
                    </div>
                    Sécurité
                </h2>
                <div
                    class="bg-white p-5 rounded-lg border border-gray-200/60 hover:shadow-sm transition-shadow"
                >
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nouveau mot de passe -->
                        <div>
                            <InputLabel
                                for="password"
                                value="Nouveau mot de passe"
                                class="text-gray-600 mb-1 font-medium"
                            />
                            <div class="relative">
                                <fonta
                                    icon="lock"
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm z-10"
                                />
                                <input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    class="mt-1 block w-full pl-10 p-2 border rounded-lg border-gray-300 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                    placeholder="••••••••"
                                />
                                <InputError
                                    :message="form.errors.password"
                                    class="mt-1"
                                />
                            </div>
                        </div>

                        <!-- Confirmation -->
                        <div>
                            <InputLabel
                                for="password_confirmation"
                                value="Confirmation"
                                class="text-gray-600 mb-1 font-medium"
                            />
                            <div class="relative">
                                <input
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    type="password"
                                    class="mt-1 block w-full p-2 border rounded-lg border-gray-300 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                />
                                <InputError
                                    :message="form.errors.password_confirmation"
                                    class="mt-1"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div
                class="flex justify-end space-x-3 pt-4 border-t border-gray-200"
            >
                <Button
                    type="button"
                    @click="reset"
                    class="bg-white hover:bg-gray-50 border-gray-300 text-gray-700 hover:text-gray-900"
                >
                    Annuler
                </Button>
                <BaseButton
                    type="submit"
                    :disabled="!isEmailChanged && !isPasswordChanged"
                    class="px-6 bg-blue-500 group disabled:opacity-70 disabled:bg-gray-400 disabled:bg-none"
                >
                    <div class="flex items-center gap-2">
                        <fonta
                            icon="save"
                            class="text-sm transition-transform group-hover:scale-110"
                        />
                        <span>Mettre à jour</span>
                        <fonta
                            v-if="form.processing"
                            icon="spinner"
                            class="animate-spin text-sm"
                        />
                    </div>
                </BaseButton>
            </div>
        </form>
    </div>
</template>

<script setup>
import BaseButton from "@/Components/BaseButton.vue";
import Button from "@/Components/Button.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TestLayout from "@/Layouts/TestLayout.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { computed, defineOptions, ref } from "vue";

const page = usePage();

defineOptions({
    layout: TestLayout,
});

const props = defineProps({
    doctor: Object,
});

const form = useForm({
    email: page.props.auth.user.email || "",
    password: "",
    password_confirmation: "",
});

// Stocker l'email d'origine
const originalEmail = ref(form.email);

// Computed property pour vérifier si l'email a changé
const isEmailChanged = computed(() => {
    return form.email !== originalEmail.value;
});

// Computed property pour vérifier si le mot de passe a été modifié
const isPasswordChanged = computed(() => {
    return form.password !== "" || form.password_confirmation !== "";
});

const reset = () => {
    form.email = originalEmail.value;
    form.reset("password", "password_confirmation");
};

const updateParameters = () => {
    form.post(route("medecin.parametre.update"), {
        onSuccess: () => {
            form.reset("password", "password_confirmation");
            originalEmail.value = form.email;
            Swal.fire({
                title: "Mise à jour !",
                text: "Informations mises à jour avec succès",
                icon: "success",
                confirmButtonColor: "#3b82f6",
            });
        },
        onError: (e) => {
            form.reset("password", "password_confirmation");
            console.log(e);
        },
    });
};
</script>

<style scoped>
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

.bg-indigo-50\/30 {
    background-color: rgba(238, 242, 255, 0.3);
}

.backdrop-blur-sm {
    backdrop-filter: blur(4px);
}

.group-hover\:scale-110 {
    transition: transform 0.3s ease;
}
</style>
