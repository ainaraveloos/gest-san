<template>
    <a-dropdown :trigger="['click']" overlay-class-name="sort-dropdown">
        <div
            class="flex items-center gap-2 bg-gray-50 hover:bg-gray-100 transition-all duration-300 px-4 py-3 rounded-lg border border-gray-200 cursor-pointer shadow-sm hover:shadow"
        >
            <component
                :is="getSortIcon()"
                class="text-blue-500 transition-transform duration-300"
            />
            <span class="text-gray-500 font-normal sm:block hidden">{{
                getSortLabel()
            }}</span>
            <DownOutlined
                class="text-gray-400 text-xs transition-all duration-300"
            />
        </div>

        <template #overlay>
            <a-menu class="sort-menu">
                <div class="p-3 border-b border-gray-100 bg-gray-50">
                    <h4 class="font-semibold text-gray-700">Trier par</h4>
                </div>
                <a-menu-item
                    v-for="option in sortOptions"
                    :key="option.value"
                    @click="changeSortOrder(option.value)"
                    :class="
                        currentSort === option.value
                            ? 'ant-menu-item-active bg-blue-50'
                            : ''
                    "
                >
                    <div class="flex items-center p-1 gap-2">
                        <component
                            :is="getSortIcon(option.value)"
                            class="text-sm"
                            :class="
                                currentSort === option.value
                                    ? 'text-blue-500'
                                    : 'text-gray-400'
                            "
                        />
                        <span
                            :class="
                                currentSort === option.value
                                    ? 'text-blue-600 font-medium'
                                    : 'text-gray-600'
                            "
                        >
                            {{ option.label }}
                        </span>
                        <CheckOutlined
                            v-if="currentSort === option.value"
                            class="ml-auto text-blue-500"
                        />
                    </div>
                </a-menu-item>
            </a-menu>
        </template>
    </a-dropdown>
</template>

<script setup>
import {
    CheckOutlined,
    DownOutlined,
    SortAscendingOutlined,
} from "@ant-design/icons-vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    sortOptions: {
        type: Array,
        required: true,
    },
    currentSort: {
        type: String,
        default: "",
    },
    routeName: {
        type: String,
        required: true,
    },
    additionalParams: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(["sort-changed"]);

// Fonction pour obtenir le label du tri actuel
const getSortLabel = () => {
    if (!props.currentSort) return "Trier par";
    const option = props.sortOptions.find(
        (opt) => opt.value === props.currentSort
    );
    return option ? option.label : "Trier par";
};

// Fonction pour obtenir l'icône correspondant au type de tri
const getSortIcon = (value) => {
    if (!value && !props.currentSort) return SortAscendingOutlined;

    const option = props.sortOptions.find(
        (opt) => opt.value === (value || props.currentSort)
    );
    return option && option.icon ? option.icon : SortAscendingOutlined;
};

// Fonction pour changer l'ordre de tri
const changeSortOrder = (value) => {
    // Émettre l'événement pour informer le parent du changement
    emit("sort-changed", value);

    // Préparer les paramètres de route
    let routeParams = { sort: value };

    // Si des paramètres additionnels sont fournis, les fusionner avec le paramètre de tri
    if (props.additionalParams) {
        // Créer une copie pour éviter de modifier l'original
        routeParams = { ...props.additionalParams, sort: value };
    }

    // Naviguer vers la même page avec les paramètres
    router.get(route(props.routeName), routeParams, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};
</script>

<style>
/* Styles pour le dropdown de tri */
:deep(.sort-dropdown .ant-dropdown-menu) {
    padding: 0;
    border-radius: 0.75rem;
    min-width: 220px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

:deep(.sort-dropdown .ant-menu-item) {
    height: auto !important;
    line-height: 1.5;
    padding: 8px 12px !important;
    margin: 4px !important;
    border-radius: 6px;
    transition: all 0.2s ease;
}

:deep(.sort-dropdown .ant-menu-item:hover) {
    background-color: #f5f7fa;
}

:deep(.sort-dropdown .ant-menu-item.bg-blue-50) {
    background-color: rgb(239, 246, 255) !important;
}

:deep(.sort-dropdown .ant-menu-item.ant-menu-item-active) {
    background-color: rgb(239, 246, 255);
}
</style>
