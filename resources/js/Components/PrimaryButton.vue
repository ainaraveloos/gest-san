<template>
    <button
        :class="`inline-flex items-center rounded-md border border-transparent transition-colors  duration-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white  ease-in-out ${bgColor} hover:${hoverColor} focus:${focusColor} focus:outline-none focus:ring-2 focus:ring-offset-2 active:${activeColor}`"
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
});
</script>
