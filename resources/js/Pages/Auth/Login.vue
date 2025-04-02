<template>
    <div
        class="min-h-screen bg-gradient-to-br from-blue-50 to-cyan-50/30 flex items-center justify-center"
    >
        <div
            class="w-full max-w-5xl bg-white rounded-xl shadow-xl shadow-blue-100/50 flex flex-col lg:flex-row"
        >
            <!-- Left Section -->
            <div class="w-full lg:w-1/2 p-8 lg:p-12 xl:p-14">
                <!-- Logo -->
                <div
                    class="flex items-center justify-center gap-3 mb-10 animate-slide-in"
                >
                    <img
                        src="../../../assets/logo.svg"
                        class="w-16 h-16 text-red-400 transition-transform hover:scale-105 logo-animation"
                    />
                    <span
                        class="text-4xl font-bold text-blue-500 logo-animation"
                    >
                        medicare
                    </span>
                </div>

                <!-- Formulaire -->
                <form class="space-y-6" @submit.prevent="submit">
                    <!-- Email -->
                    <div>
                        <InputLabel
                            for="email"
                            value="Email"
                            class="text-gray-700 font-medium"
                        />
                        <div class="relative mt-2">
                            <font-awesome-icon
                                icon="envelope"
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm z-10"
                            />
                            <BaseInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full p-2 border rounded-lg border-gray-300 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                v-model="form.email"
                                required
                                autofocus
                                placeholder="exemple@medicare.com"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.email"
                            />
                        </div>
                    </div>

                    <!-- Mot de passe -->
                    <div>
                        <InputLabel
                            for="password"
                            value="Mot de passe"
                            class="text-gray-700 font-medium"
                        />
                        <div class="relative mt-2">
                            <font-awesome-icon
                                icon="lock"
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm z-10"
                            />
                            <BaseInput
                                id="password"
                                type="password"
                                v-model="form.password"
                                required
                                placeholder="••••••••"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.password"
                            />
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center space-x-2">
                            <Checkbox
                                name="remember"
                                v-model:checked="form.remember"
                                class="text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            />
                            <span class="text-sm text-gray-600"
                                >Se souvenir de moi</span
                            >
                        </label>

                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm font-medium bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent hover:from-blue-500 hover:to-cyan-400 transition-all"
                        >
                            Mot de passe oublié ?
                        </Link>
                    </div>

                    <!-- Bouton -->
                    <BaseButton :disabled="form.processing" class="py-2">
                        <fonta icon="sign-in-alt" class=" " />
                        <span>Connexion</span>
                        <fonta
                            v-if="form.processing"
                            icon="spinner"
                            class="animate-spin disabled:opacity-70 disabled:bg-gray-400 disabled:bg-none"
                        />
                    </BaseButton>
                </form>
            </div>

            <!-- Right Section -->
            <div
                class="hidden lg:flex flex-1 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-r-2xl items-center justify-center p-8"
            >
                <div class="max-w-md w-full">
                    <img
                        :src="backgroundImage"
                        alt="Connexion sécurisée"
                        class="w-full h-auto animate-float scale-animation"
                    />
                    <h2
                        class="detail_animation text-2xl font-bold text-blue-500 mt-8 text-center"
                    >
                        Plateforme Médicale Sécurisée
                    </h2>
                    <p
                        class="sub_detail_animation text-gray-500 text-center mt-2"
                    >
                        Accédez à votre espace professionnel en toute sécurité
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { Link, useForm } from "@inertiajs/vue3";

import backgroundImage from "@/../assets/login.svg";
import BaseButton from "@/Components/BaseButton.vue";
import BaseInput from "@/Components/BaseInput.vue";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<style>
.logo-animation {
    animation: slideIn 1s ease-out forwards;
}
.detail_animation {
    animation: slideIn 0.8s ease-out forwards;
}
.sub_detail_animation {
    animation: slideIn 1s ease-out forwards;
}

.scale-animation {
    animation: scaleIn 1s ease-out forwards;
}

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

@keyframes scaleIn {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}
</style>
