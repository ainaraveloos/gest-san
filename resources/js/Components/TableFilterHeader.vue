<template>
    <div class="relative flex items-center justify-start gap-4">
        <span class="mr-1">{{ label }}</span>
        <a-dropdown v-model:open="dropdownVisible" :trigger="['click']">
            <FilterFilled
                class="hover:text-white hover:shadow-md transition-colors duration-300 text-gray-300"
            />

            <template #overlay>
                <div
                    class="bg-white rounded-lg shadow-xl border border-gray-100"
                    :class="[widthClass]"
                >
                    <div class="p-4 border-b border-gray-100">
                        <h4 class="font-semibold text-gray-800">
                            {{ filterTitle }}
                        </h4>
                    </div>

                    <div class="p-2 max-h-60 overflow-y-auto">
                        <a-checkbox-group
                            :value="modelValue"
                            @update:value="handleChange"
                            class="flex flex-col gap-2"
                        >
                            <a-checkbox
                                v-for="option in options"
                                :key="option.value"
                                :value="option.value"
                                class="!flex items-center p-2 hover:bg-gray-50 rounded-md transition-colors"
                            >
                                <div
                                    v-if="showOptionIcon"
                                    class="flex items-center gap-2"
                                >
                                    <div
                                        v-if="option.color"
                                        :class="[
                                            'w-3 h-3 rounded-full',
                                            `bg-${option.color}-500`,
                                            option.pulse
                                                ? `pulse-${option.color}`
                                                : '',
                                        ]"
                                    ></div>
                                    <span class="text-gray-700">{{
                                        option.label
                                    }}</span>
                                </div>
                                <span v-else class="text-gray-700">{{
                                    option.label
                                }}</span>
                            </a-checkbox>
                        </a-checkbox-group>
                    </div>

                    <div class="p-2 border-t border-gray-100 flex justify-end">
                        <a-button
                            type="link"
                            size="small"
                            @click="resetFilter"
                            class="!text-gray-500 hover:!text-red-500 !h-8"
                        >
                            Réinitialiser
                        </a-button>
                    </div>
                </div>
            </template>
        </a-dropdown>
    </div>
</template>

<script setup>
import { FilterFilled } from "@ant-design/icons-vue";
import { ref } from "vue";

// Props du composant
const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    filterTitle: {
        type: String,
        required: true,
    },
    options: {
        type: Array,
        required: true,
    },
    modelValue: {
        type: Array,
        required: true,
    },
    showOptionIcon: {
        type: Boolean,
        default: false,
    },
    widthClass: {
        type: String,
        default: "w-56",
    },
    alignCenter: {
        type: Boolean,
        default: false,
    },
});

// Événements émis
const emit = defineEmits(["update:modelValue", "change"]);

// État local du dropdown
const dropdownVisible = ref(false);

// Fonction pour réinitialiser le filtre
const resetFilter = () => {
    emit("update:modelValue", []);
};

// Fonction pour gérer le changement de valeur
const handleChange = (newValue) => {
    emit("update:modelValue", newValue);
    emit("change", newValue);
};
</script>

<style scoped>
/* Les styles sont délibérément vides car le composant utilise les classes Tailwind */
</style>
