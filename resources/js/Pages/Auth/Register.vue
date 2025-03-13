<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    role: "admin",
    specialite: "",
    type: "",
});

const showMedecinFields = ref(form.role === "medecin");

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

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="role" value="Rôle" class="mb-1" />
                <select
                    id="role"
                    v-model="form.role"
                    class="block w-full border-gray-300 rounded-md shadow-sm"
                >
                    <option value="medecin">Médecin</option>
                    <option value="admin">Admin</option>
                </select>
                <InputError :message="form.errors.role" />
            </div>

            <div v-if="showMedecinFields" class="mt-4">
                <div class="flex gap-4">
                    <!-- Champ Spécialité -->
                    <div class="w-1/2">
                        <InputLabel
                            for="specialite"
                            value="Spécialité"
                            class="mb-1"
                        />
                        <TextInput
                            id="specialite"
                            v-model="form.specialite"
                            class="w-full"
                        />
                        <InputError :message="form.errors.specialite" />
                    </div>

                    <!-- Champ Type -->
                    <div class="w-1/2">
                        <InputLabel
                            for="type"
                            value="Type de Médecin"
                            class="mb-1"
                        />
                        <select
                            id="type"
                            v-model="form.type"
                            class="w-full border-gray-300 rounded-md shadow-sm"
                        >
                            <option value="GENERALISTE">Généraliste</option>
                            <option value="ENTREPRISE">
                                Médecin d'entreprise
                            </option>
                        </select>
                        <InputError :message="form.errors.type" />
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Already registered?
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
