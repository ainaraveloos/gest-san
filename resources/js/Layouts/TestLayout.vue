<template>
    <!-- Sidebar  -->
    <a-layout>
        <a-layout-sider
            v-model:collapsed="collapsed"
            :trigger="null"
            collapsible
            :breakpoint="'md'"
            :style="{
                overflow: 'auto',
                height: '100vh',
                position: 'fixed',
                left: 0,
                top: 0,
                bottom: 0,
            }"
            :width="240"
            :class="[
                ' z-10  shadow-lg ',
                'transition-all duration-300 ease-in-out',
            ]"
        >
            <div class="h-16 flex items-center justify-center mb-8">
                <h1
                    class="text-3xl mt-4 flex items-center justify-center font-semibold text-slate-600"
                >
                    <img src="../../assets/logo.svg" alt="" class="w-16" />
                    <span v-if="!collapsed">
                        <span class="text-blue-500">Medi</span>care</span
                    >
                </h1>
            </div>

            <a-menu mode="inline" class="!border-r-0 flex flex-col !gap-4">
                <template v-for="item in asideMenu" :key="item.key">
                    <!-- Sous-menu -->

                    <a-sub-menu v-if="item.children" :key="item.key">
                        <template #title>
                            <div class="flex items-center gap-2">
                                <component :is="item.icon" class="!text-xl" />
                                <span>{{ item.label }}</span>
                            </div>
                        </template>

                        <!-- Items enfants -->
                        <a-menu-item
                            v-for="child in item.children"
                            :key="child.key"
                            class="!flex items-center justify-between !py-4 text-sm font-medium !gap-2"
                            :class="[
                                'hover:bg-gray-100',
                                isActive(child.path)
                                    ? '!bg-blue-500 !text-white'
                                    : 'text-gray-700',
                            ]"
                        >
                            <Link :href="route(child.path)">
                                <span>{{ child.label }}</span>
                            </Link>
                        </a-menu-item>
                    </a-sub-menu>

                    <!-- Item simple -->
                    <a-menu-item
                        v-else
                        class="!flex items-center justify-between !py-4 text-sm font-medium !gap-2"
                        :class="[
                            'hover:bg-gray-100',
                            isActive(item.path)
                                ? '!bg-blue-500 !text-white'
                                : 'text-gray-700',
                        ]"
                    >
                        <template #icon>
                            <component :is="item.icon" class="!text-xl" />
                        </template>
                        <Link :href="route(item.path)">
                            <span>{{ item.label }}</span>
                        </Link>
                    </a-menu-item>
                </template>
            </a-menu>
        </a-layout-sider>
    </a-layout>

    <!-- Layout principal -->
    <a-layout :class="['transition-all duration-300 ease-in-out']">
        <!-- Header  -->
        <a-layout-header
            class="fixed top-0 right-0 h-20 bg-gradient-to-r from-white to-blue-50 shadow-md z-40 flex items-center justify-between px-8 border-b border-blue-100"
            :style="{
                width: `calc(100% - ${collapsed ? 80 : 240}px)`,
                left: `${collapsed ? 80 : 240}px`,
            }"
        >
            <div class="flex items-center gap-4">
                <button
                    @click="toggleCollapse"
                    class="bg-blue-50 hover:bg-blue-100 flex items-center hover:scale-110 p-2 rounded-lg text-xl transition-all duration-300"
                >
                    <menu-unfold-outlined
                        v-if="collapsed"
                        class="text-blue-600"
                    />
                    <menu-fold-outlined v-else class="text-blue-600" />
                </button>

                <div
                    v-if="collapsed"
                    class="block text-lg font-medium text-gray-700"
                >
                    <span class="text-blue-500">Med</span>icare
                    <span class="text-sm font-normal text-gray-500"
                        >| Gestion Sanitaire</span
                    >
                </div>
            </div>

            <a-dropdown trigger="click">
                <a-button
                    type="text"
                    class="flex items-center hover:!bg-blue-50 gap-4 text-xl !py-6 !px-4 rounded-lg"
                >
                    <div
                        class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full text-blue-600 text-xl"
                    >
                        {{ $page.props.auth.user.name.charAt(0) }}
                    </div>
                    <div class="flex flex-col items-start justify-center">
                        <span class="text-gray-700 font-medium text-base">
                            {{ $page.props.auth.user.prenom }}
                            {{ $page.props.auth.user.name.toUpperCase() }}</span
                        >
                        <span class="text-xs font-medium text-blue-500">{{
                            $page.props.auth.user.role
                        }}</span>
                    </div>
                </a-button>
                <template #overlay>
                    <a-menu
                        class="!mt-1 !min-w-[250px] !rounded-xl !shadow-lg !border !border-gray-100"
                    >
                        <!-- Informations utilisateur -->
                        <div class="px-4 py-3">
                            <div class="font-semibold text-gray-700">
                                {{ $page.props.auth.user.name }}
                                {{ $page.props.auth.user.prenom }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>
                        <!-- Option paramètres -->
                        <a-menu-item
                            v-if="$page.props.auth.user.role == 'admin'"
                            key="settings"
                            class="!flex !items-center !text-sm !p-3 !font-semibold border-t border-gray-100 !text-gray-600 hover:!bg-gray-50"
                        >
                            <Link
                                :href="route('admin.parametres')"
                                class="flex items-center"
                            >
                                <SettingOutlined class="mr-2" />
                                <span>Paramètres</span>
                            </Link>
                        </a-menu-item>

                        <!-- Séparateur -->
                        <a-menu-divider class="!my-1" />

                        <!-- Option déconnexion -->
                        <a-menu-item
                            key="logout"
                            @click="logout"
                            class="!flex !items-center !text-sm !p-3 !font-semibold !text-red-600 hover:!bg-red-50"
                        >
                            <LogoutOutlined class="mr-2" />
                            <span>Déconnexion</span>
                        </a-menu-item>
                    </a-menu>
                </template>
            </a-dropdown>
        </a-layout-header>
    </a-layout>

    <!-- Contenu  -->
    <a-layout-content
        :style="{
            width: `calc(100% - ${collapsed ? 80 : 240}px)`,
            left: `${collapsed ? 80 : 240}px`,
        }"
        class="relative transition-all duration-300 bg-gray-100 mt-16 min-h-[calc(100vh-142px)] p-4"
    >
        <slot />
        <a-back-top :visibility-height="15" :duration="800" />
    </a-layout-content>

    <!-- Footer -->
    <a-layout-footer
        class="relative flex shadow-lg transition-all p-4 duration-300 bg-white border-t border-gray-200"
        :style="{
            width: `calc(100% - ${collapsed ? 80 : 240}px)`,
            left: `${collapsed ? 80 : 240}px`,
        }"
    >
        <div
            class="w-full flex flex-col md:flex-row items-center justify-between px-6 py-2"
        >
            <div class="flex items-center gap-2 mb-2 md:mb-0">
                <span class="text-gray-600 font-semibold text-xl"
                    ><span class="text-blue-500">Medi</span>care</span
                >
            </div>

            <div class="text-sm text-gray-400 mt-2 md:mt-0">
                ©2025 Gestion Sanitaire Inter-Entreprise
            </div>
        </div>
    </a-layout-footer>
    <!-- Drawer de modification -->
