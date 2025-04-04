<template>
    <div class="px-2 py-6 bg-white rounded-lg shadow">
        <div
            class="flex flex-col md:flex-row justify-between items-center mb-6 px-4"
        >
            <h1 class="font-bold text-2xl text-gray-600">
                Liste des Patients <fonta class="ml-4" icon="users" />
            </h1>
            <!-- Boutton ouvrir modal de registre patient -->
            <BaseButton class="py-3" @click="showFormModal">
                <span>Ajouter nouveau patient</span>
                <fonta icon="user-plus" class="text-md" />
            </BaseButton>
        </div>

        <!-- Input recherche -->
        <div class="flex items-center mb-4 px-4 gap-4">
            <!-- Recherche par nom, prénom ou numéro du patient -->
            <div class="relative w-full md:w-1/3">
                <BaseInput
                    id="searchTerm"
                    type="text"
                    placeholder="Rechercher un nom, prenom, numero..."
                    v-model="searchTerm"
                />
            </div>

            <!-- Sélecteur de tri avec Ant Design Vue -->
            <a-dropdown :trigger="['click']" overlay-class-name="sort-dropdown">
                <div
                    class="flex items-center gap-2 bg-gray-50 hover:bg-gray-100 transition-all duration-300 px-4 py-2.5 rounded-lg border border-gray-200 cursor-pointer shadow-sm hover:shadow"
                >
                    <SortAscendingOutlined
                        class="text-blue-500 transition-transform duration-300"
                    />
                    <span class="text-gray-500 font-normal hidden md:inline">{{
                        getSortLabel()
                    }}</span>
                    <DownOutlined
                        class="text-gray-400 text-xs transition-all duration-300"
                    />
                </div>

                <template #overlay>
                    <a-menu class="sort-menu">
                        <div class="p-3 border-b border-gray-100 bg-gray-50">
                            <h4 class="font-semibold text-gray-700">
                                Trier par
                            </h4>
                        </div>
                        <a-menu-item
                            v-for="option in sortOptions"
                            :key="option.value"
                            @click="changeSortOrder(option.value)"
                            :class="
                                sortOrder === option.value
                                    ? 'ant-menu-item-active bg-blue-50'
                                    : ''
                            "
                        >
                            <div class="flex items-center gap-2">
                                <component
                                    :is="option.icon"
                                    class="text-sm"
                                    :class="
                                        sortOrder === option.value
                                            ? 'text-blue-500'
                                            : 'text-gray-400'
                                    "
                                />
                                <span
                                    :class="
                                        sortOrder === option.value
                                            ? 'text-blue-600 font-medium'
                                            : 'text-gray-600'
                                    "
                                >
                                    {{ option.label }}
                                </span>
                                <CheckOutlined
                                    v-if="sortOrder === option.value"
                                    class="ml-auto text-blue-500"
                                />
                            </div>
                        </a-menu-item>
                    </a-menu>
                </template>
            </a-dropdown>
        </div>

        <!-- Table patient -->
        <div class="flex items-center justify-center overflow-x-auto">
            <table class="min-w-[97%] divide-y divide-gray-200">
                <thead class="bg-blue-500">
                    <tr
                        class="text-sm text-gray-200 font-bold uppercase tracking-tight"
                    >
                        <th class="px-4 py-4 text-left hidden sm:table-cell">
                            Numéro
                        </th>
                        <th class="py-4 px-4 text-left">Nom Complet</th>
                        <th class="py-4 px-4 text-left">
                            <div
                                class="relative flex items-center justify-start gap-4"
                            >
                                <span class="mr-1">societe</span>
                                <a-dropdown
                                    v-model:open="dropdownFilterSocieteVisible"
                                    :trigger="['click']"
                                >
                                    <FilterFilled
                                        class="hover:text-white hover:shadow-md transition-colors duration-300 text-gray-300"
                                    />

                                    <template #overlay>
                                        <div
                                            class="bg-white rounded-lg shadow-xl border border-gray-100 w-64"
                                        >
                                            <div
                                                class="p-4 border-b border-gray-100"
                                            >
                                                <h4
                                                    class="font-semibold text-gray-800"
                                                >
                                                    Filtrer par société
                                                </h4>
                                            </div>

                                            <div
                                                class="p-2 max-h-60 overflow-y-auto"
                                            >
                                                <a-checkbox-group
                                                    v-model:value="
                                                        selectedSocietes
                                                    "
                                                    class="flex flex-col gap-2"
                                                >
                                                    <a-checkbox
                                                        v-for="societe in societes"
                                                        :key="societe.id"
                                                        :value="societe.id"
                                                        class="!flex items-center p-2 hover:bg-gray-50 rounded-md transition-colors"
                                                    >
                                                        <span
                                                            class="text-gray-700"
                                                            >{{
                                                                societe.nom
                                                            }}</span
                                                        >
                                                    </a-checkbox>
                                                </a-checkbox-group>
                                            </div>

                                            <div
                                                class="p-2 border-t border-gray-100 flex justify-end"
                                            >
                                                <a-button
                                                    type="link"
                                                    size="small"
                                                    @click="
                                                        selectedSocietes = []
                                                    "
                                                    class="!text-gray-500 hover:!text-red-500 !h-8"
                                                >
                                                    Réinitialiser
                                                </a-button>
                                            </div>
                                        </div>
                                    </template>
                                </a-dropdown>
                            </div>
                        </th>
                        <th class="py-4 px-4 hidden lg:table-cell">
                            <div
                                class="relative flex items-center justify-center gap-4"
                            >
                                <span class="mr-1">Type</span>
                                <a-dropdown
                                    v-model:open="dropdownFilterTypeVisible"
                                    :trigger="['click']"
                                >
                                    <!-- Icône de filtre -->
                                    <FilterFilled
                                        class="hover:text-white hover:shadow-md transition-colors duration-300 text-gray-300"
                                    />

                                    <!-- Menu déroulant -->
                                    <template #overlay>
                                        <div
                                            class="bg-white rounded-lg shadow-xl border border-gray-100 w-52"
                                        >
                                            <!-- En-tête -->
                                            <div
                                                class="p-4 border-b border-gray-100"
                                            >
                                                <h4
                                                    class="font-semibold text-gray-800"
                                                >
                                                    Filtrer par type
                                                </h4>
                                            </div>

                                            <!-- Liste des checkboxes -->
                                            <div
                                                class="p-2 max-h-60 overflow-y-auto"
                                            >
                                                <a-checkbox-group
                                                    v-model:value="
                                                        selectedTypes
                                                    "
                                                    class="flex flex-col gap-2"
                                                >
                                                    <a-checkbox
                                                        v-for="type in patientTypes"
                                                        :key="type.value"
                                                        :value="type.value"
                                                        class="!flex items-center p-2 hover:bg-gray-50 rounded-md transition-colors"
                                                    >
                                                        <span
                                                            class="text-gray-700"
                                                            >{{
                                                                type.label
                                                            }}</span
                                                        >
                                                    </a-checkbox>
                                                </a-checkbox-group>
                                            </div>

                                            <!-- Footer avec reset -->
                                            <div
                                                class="p-2 border-t border-gray-100 flex justify-end"
                                            >
                                                <a-button
                                                    type="link"
                                                    size="small"
                                                    @click="resetSelection"
                                                    class="!text-gray-500 hover:!text-red-500 !h-8"
                                                >
                                                    Réinitialiser
                                                </a-button>
                                            </div>
                                        </div>
                                    </template>
                                </a-dropdown>
                            </div>
                        </th>
                        <th class="py-4 text-center hidden md:table-cell">
                            <div class="flex items-center justify-center gap-2">
                                <span>Statut badge</span>
                                <a-dropdown
                                    v-model:open="dropdownFilterBadgeVisible"
                                    :trigger="['click']"
                                >
                                    <FilterFilled
                                        class="hover:text-white hover:shadow-md transition-colors duration-300 text-gray-300"
                                    />
                                    <template #overlay>
                                        <div
                                            class="bg-white rounded-lg shadow-xl border border-gray-100 w-56"
                                        >
                                            <div
                                                class="p-4 border-b border-gray-100"
                                            >
                                                <h4
                                                    class="font-semibold text-gray-800"
                                                >
                                                    Filtrer par statut
                                                </h4>
                                            </div>
                                            <div
                                                class="p-2 max-h-60 overflow-y-auto"
                                            >
                                                <a-checkbox-group
                                                    v-model:value="
                                                        selectedBadgeStatuses
                                                    "
                                                    class="flex flex-col gap-2"
                                                >
                                                    <a-checkbox
                                                        v-for="status in badgeStatuses"
                                                        :key="status.value"
                                                        :value="status.value"
                                                        class="!flex items-center p-2 hover:bg-gray-50 rounded-md transition-colors"
                                                    >
                                                        <div
                                                            class="flex items-center gap-2"
                                                        >
                                                            <div
                                                                :class="[
                                                                    'w-3 h-3 rounded-full',
                                                                    status.value ===
                                                                    'actif'
                                                                        ? 'bg-green-500 pulse-green'
                                                                        : status.value ===
                                                                          'desactive'
                                                                        ? 'bg-red-500 pulse-red'
                                                                        : status.value ===
                                                                          'expired'
                                                                        ? 'bg-orange-500 pulse-orange'
                                                                        : 'bg-gray-500',
                                                                ]"
                                                            ></div>
                                                            <span
                                                                class="text-gray-700"
                                                                >{{
                                                                    status.label
                                                                }}</span
                                                            >
                                                        </div>
                                                    </a-checkbox>
                                                </a-checkbox-group>
                                            </div>
                                            <div
                                                class="p-2 border-t border-gray-100 flex justify-end"
                                            >
                                                <a-button
                                                    type="link"
                                                    size="small"
                                                    @click="
                                                        selectedBadgeStatuses =
                                                            []
                                                    "
                                                    class="!text-gray-500 hover:!text-red-500 !h-8"
                                                >
                                                    Réinitialiser
                                                </a-button>
                                            </div>
                                        </div>
                                    </template>
                                </a-dropdown>
                            </div>
                        </th>

                        <th class="py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="(patient, index) in patients.data"
                        :key="patient.id"
                        :class="[
                            ' transition-colors duration-300',
                            index % 2 === 0 ? 'bg-gray-50' : 'bg-white',
                        ]"
                    >
                        <td class="p-4 whitespace-nowrap sm:table-cell hidden">
                            <div class="text-sm text-gray-900">
                                {{ patient.numero }}
                            </div>
                        </td>
                        <td
                            class="p-4 whitespace-nowrap text-left font-semibold text-sm text-gray-600"
                        >
                            <div>{{ patient.nom }} {{ patient.prenom }}</div>
                        </td>
                        <td
                            class="p-4 whitespace-nowrap text-sm font-medium text-gray-600 text-left"
                        >
                            <div>
                                {{ patient.societe?.nom }}
                            </div>
                        </td>
                        <td
                            class="p-4 whitespace-nowrap hidden lg:table-cell text-center"
                        >
                            <span
                                :class="[
                                    'px-2 py-1 text-xs font-semibold rounded-full shadow',
                                    patient.type === 'SALARIE'
                                        ? 'bg-blue-100 text-blue-800'
                                        : 'bg-green-100 text-green-800',
                                ]"
                            >
                                {{ patient.type.toUpperCase() }}
                            </span>
                        </td>
                        <td class="text-center hidden md:table-cell">
                            <a-dropdown
                                :trigger="['click']"
                                overlay-class-name="badge-dropdown"
                                placement="bottomLeft"
                            >
                                <span
                                    :class="[
                                        'px-4 py-2 rounded-full  text-sm font-semibold cursor-pointer  hover:shadow transition-all duration-300 flex items-center gap-2 justify-center',
                                        patient.badge.status === 'actif' &&
                                        !isExpired(patient.badge.validite)
                                            ? 'bg-green-100 hover:bg-green-200 text-green-500'
                                            : patient.badge.status ===
                                              'desactive'
                                            ? 'bg-red-100 hover:bg-red-200 text-red-500'
                                            : isExpired(patient.badge.validite)
                                            ? 'bg-orange-100 hover:bg-orange-200 text-orange-500'
                                            : 'bg-gray-100 hover:bg-gray-200 text-gray-500',
                                    ]"
                                >
                                    <span
                                        :class="[
                                            'w-3 h-3 rounded-full mr-2 pulse-circle',
                                            patient.badge.status === 'actif' &&
                                            !isExpired(patient.badge.validite)
                                                ? 'bg-green-500 pulse-green'
                                                : patient.badge.status ===
                                                  'desactive'
                                                ? 'bg-red-500 pulse-red'
                                                : isExpired(
                                                      patient.badge.validite
                                                  )
                                                ? 'bg-orange-500 pulse-orange'
                                                : 'bg-gray-500',
                                        ]"
                                    ></span>
                                    {{ patient.badge.status }}
                                </span>

                                <template #overlay>
                                    <a-menu class="p-0 rounded-xl shadow-xl">
                                        <div
                                            class="px-5 py-4 font-semibold text-gray-700 border-b border-gray-100 mb-2 bg-gray-50 rounded-t-xl"
                                        >
                                            Statut du badge
                                        </div>

                                        <!-- Bouton "Renouveler" visible uniquement quand le badge est désactivé -->
                                        <a-menu-item
                                            v-if="
                                                patient.badge.status ===
                                                'desactive'
                                            "
                                            key="renew"
                                            @click="
                                                renewBadge(patient.badge.id)
                                            "
                                            class="!my-2 !mx-2 !rounded-lg !hover:bg-blue-50"
                                        >
                                            <div
                                                class="flex items-center gap-3 font-medium text-blue-600 px-3 py-2"
                                            >
                                                <div
                                                    class="w-4 h-4 rounded-full bg-blue-500 pulse-blue"
                                                ></div>
                                                <span
                                                    >Renouveler pour 1
                                                    mois</span
                                                >
                                            </div>
                                        </a-menu-item>

                                        <div
                                            class="p-4 m-3 rounded-lg bg-gray-50 text-sm border border-gray-200"
                                        >
                                            <div
                                                v-if="
                                                    patient.badge.status ===
                                                    'desactive'
                                                "
                                                class="flex items-center gap-3"
                                            >
                                                <div
                                                    class="w-8 h-8 flex items-center justify-center rounded-full bg-red-100"
                                                >
                                                    <div
                                                        class="w-3 h-3 rounded-full bg-red-500 pulse-red"
                                                    ></div>
                                                </div>
                                                <div>
                                                    <span
                                                        class="text-red-600 font-medium"
                                                        >Badge inactif</span
                                                    >
                                                    <p
                                                        class="text-sm text-gray-400 mt-1"
                                                    >
                                                        Veuillez renouveler le
                                                        badge pour l'activer
                                                    </p>
                                                </div>
                                            </div>
                                            <div
                                                v-else-if="
                                                    isExpired(
                                                        patient.badge.validite
                                                    )
                                                "
                                                class="flex items-center gap-3"
                                            >
                                                <div
                                                    class="w-8 h-8 flex items-center justify-center rounded-full bg-orange-100"
                                                >
                                                    <div
                                                        class="w-3 h-3 rounded-full bg-orange-500 pulse-orange"
                                                    ></div>
                                                </div>
                                                <div>
                                                    <span
                                                        class="text-orange-600 font-medium"
                                                        >Badge expiré</span
                                                    >
                                                    <p
                                                        class="text-sm text-gray-400 mt-1"
                                                    >
                                                        Validité terminée le
                                                        {{
                                                            formatDate(
                                                                patient.badge
                                                                    .validite
                                                            )
                                                        }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div
                                                v-else
                                                class="flex items-center gap-3"
                                            >
                                                <div
                                                    class="w-8 h-8 flex items-center justify-center rounded-full bg-green-100"
                                                >
                                                    <div
                                                        class="w-3 h-3 rounded-full bg-green-500 pulse-green"
                                                    ></div>
                                                </div>
                                                <div>
                                                    <span
                                                        class="text-green-600 font-medium"
                                                        >Badge actif</span
                                                    >
                                                    <p
                                                        class="text-xs text-gray-600 mt-1"
                                                    >
                                                        Valide jusqu'au
                                                        {{
                                                            formatDate(
                                                                patient.badge
                                                                    .validite
                                                            )
                                                        }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </a-menu>
                                </template>
                            </a-dropdown>
                        </td>
                        <td class="py-4 whitespace-nowrap text-sm font-medium">
                            <!-- Version desktop -->
                            <div
                                class="hidden lg:flex gap-2 justify-center items-center"
                            >
                                <button
                                    @click="editingPatient(patient)"
                                    class="text-blue-500 hover:bg-gray-100 group px-2 py-1 hover:scale-110 overflow-hidden hover:shadow-sm transition-all duration-300 rounded-md hover:underline"
                                >
                                    <EditFilled
                                        class="text-lg group-hover:scale-125 duration-500"
                                    />
                                </button>

                                <button
                                    @click="
                                        confirmDelete(
                                            route(
                                                'admin.patient.delete',
                                                patient
                                            )
                                        )
                                    "
                                    class="text-red-500 hover:bg-gray-100 group hover:scale-110 overflow-hidden px-2 py-1 hover:shadow-sm transition-all duration-500 rounded-md hover:underline"
                                >
                                    <DeleteFilled
                                        class="text-lg group-hover:scale-125 duration-500"
                                    />
                                </button>

                                <button
                                    @click="viewDossier(patient)"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 text-blue-600 font-semibold hover:bg-blue-100 hover:scale-110 transition-all duration-500 ease-in-out border border-blue-200"
                                >
                                    <fonta
                                        icon="folder-open"
                                        class="text-blue-500"
                                    />
                                    <span>Dossier</span>
                                </button>
                            </div>

                            <!-- Version mobile avec dropdown -->
                            <div class="lg:hidden flex justify-center">
                                <a-dropdown
                                    placement="bottomRight"
                                    :trigger="['click']"
                                >
                                    <button
                                        class="text-blue-500 hover:bg-blue-100 bg-blue-50 group px-2 py-1 hover:scale-110 overflow-hidden hover:shadow-sm transition-all duration-300 rounded-md hover:underline"
                                    >
                                        <fonta
                                            icon="ellipsis"
                                            class="text-lg text-gray-400"
                                        />
                                    </button>
                                    <template #overlay>
                                        <a-menu
                                            class="!min-w-[120px] space-y-4"
                                        >
                                            <a-menu-item
                                                @click="editingPatient(patient)"
                                                class="!py-2"
                                            >
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <EditFilled
                                                        class="text-blue-500 text-lg"
                                                    />
                                                    <span
                                                        class="text-blue-500 font-medium"
                                                        >Modifier</span
                                                    >
                                                </div>
                                            </a-menu-item>
                                            <a-menu-item
                                                @click="
                                                    confirmDelete(
                                                        route(
                                                            'admin.patient.delete',
                                                            patient
                                                        )
                                                    )
                                                "
                                                class="!py-2"
                                            >
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <DeleteFilled
                                                        class="text-red-500 text-lg"
                                                    />
                                                    <span
                                                        class="text-red-500 font-medium"
                                                        >Supprimer</span
                                                    >
                                                </div>
                                            </a-menu-item>
                                            <a-menu-item
                                                @click="viewDossier(patient)"
                                                class="!py-2"
                                            >
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <fonta
                                                        icon="folder-open"
                                                        class="text-blue-500 text-lg"
                                                    />
                                                    <span
                                                        class="text-blue-500 font-medium"
                                                        >Dossier</span
                                                    >
                                                </div>
                                            </a-menu-item>
                                        </a-menu>
                                    </template>
                                </a-dropdown>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div v-if="patients.data.length > 0 && patients.links.length > 3">
            <div class="flex flex-wrap mt-4 items-center justify-end">
                <div
                    v-for="(link, linkIndex) in patients.links"
                    :key="linkIndex"
                >
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
                        @click.prevent="navigateToPage(link.url)"
                    >
                        <span v-html="link.label"></span>
                    </Link>
                </div>
            </div>
        </div>

        <!-- Modal registre de patient -->
        <a-modal
            :open="showModal"
            :width="800"
            :footer="null"
            style="top: 15px"
            @cancel="handleCloseModal"
        >
            <h1 class="mb-6 text-blue-500 font-bold text-xl text-center">
                {{ isEditing ? "Modifier Information" : "Ajouter un Patient" }}
            </h1>
            <form @submit.prevent="handleSubmit" class="!px-4">
                <transition name="fade">
                    <div
                        class="grid grid-cols-1 items-center md:grid-cols-2 gap-4 mb-4"
                    >
                        <div>
                            <InputLabel
                                for="type de patient"
                                value="Type de patient"
                                class="mb-1"
                            />
                            <a-segmented
                                v-model:value="form.type"
                                :options="patientOptions"
                                :disabled="isEditing"
                                block
                                class="custom-patient-segmented"
                            />
                        </div>
                        <div v-if="form.type === 'SALARIE'">
                            <InputLabel
                                for="societe"
                                value="Societe"
                                class="mb-1"
                            />
                            <a-select
                                v-model:value="form.societe_id"
                                placeholder="Sélectionner une société"
                                class="w-full societe_select"
                                :style="{ height: '42px' }"
                                :disabled="isEditing"
                            >
                                <a-select-option
                                    class="p-4"
                                    v-for="societe in societes"
                                    :key="societe.id"
                                    :value="societe.id"
                                >
                                    {{ societe.nom }}
                                </a-select-option>
                            </a-select>
                        </div>

                        <div v-if="form.type === 'FAMILLE'" class="relative">
                            <InputLabel
                                for="parent_id"
                                value="Salarié Référent"
                                class="mb-1"
                            />
                            <BaseInput
                                type="text"
                                v-model="employeeSearchTerm"
                                placeholder="Rechercher un salarié..."
                                @focus="dropdownVisible = true"
                                @input="searchSalaries"
                            />
                            <div
                                v-if="
                                    dropdownVisible &&
                                    filteredSalaries.length > 0
                                "
                                class="absolute z-10 w-full bg-white shadow-md p-4 flex flex-col gap-2 rounded-md mt-1 max-h-60 overflow-y-auto"
                            >
                                <div
                                    v-for="salarie in filteredSalaries"
                                    :key="salarie.id"
                                    @mousedown.prevent="selectSalarie(salarie)"
                                    class="px-3 py-2 hover:bg-blue-600 rounded-md hover:text-white transition-colors duration-500 cursor-pointer"
                                >
                                    {{ salarie.nom }} {{ salarie.prenom }} ({{
                                        salarie.numero
                                    }})
                                </div>
                            </div>
                            <input type="hidden" v-model="form.parent_id" />
                        </div>
                    </div>
                </transition>
                <div class="mb-4" v-if="form.type === 'FAMILLE'">
                    <InputLabel for="lien_parente" value="Lien de parente" />
                    <BaseInput
                        id="lient_parente"
                        type="text"
                        v-model="form.lien_parente"
                        required
                    />
                    <InputError
                        class="mt-1"
                        :message="form.errors.lien_parente"
                    />
                </div>
                <!-- Informations de base -->
                <!-- Informations Personnelles -->
                <transition name="fade">
                    <div class="mb-6 border-b pb-4">
                        <h2 class="text-lg font-semibold text-blue-500 mb-4">
                            Informations Personnelles
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <InputLabel for="nom" value="Nom" />
                                <BaseInput
                                    id="nom"
                                    type="text"
                                    v-model="form.nom"
                                    required
                                />
                                <InputError
                                    class="mt-1"
                                    :message="form.errors.nom"
                                />
                            </div>

                            <div>
                                <InputLabel for="prenom" value="Prénom" />
                                <BaseInput
                                    id="prenom"
                                    type="text"
                                    v-model="form.prenom"
                                    required
                                />
                                <InputError
                                    class="mt-1"
                                    :message="form.errors.prenom"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <InputLabel
                                    for="date_naissance"
                                    value="Date de naissance"
                                />
                                <BaseInput
                                    id="date_naissance"
                                    type="date"
                                    v-model="form.date_naissance"
                                    required
                                />
                                <InputError
                                    class="mt-1"
                                    :message="form.errors.date_naissance"
                                />
                            </div>

                            <div>
                                <InputLabel for="adresse" value="Adresse" />
                                <BaseInput
                                    id="adresse"
                                    type="text"
                                    v-model="form.adresse"
                                />
                                <InputError
                                    class="mt-1"
                                    :message="form.errors.adresse"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <InputLabel for="telephone" value="Téléphone" />
                                <BaseInput
                                    id="telephone"
                                    type="text"
                                    v-model="form.telephone"
                                />
                                <InputError
                                    class="mt-1"
                                    :message="form.errors.telephone"
                                />
                            </div>

                            <div>
                                <InputLabel for="email" value="Email" />
                                <BaseInput
                                    id="email"
                                    type="email"
                                    v-model="form.email"
                                />
                                <InputError
                                    class="mt-1"
                                    :message="form.errors.email"
                                />
                            </div>
                        </div>
                    </div>
                </transition>

                <!-- Informations Professionnelles (Uniquement pour les salariés) -->
                <transition name="fade">
                    <div v-if="form.type === 'SALARIE'" class="border-b pb-4">
                        <h2 class="text-lg font-semibold text-blue-500 mb-4">
                            Informations Professionnelles
                        </h2>
                        <div
                            v-if="form.type === 'SALARIE'"
                            class="w-full col-span-full mb-4"
                        >
                            <InputLabel for="poste" value="Poste occupe" />
                            <BaseInput
                                id="poste"
                                type="text"
                                v-model="form.poste"
                            />
                            <InputError
                                class="mt-1"
                                :message="form.errors.poste"
                            />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <InputLabel
                                    for="date_entree_entreprise"
                                    value="Date d'entrée"
                                />
                                <BaseInput
                                    id="date_entree_entreprise"
                                    type="date"
                                    v-model="form.date_entree_entreprise"
                                    :disabled="isEditing"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    for="statut_emploi"
                                    value="Statut d'emploi"
                                    class="mb-1"
                                />
                                <a-select
                                    v-model:value="form.statut_emploi"
                                    class="w-full"
                                    :style="{ height: '42px' }"
                                    placeholder="Sélectionner un statut"
                                >
                                    <a-select-option value="Permanent"
                                        >Permanent</a-select-option
                                    >
                                    <a-select-option value="Temporaire"
                                        >Temporaire</a-select-option
                                    >
                                    <a-select-option value="CDD"
                                        >CDD</a-select-option
                                    >
                                    <a-select-option value="CDI"
                                        >CDI</a-select-option
                                    >
                                </a-select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div
                                v-if="
                                    ['Temporaire', 'CDD'].includes(
                                        form.statut_emploi
                                    )
                                "
                            >
                                <InputLabel
                                    for="date_fin_contrat"
                                    value="Date fin contrat"
                                />
                                <BaseInput
                                    id="date_fin_contrat"
                                    type="date"
                                    v-model="form.date_fin_contrat"
                                />
                            </div>
                        </div>
                    </div>
                </transition>

                <!-- Boutons d'action, disposés de manière responsive -->
                <div
                    class="flex flex-col mt-4 sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-4"
                >
                    <Button @click="handleCloseModal"> Annuler </Button>
                    <BaseButton type="submit">
                        {{ isEditing ? "Modifier" : "Enregistrer" }}
                    </BaseButton>
                </div>
            </form>
        </a-modal>
    </div>
</template>

<script setup>
import BaseButton from "@/Components/BaseButton.vue";
import BaseInput from "@/Components/BaseInput.vue";
import Button from "@/Components/Button.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import useConfirmDialog from "@/composables/useConfirmDialog";
import TestLayout from "@/Layouts/TestLayout.vue";
import {
    CalendarOutlined,
    CheckOutlined,
    DeleteFilled,
    DownOutlined,
    EditFilled,
    FilterFilled,
    SortAscendingOutlined,
    SortDescendingOutlined,
    TeamOutlined,
} from "@ant-design/icons-vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import { debounce } from "lodash";
import Swal from "sweetalert2";
import { defineOptions, onMounted, ref, watch } from "vue";
const { confirmDelete } = useConfirmDialog();

defineOptions({
    layout: TestLayout,
});
const props = defineProps({
    patients: Object,
    societes: Array,
    salaries: Array,
    filters: Object,
    badge: Array,
});
//Pour l'ajout ou edit patient
const form = useForm({
    type: "SALARIE",
    parent_id: null,
    societe_id: null,
    lien_parente: "",
    nom: "",
    prenom: "",
    date_naissance: null,
    adresse: "",
    telephone: "",
    email: "",
    poste: "",
    date_entree_entreprise: null,
    statut_emploi: "CDI",
    date_fin_contrat: null,
});
//Variable pour le formulaire modal
const showModal = ref(false);
const isEditing = ref(false);
const editingPatientId = ref(null);
const patientOptions = ref([
    {
        value: "SALARIE",
        label: "Salarié",
        // Style personnalisé avec Tailwind
        class: "!text-blue-800 hover:!bg-blue-50",
    },
    {
        value: "FAMILLE",
        label: "Bénéficiaire",
        // Style personnalisé avec Tailwind
        class: "!text-green-800 hover:!bg-green-50",
    },
]);
// Variables pour la recherche de salarié dans modal
const employeeSearchTerm = ref("");
const dropdownVisible = ref(false);
const filteredSalaries = ref([]);
const selectedTypes = ref(props.filters.types || []);
const resetSelection = () => {
    selectedTypes.value = [];
};
function selectSalarie(salarie) {
    form.parent_id = salarie.id;
    employeeSearchTerm.value = `${salarie.nom} ${salarie.prenom}`;
    dropdownVisible.value = false;
}
const searchSalaries = async () => {
    if (employeeSearchTerm.value.length < 2) {
        filteredSalaries.value = []; //Si l'input du champ de recherche est trop court on ne return pas
        return;
    }

    const response = await fetch(//sinon on retourne une correspondance
        `/admin/salaries/search?search=${employeeSearchTerm.value}`
    );
    const data = await response.json();
    filteredSalaries.value = data; // mise à jour du salarié referent
};

//Fermeture du Modal
const handleCloseModal = () => {
    showModal.value = false;
    form.reset();
};
//gestion de Modification
const editingPatient = (patient) => {
    isEditing.value = true;
    editingPatientId.value = patient.id;
    if (patient.type === "SALARIE") {
        form.societe_id = patient.societe_id;
        form.poste = patient.poste;
    } else if (patient.type === "FAMILLE") {
        form.parent_id = patient.parent_id;
        form.lien_parente = patient.lien_parente;
    }
    form.type = patient.type;
    form.nom = patient.nom;
    form.prenom = patient.prenom;
    form.adresse = patient.adresse;
    form.telephone = patient.telephone;
    form.email = patient.email;
    form.date_naissance = patient.date_naissance;
    showModal.value = true;
};
//gestion du formulaire d'Ajout
const showFormModal = () => {
    isEditing.value = false;
    form.reset();
    showModal.value = true;
};
//Soumission de donées (ajout ou modification)
const handleSubmit = () => {
    if (isEditing.value) {
        form.patch(route("admin.patient.update", editingPatientId.value), {
            onSuccess: () => {
                form.reset();
                showModal.value = false;
                Swal.fire({
                    title: "Mis à jour !",
                    text: "Information du patient mis à jour avec succès",
                    icon: "success",
                    confirmButtonColor: "#3b82f6",
                });
            },
            onError: (e) => {
                console.log(e);
            },
        });
    } else {
        form.post(route("admin.patient.store"), {
            onSuccess: () => {
                Swal.fire({
                    title: "Ajout !",
                    text: "Patient ajouter avec succès",
                    icon: "success",
                    confirmButtonColor: "#3b82f6",
                });
                form.reset();
                showModal.value = false;
            },
            onError: (e) => {
                console.log(e);
            },
        });
    }
};
// Initialisation des filtres à partir des valeurs envoyées par le contrôleur (s'il y en a)
//filtre selon le societe,type de patient et recherche
const searchTerm = ref(props.filters.search || "");
const selectedSocietes = ref(
    props.filters.societes ? props.filters.societes.map((id) => Number(id)) : []
);
const patientTypes = [
    { value: "SALARIE", label: "Salarié" },
    { value: "FAMILLE", label: "Bénéficiaire" },
];
const dropdownFilterTypeVisible = ref(false);
const dropdownFilterSocieteVisible = ref(false);

//les status du badge du patient/filtre selon le status du badge
const badgeStatuses = [
    { value: "actif", label: "Badges actifs" },
    { value: "desactive", label: "Badges inactifs" },
    { value: "expired", label: "Badges expirés" },
];
const selectedBadgeStatuses = ref(props.filters.badgeStatuses || []);
const dropdownFilterBadgeVisible = ref(false);

// Options de tri disponibles/tri des resultats
const sortOptions = [
    { value: "newest", label: "Plus récents", icon: CalendarOutlined },
    { value: "oldest", label: "Plus anciens", icon: CalendarOutlined },
    { value: "name_asc", label: "Nom (A-Z)", icon: SortAscendingOutlined },
    { value: "name_desc", label: "Nom (Z-A)", icon: SortDescendingOutlined },
    { value: "societe", label: "Société", icon: TeamOutlined },
];
// valeur par defaut du tri
const sortOrder = ref(props.filters.sort || "newest");

// Fonction pour obtenir le label du tri actuel à aficher dans l'input du selection
const getSortLabel = () => {
    const option = sortOptions.find((option) => option.value === sortOrder.value);
    return option ? option.label : "Trier par";
};
// Fonction pour changer l'ordre de tri
const changeSortOrder = (value) => {
    sortOrder.value = value;

    router.get(
        route("admin.patient.index"),
        {
            search: searchTerm.value,
            societes: selectedSocietes.value,
            types: selectedTypes.value,
            badgeStatuses: selectedBadgeStatuses.value,
            sort: value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};

// Mise à jour de la fonction watch pour inclure le tri
watch(
    [searchTerm, selectedSocietes, selectedTypes, selectedBadgeStatuses],
    debounce(([newSearch, newSociete, newTypes, newBadgeStatuses]) => {
        // Pour les valeurs numériques comme les IDs de sociétés,il faut convertir en numeric pour maintenir le filtre
        const societeValues = newSociete.map((id) => Number(id));

        router.get(
            route("admin.patient.index"),
            {
                search: newSearch,
                societes: societeValues,
                types: newTypes,
                badgeStatuses: newBadgeStatuses,
                sort: sortOrder.value,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            }
        );
    }, 800)
);

// Fonction pour voir le dossier d'un patient
const viewDossier = (patient) => {
    router.get(route("admin.patient.view", { id: patient.id }));
};

// Fonction pour vérifier si la date est expirée
const isExpired = (dateStr) => {
    if (!dateStr) return true;

    const validityDate = new Date(dateStr);
    const today = new Date();

    return validityDate < today;
};

// Fonction pour formater la date
const formatDate = (dateStr) => {
    if (!dateStr) return "Non définie";

    return new Date(dateStr).toLocaleDateString("fr-FR", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};
// Fonction pour renouveler le badge
const renewBadge = (badgeId) => {
    router.post(
        route("admin.badge.renew"),
        {
            badge_id: badgeId,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                Swal.fire({
                    title: "Badge activé !",
                    text: "Le badge a été renouvelé et activé pour un mois",
                    icon: "success",
                    confirmButtonColor: "#3b82f6",
                });
            },
            onError: (e) => {
                console.error(e);
                Swal.fire({
                    title: "Erreur",
                    text: "Impossible de renouveler le badge",
                    icon: "error",
                    confirmButtonColor: "#ef4444",
                });
            },
        }
    );
};

// Vérifier les badges expirés au chargement de la page
onMounted(() => {
    router.post(route("admin.badge.checkExpired"));
});

const navigateToPage = (url) => {
    router.get(
        url,
        {
            search: searchTerm.value,
            societes: selectedSocietes.value.map((id) => Number(id)),
            types: selectedTypes.value,
            badgeStatuses: selectedBadgeStatuses.value,
            sort: sortOrder.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};
</script>

<style>
.ant-select-selector {
    @apply !h-full;
}
.ant-select-selection-item {
    @apply !text-base mt-1 text-gray-500;
}
.ant-modal-close-x {
    @apply flex items-center justify-center   !important;
}
.ant-modal-close-x svg {
    @apply text-base;
}
.ant-segmented .ant-segmented-item-label {
    @apply py-2 text-base font-semibold;
}
.ant-segmented .ant-segmented-item-selected {
    @apply bg-blue-500 text-white transition-all duration-500;
}
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
    opacity: 0;
}

/* Styles pour le dropdown de badge */
:deep(.badge-dropdown .ant-dropdown-menu) {
    padding: 0;
    border-radius: 1rem;
    min-width: 300px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    border: 1px solid rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

:deep(.badge-dropdown .ant-menu-item) {
    margin: 0.5rem;
    height: auto !important;
    line-height: 1.5;
    padding: 0 !important;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

:deep(.badge-dropdown .ant-menu-item:hover) {
    background-color: rgba(0, 0, 0, 0.02);
    transform: translateY(-1px);
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
}

:deep(.badge-dropdown .ant-menu-item-disabled) {
    color: rgba(0, 0, 0, 0.25);
    background-color: transparent !important;
    cursor: not-allowed;
}

/* Style spécial pour le bouton de renouvellement */
:deep(.badge-dropdown .ant-menu-item.bg-blue-50) {
    background-color: rgb(239, 246, 255) !important;
    transition: all 0.3s ease;
}

:deep(.badge-dropdown .ant-menu-item.bg-blue-50:hover) {
    background-color: rgb(219, 234, 254) !important;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}

/* Animation de pulsation pour les badges */
@keyframes pulse-green {
    0% {
        box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.7);
    }
    70% {
        box-shadow: 0 0 0 6px rgba(74, 222, 128, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(74, 222, 128, 0);
    }
}

@keyframes pulse-red {
    0% {
        box-shadow: 0 0 0 0 rgba(248, 113, 113, 0.7);
    }
    70% {
        box-shadow: 0 0 0 6px rgba(248, 113, 113, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(248, 113, 113, 0);
    }
}

@keyframes pulse-orange {
    0% {
        box-shadow: 0 0 0 0 rgba(251, 146, 60, 0.7);
    }
    70% {
        box-shadow: 0 0 0 6px rgba(251, 146, 60, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(251, 146, 60, 0);
    }
}

@keyframes pulse-blue {
    0% {
        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
    }
    70% {
        box-shadow: 0 0 0 6px rgba(59, 130, 246, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
    }
}

.pulse-circle {
    display: inline-block;
}

.pulse-green {
    animation: pulse-green 2s infinite;
}

.pulse-red {
    animation: pulse-red 2s infinite;
}

.pulse-orange {
    animation: pulse-orange 2s infinite;
}

.pulse-blue {
    animation: pulse-blue 2s infinite;
}

.badge-pulse-active,
.badge-pulse-inactive,
.badge-pulse-warning {
    animation: none;
}

/* Animations plus fluides */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateX(-5px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.3s ease-in-out;
}

.group:hover .group-hover\:rotate-180 {
    transform: rotate(180deg);
}

.group:hover .group-hover\:border-blue-200 {
    border-color: rgba(191, 219, 254, 1);
}

.group:hover .group-hover\:text-blue-500,
.group:hover .group-hover\:text-blue-600 {
    transition-delay: 0.05s;
}

.group:hover .group-hover\:scale-110 {
    transition-delay: 0.05s;
}

/* Improve visibility transition */
.invisible {
    visibility: hidden;
}

.group-hover\:visible {
    visibility: visible;
    transition-delay: 0s;
}

.group-hover\:opacity-100 {
    transition-delay: 0.05s;
}

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
