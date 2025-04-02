<template>
    <div
        class="bg-gradient-to-br from-white to-blue-50/20 mx-auto rounded-md p-8 shadow-xl shadow-blue-100/50 space-y-8 max-w-3xl"
    >
        <!-- En-tête -->
        <header class="text-center space-y-2">
            <div class="animate-slide-in">
                <fonta
                    icon="user-md"
                    class="text-5xl mb-4 text-blue-500 transition-transform hover:scale-105"
                />
            </div>
            <h1 class="text-2xl font-bold text-gray-800">
                <span class="text-blue-500"> Paramètres du Compte </span>
            </h1>
            <p class="text-gray-400 font-medium">
                Gestion des informations professionnelles
            </p>
        </header>

        <!-- Informations médecin -->
        <section
            class="bg-indigo-50/30 p-6 rounded-md border border-indigo-200 backdrop-blur-sm hover:shadow-sm transition-all duration-500 hover:-translate-y-2"
        >
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <!-- Nom -->
                <div
                    class="flex items-center justify-center lg:justify-start gap-3"
                >
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <fonta icon="id-card" class="text-blue-600 text-lg" />
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Médecin</p>
                        <p class="text-base font-semibold text-gray-600">
                            {{ doctor.nom.toUpperCase() }} {{ doctor.prenom }}
                        </p>
                    </div>
                </div>

                <!-- Spécialité -->
                <div
                    class="flex items-center justify-center lg:justify-start gap-3"
                >
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
                <div
                    class="flex items-center justify-center lg:justify-start gap-3"
                >
                    <div class="p-3 bg-cyan-100 rounded-lg">
                        <fonta icon="user-tag" class="text-cyan-600 text-lg" />
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
        </section>

        <!-- Formulaire -->
        <form @submit.prevent="updateParameters" class="space-y-6">
            <!-- Email -->
            <section
                class="bg-white p-6 rounded-md border border-gray-200/60 hover:shadow-sm transition-shadow"
            >
                <div class="space-y-2">
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
                        <InputError :message="form.errors.email" class="mt-1" />
                    </div>
                </div>
            </section>

            <!-- Mot de passe -->
            <section
                class="bg-white p-6 rounded-md border border-gray-200/60 hover:shadow-sm transition-shadow"
            >
                <div class="space-y-4">
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
            </section>

            <!-- Bouton -->
            <div class="flex justify-end">
                <PrimaryButton
                    type="submit"
                    :disabled="!isEmailChanged && !isPasswordChanged"
                    class="w-full sm:w-auto px-8 py-3 bg-blue-500 disabled:opacity-70 disabled:bg-gray-400 disabled:bg-none transition-all"
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
                            class="animate-spin text-sm ml-2"
                        />
                    </div>
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>

<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Swal from "sweetalert2";
import TestLayout from "@/Layouts/TestLayout.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { message } from "ant-design-vue";
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

const updateParameters = () => {
    form.post(route("medecin.parametre.update"), {
        onSuccess: () => {
            form.reset("password", "password_confirmation");
            originalEmail.value = form.email;
            Swal.fire({
                            title: "Mise à jour !",
                            text: "Information mis à jour avec succès",
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