</template>

<script setup>
import {
    BuildFilled,
    CarryOutFilled,
    FundFilled,
    LogoutOutlined,
    MenuFoldOutlined,
    MenuUnfoldOutlined,
    SettingFilled,
    SettingOutlined,
    UsergroupAddOutlined,
} from "@ant-design/icons-vue";
import { Link, router, usePage } from "@inertiajs/vue3";

import { ref } from "vue";

const logout = () => {
    router.post(route("logout"));
};
const page = usePage();
const collapsed = ref(false);

const toggleCollapse = () => {
    collapsed.value = !collapsed.value;
};

// Fonction pour vérifier si un lien est actif
const isActive = (path) => {
    const currentUrl = usePage().url.split("?")[0]; // Get the base URL without query parameters
    const routeUrl = route(path)
        .replace(window.location.origin, "")
        .split("?")[0];
    return currentUrl === routeUrl; // Compare base URLs
};

// Données du menu
const userRole = page.props.auth.user.role;
const adminMenu = [
    {
        key: "1",
        icon: FundFilled,
        label: "Dashboard",
        path: "admin.dashboard",
    },
    {
        key: "2",
        icon: BuildFilled,
        label: "Gestion des Societes",
        path: "admin.societe.index",
    },
    {
        key: "3",
        icon: UsergroupAddOutlined,
        label: "Gestion des Patients",
        path: "admin.patient.index",
    },
    {
        key: "4",
        icon: UsergroupAddOutlined,
        label: "Gestion des Medecins",
        path: "admin.medecins.list",
    },

    {
        key: "5",
        icon: SettingFilled,
        label: "Parametres",
        path: "admin.parametres",
    },
];

const medecinMenu = [
    {
        key: "1",
        icon: FundFilled,
        label: "Dashboard",
        path: "medecin.dashboard",
    },

    {
        key: "2",
        icon: CarryOutFilled,
        label: "Mes Consultations",
        children: [
            {
                key: "3-1",
                label: "Nouvelle Consultation",
                path: "medecin.consultation.create",
            },
            {
                key: "3-2",
                label: "Dossiers Medicals",
                path: "patient.dossier",
            },
        ],
    },

    {
        key: "3",
        icon: SettingFilled,
        label: "Parametres",
        path: "medecin.parametre",
    },
];

// Menu à utiliser selon le rôle
const asideMenu = userRole === "admin" ? adminMenu : medecinMenu;
</script>

<style>
/* Overrides Ant Design */
.ant-layout-sider {
    background: #ffffff !important;
}

.ant-menu-light {
    background: transparent;
    border-right: none !important;
}
.ant-menu-light .ant-menu-item-selected {
    @apply bg-transparent text-gray-600;
}

.ant-menu-item {
    @apply text-gray-600;
}

.ant-menu-item:hover {
    @apply !bg-gray-100 !text-gray-600 transition-colors duration-300;
}

.ant-layout-header {
    background: #fff !important;
    padding: 0 24px !important;
    line-height: normal !important;
}
.ant-menu .ant-menu-submenu-title .anticon + span {
    @apply text-gray-600 font-semibold;
}
.ant-modal-confirm .ant-modal-confirm-btns .ant-btn-default.ant-btn-dangerous {
    @apply !bg-red-500 text-white hover:bg-opacity-70;
}
.ant-modal-confirm .ant-btn-default {
    @apply hover:bg-gray-50 border-none hover:text-gray-800;
}
</style>
