<template>
    <div v-if="links.length > 3" class="mt-4">
        <!-- En-tête de pagination avec informations de page (si disponible) -->
        <div
            v-if="showPageInfo && currentPage && lastPage"
            class="flex flex-wrap px-4 py-3 items-center justify-between border-t border-gray-200"
        >
            <div class="flex items-center space-x-2">
                <span class="text-base font-semibold text-gray-500">
                    Page {{ currentPage }} sur {{ lastPage }}
                </span>
            </div>
        </div>

        <!-- Liens de pagination -->
        <div class="flex flex-wrap items-center justify-end">
            <div v-for="(link, linkIndex) in links" :key="linkIndex">
                <div
                    v-if="link.url === null"
                    class="mr-1 mb-1 shadow-sm transition-colors duration-300 px-4 py-3 leading-4 text-gray-400 border rounded"
                    v-html="link.label"
                ></div>
                <Link
                    v-else
                    :preserve-state="true"
                    :preserve-scroll="true"
                    class="mr-1 shadow-sm transition-all duration-300 hover:scale-110 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-blue-500 hover:text-white focus:border-blue-500 inline-block px-4 py-3 focus:text-blue-500"
                    :class="{
                        'bg-slate-600 !text-white border-none': link.active,
                    }"
                    :href="link.url"
                    @click.prevent="navigate(link.url)"
                >
                    <span v-html="link.label"></span>
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    links: {
        type: Array,
        required: true,
    },
    onNavigate: {
        type: Function,
        required: true,
    },
    currentPage: {
        type: Number,
        default: null,
    },
    lastPage: {
        type: Number,
        default: null,
    },
    showPageInfo: {
        type: Boolean,
        default: false,
    },
});

const navigate = (url) => {
    props.onNavigate(url);
};
</script>

<style scoped>
/* Animation pour hover et effet de clic */
.hover\:scale-110:hover {
    transform: scale(1.1);
    transition: transform 0.3s ease;
}
</style>
