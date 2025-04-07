<template>
    <button
    :disabled="disabled"
        :class="`group inline-flex gap-2 disabled:bg-gray-200 items-center rounded-md border disabled:cursor-not-allowed  border-transparent transition-colors duration-500   px-4 py-1 text-xs font-semibold uppercase tracking-widest text-white ease-in-out ${bgColor} hover:${hoverColor} focus:${focusColor} focus:outline-none focus:ring-2 focus:ring-offset-2 active:${activeColor}`"
    >
        <slot />
    </button>
</template>
<script setup>
import { defineProps } from "vue";


const props = defineProps({
    bgColor: {
        type: String,
        default: "bg-blue-500",
    },
    hoverColor: {
        type: String,
        default: "bg-blue-600",
    },
    focusColor: {
        type: String,
        default: "ring-blue-400",
    },
    activeColor: {
        type: String,
        default: "bg-blue-800",
    },
    disabled: Boolean,

});
</script>
