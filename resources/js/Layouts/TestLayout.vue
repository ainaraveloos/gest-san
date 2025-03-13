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
                    class="!text-3xl mt-4 flex items-center justify-center font-semibold text-slate-600"
                >
                    <img src="../../assets/healthcare.png" alt="" />
                    <span v-if="!collapsed">medicare</span>
                </h1>
            </div>

            <a-menu
                mode="inline"
                :inline-collapsed="collapsed"
                class="!border-r-0 flex flex-col !gap-4"
            >
                <!-- Boucle sur asideMenu pour générer les items -->
                <a-menu-item
                    v-for="item in asideMenu"
                    :key="item.key"
                    class="!flex items-center justify-between !py-4 text-sm font-medium !gap-2"
                    :class="[
                        '!flex items-center justify-between hover:bg-gray-100',
                        isActive(item.path)
                            ? '!bg-blue-600 !text-white' // Styles Tailwind pour l'élément actif
                            : 'text-gray-700 ', // Styles Tailwind par défaut
                    ]"
                >
                    <template #icon>
                        <component :is="item.icon" class="!text-xl" />
                    </template>
                    <!-- Utilisation de Inertia Link -->
                    <Link :href="route(item.path)">
                        <span>{{ item.label }}</span>
                    </Link>
                </a-menu-item>
            </a-menu>
        </a-layout-sider>
    </a-layout>

    <!-- Layout principal -->
    <a-layout :class="['transition-all duration-300 ease-in-out']">
        <!-- Header  -->
        <a-layout-header
            class="fixed top-0 right-0 h-20 bg-white shadow-md z-40 flex items-center justify-between px-6 border-b"
            :style="{
                width: `calc(100% - ${collapsed ? 80 : 240}px)`,
                left: `${collapsed ? 80 : 240}px`,
            }"
        >
            <button
                @click="toggleCollapse"
                class="hover:bg-gray-100 flex items-center hover:scale-125 py-1 px-2 rounded-md text-xl transition-all duration-300"
            >
                <menu-unfold-outlined v-if="collapsed" class="text-gray-600" />
                <menu-fold-outlined v-else class="text-gray-600" />
            </button>

            <a-dropdown trigger="click">
                <a-button
                    type="text"
                    class="flex items-center gap-4 text-xl !py-6"
                >
                    <user-outlined class="text-gray-600 text-2xl" />
                    <div class="flex flex-col items-start justify-center">
                        <span class="text-gray-600 text-xl">{{
                            $page.props.auth.user.name
                        }}</span>
                        <span class="text-xs font-medium text-gray-500">{{
                            $page.props.auth.user.role
                        }}</span>
                    </div>
                </a-button>
                <template #overlay>
                    <a-menu class="!mt-1  ">

                        <a-menu-item
                            key="logout"
                            @click="logout"
                            class="!flex !items-center !text-sm !p-2 !font-semibold !text-red-600 hover:bg-gray-100"
                        >
                            <LogoutOutlined class="mr-2 text-lg" />
                            <span class="">Déconnexion</span>
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
        class="relative transition-all duration-300 bg-gray-100 mt-16 min-h-[calc(100vh-120px)] p-4"
    >
        <slot />
    </a-layout-content>

    <!-- Footer corrigé -->
    <a-layout-footer
        class="relative flex shadow-md items-center transition-all duration-300 justify-end text-gray-500 justify-center p-4 bg-white border-t-2 border-gray-100"
        :style="{
            width: `calc(100% - ${collapsed ? 80 : 240}px)`,
            left: `${collapsed ? 80 : 240}px`,
        }"
    >
        ©2025 Gestion Sanitaire Inter-Entreprise
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
    UsergroupAddOutlined,
    UserOutlined,
} from "@ant-design/icons-vue";
import { Link, router, usePage } from "@inertiajs/vue3";

import { ref} from "vue";

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
    const currentUrl = usePage().url;
    // Génère l'URL à partir du nom de la route et supprime le domaine si présent
    const routeUrl = route(path).replace(window.location.origin, "");
    return currentUrl === routeUrl;
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
        icon: CarryOutFilled,
        label: "Dossiers Medicaux",
        path: "admin.dossiers",
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
        icon: UsergroupAddOutlined,
        label: "Mes Patients",
        path: "medecin.patients.index",
    },
    {
        key: "3",
        icon: CarryOutFilled,
        label: "Nouveau Consultation ",
        path: "medecin.consultation",
    },
    {
        key: "4",
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

.ant-menu-item {
    color: rgb(119, 119, 119);
}

.ant-menu-item:hover {
    @apply !bg-gray-100 !text-gray-800 transition-colors duration-300;
}

.ant-layout-header {
    background: #fff !important;
    padding: 0 24px !important;
    line-height: normal !important;
}

</style>
