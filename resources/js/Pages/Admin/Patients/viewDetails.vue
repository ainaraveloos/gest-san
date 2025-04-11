<template>
    <div class="mx-auto bg-white rounded-lg shadow-md p-8 space-y-8">
        <!-- En-tête avec dégradé -->
        <div
            class="patient-header bg-gradient-to-br from-white to-blue-50/30 p-8 rounded-xl shadow-sm border border-blue-100/60"
        >
            <div
                class="bg-white p-6 rounded-xl shadow-sm border border-gray-100/60"
            >
                <div
                    class="flex flex-col lg:flex-row items-center justify-between gap-8"
                >
                    <!-- Colonne gauche: Avatar et infos basiques -->
                    <div
                        class="flex flex-col md:flex-row items-center md:items-start gap-6 flex-1"
                    >
                        <!-- Avatar avec badge numéro -->
                        <div class="relative flex-shrink-0">
                            <div
                                class="w-40 h-40 rounded-full bg-gradient-to-br from-blue-200 to-blue-400 border-8 border-white shadow-lg flex items-center justify-center"
                            >
                                <span class="text-3xl font-bold text-white">{{
                                    initials
                                }}</span>
                            </div>
                            <div
                                class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-gradient-to-br from-blue-500 to-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-lg min-w-32 text-center"
                            >
                                {{ patient.numero }}
                            </div>
                        </div>

                        <!-- Informations principales -->
                        <div
                            class="text-center flex flex-col gap-4 md:text-left mt-10 md:mt-3"
                        >
                            <h1 class="text-lg font-bold text-gray-600 mb-2">
                                {{ patient.nom }} {{ patient.prenom }}
                            </h1>
                            <div
                                class="flex flex-wrap items-center gap-2 justify-center md:justify-start"
                            >
                                <span
                                    class="px-2 py-1 rounded-full bg-blue-100 text-blue-600 border border-blue-200/70 font-semibold text-sm"
                                >
                                    <fonta icon="user-tag" class="mr-1" />{{
                                        patient.type
                                    }}
                                </span>
                                <span
                                    v-if="patient.telephone"
                                    class="px-3 py-1 rounded-full bg-green-50 text-green-700 font-medium text-sm border border-green-200/70"
                                >
                                    <fonta icon="phone" class="mr-1" />{{
                                        patient.telephone
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div
                        class="flex flex-col items-center bg-gray-50 p-4 rounded-xl border border-gray-100/60"
                    >
                        <a-qrcode
                            :value="qrCodeValue"
                            :size="150"
                            class="qr-code"
                            :ref="qrCodeRef"
                            :bordered="false"
                            errorLevel="H"
                        />
                        <p class="text-xs text-gray-500 mt-2 text-center">
                            Scannez pour accéder aux<br />informations du
                            patient
                        </p>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-6">
                <div
                    class="bg-white p-4 rounded-xl border border-gray-100/60 shadow-sm flex items-center gap-4"
                >
                    <div
                        class="bg-gradient-to-br from-blue-100 to-blue-200 p-4 rounded-xl shadow-sm"
                    >
                        <fonta
                            icon="calendar-check"
                            class="text-blue-600 text-xl"
                        />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Consultations
                        </p>
                        <p class="text-lg font-bold text-blue-700">
                            {{ patient.consultations_count }}
                        </p>
                    </div>
                </div>

                <div
                    class="bg-white p-4 rounded-xl border border-gray-100/60 shadow-sm flex items-center gap-4"
                >
                    <div
                        class="bg-gradient-to-br from-green-100 to-green-200 p-4 rounded-xl shadow-sm"
                    >
                        <fonta
                            icon="file-medical"
                            class="text-green-600 text-lg"
                        />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Dossiers actifs
                        </p>
                        <p class="text-lg font-bold text-green-700">
                            {{ totalDossiers }}
                        </p>
                    </div>
                </div>

                <div
                    class="bg-white p-4 rounded-xl border border-gray-100/60 shadow-sm flex items-center gap-4"
                >
                    <div
                        class="bg-gradient-to-br from-purple-100 to-purple-200 p-4 rounded-xl shadow-sm"
                    >
                        <fonta icon="user-md" class="text-purple-600 text-lg" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Dernière visite
                        </p>
                        <p class="text-lg font-bold text-purple-700">
                            {{
                                lastConsultationDate
                                    ? formatDate(lastConsultationDate)
                                    : "N/A"
                            }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <a-tabs v-model:activeKey="activeKey" type="card">
                <!-- Onglet 1 - Informations -->
                <a-tab-pane key="1">
                    <template #tab>
                        <span class="flex items-center gap-2">
                            <fonta icon="id-card" class="text-lg" />
                            Informations du Patient
                        </span>
                    </template>

                    <div
                        class="bg-white rounded-xl shadow-md border border-gray-100/60 p-8"
                    >
                        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                            <!-- Colonne 1 - Identité -->
                            <div class="space-y-5">
                                <h3
                                    class="text-lg font-semibold text-blue-500 flex items-center gap-2 pb-2 border-b border-blue-100/70"
                                >
                                    <fonta
                                        icon="user-circle"
                                        class="text-blue-500"
                                    />
                                    Identité
                                </h3>

                                <div
                                    class="grid grid-cols-1 sm:grid-cols-2 gap-6"
                                >
                                    <div
                                        class="info-card bg-blue-50/70 p-5 rounded-xl border border-gray-100/60"
                                    >
                                        <label
                                            class="text-sm text-gray-600 font-medium block mb-2"
                                            >Date naissance</label
                                        >
                                        <p
                                            class="font-medium text-gray-600 flex items-center"
                                        >
                                            {{
                                                formatDate(
                                                    patient.date_naissance
                                                )
                                            }}
                                        </p>
                                    </div>

                                    <div
                                        class="info-card bg-blue-50/70 p-5 rounded-xl border border-gray-100/60"
                                    >
                                        <label
                                            class="text-sm text-gray-600 font-medium block mb-2"
                                            >Âge</label
                                        >
                                        <p
                                            class="font-medium text-gray-600 flex items-center"
                                        >
                                            {{
                                                calculateAge(
                                                    patient.date_naissance
                                                )
                                            }}
                                            ans
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Colonne 2 - Contacts -->
                            <div class="space-y-5">
                                <h3
                                    class="text-lg font-semibold text-blue-500 flex items-center gap-2 pb-2 border-b border-blue-100/70"
                                >
                                    <fonta
                                        icon="address-book"
                                        class="text-blue-500"
                                    />
                                    Contacts
                                </h3>

                                <div class="space-y-4">
                                    <div
                                        class="info-card bg-green-50/70 p-5 rounded-xl border border-gray-100/60"
                                    >
                                        <label
                                            class="text-sm text-gray-600 font-medium block mb-2"
                                            >Téléphone</label
                                        >
                                        <p
                                            class="font-medium text-gray-600 flex items-center"
                                        >
                                            {{
                                                patient.telephone ||
                                                "Non renseigné"
                                            }}
                                        </p>
                                    </div>

                                    <div
                                        class="info-card bg-green-50/70 p-5 rounded-xl border border-gray-100/60"
                                    >
                                        <label
                                            class="text-sm text-gray-600 font-medium block mb-2"
                                            >Email</label
                                        >
                                        <p
                                            class="font-medium text-gray-600 flex items-center"
                                        >
                                            {{
                                                patient.email || "Non renseigné"
                                            }}
                                        </p>
                                    </div>

                                    <div
                                        class="info-card bg-green-50/70 p-5 rounded-xl border border-gray-100/60"
                                    >
                                        <label
                                            class="text-sm text-gray-600 font-medium block mb-2"
                                            >Adresse</label
                                        >
                                        <p
                                            class="font-medium text-gray-600 flex items-center"
                                        >
                                            {{
                                                patient.adresse ||
                                                "Non renseigné"
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Colonne 3 - Référent (conditional for FAMILLE) -->
                            <div
                                class="space-y-5"
                                v-if="patient.type === 'FAMILLE'"
                            >
                                <h3
                                    class="text-lg font-semibold text-blue-500 flex items-center gap-2 pb-2 border-b border-blue-100/70"
                                >
                                    <fonta icon="users" class="text-blue-500" />
                                    Référent Familial
                                </h3>

                                <div class="space-y-4">
                                    <div
                                        class="info-card bg-gray-50/70 p-4 rounded-lg border border-gray-100/60"
                                    >
                                        <label
                                            class="text-sm text-gray-500 block mb-1"
                                            >Nom complet</label
                                        >
                                        <p
                                            class="font-medium text-gray-600 flex items-center"
                                        >
                                            <fonta
                                                icon="user"
                                                class="text-blue-500 mr-2"
                                            />
                                            {{ patient.salarie?.nom }}
                                            {{ patient.salarie?.prenom }}
                                        </p>
                                    </div>

                                    <div
                                        class="info-card bg-gray-50/70 p-4 rounded-lg border border-gray-100/60"
                                    >
                                        <label
                                            class="text-sm text-gray-500 block mb-1"
                                            >Lien de parenté</label
                                        >
                                        <p
                                            class="font-medium text-gray-600 flex items-center"
                                        >
                                            {{
                                                patient.lien_parente ||
                                                "Non renseigné"
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Colonne 4 - Professionnel (conditional for SALARIE) -->
                            <div
                                class="space-y-5 md:col-span-2 lg:col-span-1"
                                v-if="patient.type === 'SALARIE'"
                            >
                                <h3
                                    class="text-lg font-semibold text-blue-500 flex items-center gap-2 pb-2 border-b border-blue-100/70"
                                >
                                    Situation Professionnelle
                                </h3>

                                <div
                                    class="grid grid-cols-1 sm:grid-cols-2 gap-4"
                                >
                                    <div
                                        class="info-card bg-gray-50/70 p-4 rounded-lg border border-gray-100/60"
                                    >
                                        <label
                                            class="text-sm text-gray-500 block mb-1"
                                            >Société</label
                                        >
                                        <p
                                            class="font-medium text-gray-600 flex items-center"
                                        >
                                            {{ patient.societe?.nom }}
                                        </p>
                                    </div>

                                    <div
                                        class="info-card bg-gray-50/70 p-4 rounded-lg border border-gray-100/60"
                                    >
                                        <label
                                            class="text-sm text-gray-500 block mb-1"
                                            >Poste</label
                                        >
                                        <p
                                            class="font-medium text-gray-600 flex items-center"
                                        >
                                            {{ patient.poste }}
                                        </p>
                                    </div>

                                    <div
                                        class="info-card bg-gray-50/70 p-4 rounded-lg border border-gray-100/60"
                                    >
                                        <label
                                            class="text-sm text-gray-500 block mb-1"
                                            >Statut</label
                                        >
                                        <p
                                            class="font-medium text-gray-600 flex items-center"
                                        >
                                            <span
                                                :class="{
                                                    'text-green-600':
                                                        patient.statut_emploi ===
                                                            'CDI' ||
                                                        patient.statut_emploi ===
                                                            'Permanent',
                                                    'text-orange-600':
                                                        patient.statut_emploi ===
                                                        'CDD',
                                                    'text-red-600':
                                                        patient.statut_emploi ===
                                                        'Temporaire',
                                                }"
                                            >
                                                {{ patient.statut_emploi }}
                                            </span>
                                        </p>
                                    </div>

                                    <div
                                        v-if="
                                            ['CDD', 'Temporaire'].includes(
                                                patient.statut_emploi
                                            )
                                        "
                                        class="info-card bg-gray-50/70 p-4 rounded-lg border border-gray-100/60"
                                    >
                                        <label
                                            class="text-sm text-gray-500 block mb-1"
                                            >Fin contrat</label
                                        >
                                        <p
                                            class="font-medium text-gray-500 flex items-center"
                                        >
                                            {{
                                                patient.date_fin_contrat
                                                    ? formatDate(
                                                          patient.date_fin_contrat
                                                      )
                                                    : "Non précisé"
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a-tab-pane>

                <!-- Onglet 2 - Constantes Vitales -->
                <a-tab-pane key="2">
                    <template #tab>
                        <span class="flex items-center gap-2">
                            <fonta icon="heartbeat" class="text-lg" />
                            Constantes Vitales
                        </span>
                    </template>

                    <div
                        class="bg-white rounded-xl shadow-md border border-gray-100/60 p-8"
                    >
                        <h3
                            class="text-xl font-semibold text-blue-500 flex items-center gap-2 pb-4 mb-6 border-b border-blue-100/70"
                        >
                            <fonta
                                icon="clipboard-list"
                                class="text-blue-500"
                            />
                            Constantes médicales
                        </h3>

                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
                        >
                            <!-- Poids -->
                            <div
                                class="bg-gradient-to-br from-white to-blue-50 p-6 rounded-xl border border-blue-100/70 transition-all duration-300 hover:border-blue-300"
                            >
                                <div class="flex items-center gap-4">
                                    <div
                                        class="bg-blue-100 p-4 rounded-lg shadow-sm"
                                    >
                                        <fonta
                                            icon="weight"
                                            class="text-lg text-blue-600"
                                        />
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm text-gray-600 font-medium mb-1"
                                        >
                                            Poids
                                        </p>
                                        <div class="flex items-end gap-2">
                                            <p
                                                class="font-semibold text-lg text-gray-600"
                                            >
                                                {{
                                                    patient.poids
                                                        ? patient.poids
                                                        : "—"
                                                }}
                                            </p>
                                            <span
                                                v-if="patient.poids"
                                                class="text-sm text-gray-500 mb-1"
                                                >kg</span
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="mt-3 text-xs text-gray-400"
                                    v-if="!patient.poids"
                                >
                                    Aucune donnée disponible
                                </div>
                            </div>

                            <!-- Taille -->
                            <div
                                class="bg-gradient-to-br from-white to-green-50 p-6 rounded-xl border border-green-100/70 transition-all duration-300 hover:border-green-300"
                            >
                                <div class="flex items-center gap-4">
                                    <div
                                        class="bg-green-100 p-4 rounded-lg shadow-sm"
                                    >
                                        <fonta
                                            icon="ruler"
                                            class="text-lg text-green-600"
                                        />
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm text-gray-600 font-medium mb-1"
                                        >
                                            Taille
                                        </p>
                                        <div class="flex items-end gap-2">
                                            <p
                                                class="font-semibold text-lg text-gray-600"
                                            >
                                                {{
                                                    patient.taille
                                                        ? patient.taille
                                                        : "—"
                                                }}
                                            </p>
                                            <span
                                                v-if="patient.taille"
                                                class="text-sm text-gray-500 mb-1"
                                                >cm</span
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="mt-3 text-xs text-gray-400"
                                    v-if="!patient.taille"
                                >
                                    Aucune donnée disponible
                                </div>
                            </div>

                            <!-- Rythme cardiaque -->
                            <div
                                class="bg-gradient-to-br from-white to-red-50 p-6 rounded-xl border border-red-100/70 transition-all duration-300 hover:border-red-300"
                            >
                                <div class="flex items-center gap-4">
                                    <div
                                        class="bg-red-100 p-4 rounded-lg shadow-sm"
                                    >
                                        <fonta
                                            icon="heart-pulse"
                                            class="text-lg text-red-600"
                                        />
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm text-gray-600 font-medium mb-1"
                                        >
                                            Rythme cardiaque
                                        </p>
                                        <div class="flex items-end gap-2">
                                            <p
                                                class="font-semibold text-lg text-gray-600"
                                            >
                                                {{
                                                    patient.freq_card
                                                        ? patient.freq_card
                                                        : "—"
                                                }}
                                            </p>
                                            <span
                                                v-if="patient.freq_card"
                                                class="text-sm text-gray-500 mb-1"
                                                >bpm</span
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="mt-3 text-xs text-gray-400"
                                    v-if="!patient.freq_card"
                                >
                                    Aucune donnée disponible
                                </div>
                            </div>

                            <!-- Température -->
                            <div
                                class="bg-gradient-to-br from-white to-orange-50 p-6 rounded-xl border border-orange-100/70 transition-all duration-300 hover:border-orange-300"
                            >
                                <div class="flex items-center gap-4">
                                    <div
                                        class="bg-orange-100 p-4 rounded-lg shadow-sm"
                                    >
                                        <fonta
                                            icon="temperature-high"
                                            class="text-lg text-orange-600"
                                        />
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm text-gray-600 font-medium mb-1"
                                        >
                                            Température
                                        </p>
                                        <div class="flex items-end gap-2">
                                            <p
                                                class="font-semibold text-lg text-gray-600"
                                            >
                                                {{
                                                    patient.temperature
                                                        ? patient.temperature
                                                        : "—"
                                                }}
                                            </p>
                                            <span
                                                v-if="patient.temperature"
                                                class="text-sm text-gray-500 mb-1"
                                                >°C</span
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="mt-3 text-xs text-gray-400"
                                    v-if="!patient.temperature"
                                >
                                    Aucune donnée disponible
                                </div>
                            </div>
                        </div>

                        <!-- Section supplémentaire pour IMC si la valeur est disponible -->
                        <div class="mt-8" v-if="patient.imc">
                            <div
                                class="bg-gray-50 p-6 rounded-xl border border-gray-200/70"
                            >
                                <div class="flex items-center">
                                    <div>
                                        <p
                                            class="text-sm text-gray-500 font-medium mb-1"
                                        >
                                            Indice de Masse Corporelle (IMC)
                                        </p>
                                        <div class="flex items-end gap-2">
                                            <p
                                                class="text-lg font-semibold"
                                                :class="
                                                    getIMCColor(patient.imc)
                                                "
                                            >
                                                {{
                                                    patient.imc
                                                        ? patient.imc.toFixed(1)
                                                        : "-"
                                                }}
                                            </p>
                                            <span
                                                class="text-sm text-gray-500 mb-1"
                                                >kg/m²</span
                                            >
                                        </div>
                                        <p
                                            class="text-sm mt-1"
                                            :class="getIMCColor(patient.imc)"
                                        >
                                            {{ getIMCCategory(patient.imc) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Note informative -->
                        <div
                            class="mt-6 p-4 bg-blue-50/70 rounded-lg border border-blue-100/70 text-sm text-gray-600"
                        >
                            <p class="flex items-start gap-2">
                                <fonta
                                    icon="info-circle"
                                    class="text-blue-500 mt-1"
                                />
                                <span
                                    >Ces constantes correspondent aux dernières
                                    valeurs enregistrées lors des consultations
                                    médicales.</span
                                >
                            </p>
                        </div>
                    </div>
                </a-tab-pane>

                <!-- Onglet 3 - Consultations & Dossiers Medicaux -->
                <a-tab-pane key="3">
                    <template #tab>
                        <span class="flex items-center gap-2">
                            <fonta icon="file-medical" class="text-lg" />
                            Consultations & Dossiers Medicaux
                        </span>
                    </template>

                    <div
                        class="bg-white rounded-xl shadow-md border border-gray-100/60 p-6"
                    >
                        <h3
                            class="text-lg font-semibold text-blue-500 flex items-center gap-2 pb-4 mb-6 border-b border-blue-100/70"
                        >
                            <fonta icon="history" class="text-blue-500" />
                            Historique médical du patient
                        </h3>

                        <div
                            v-if="patient.consultations_count > 0"
                            class="space-y-8"
                        >
                            <!-- Timeline de consultations avec dropdown -->
                            <a-timeline>
                                <a-timeline-item
                                    v-for="consultation in patient.consultations"
                                    :key="consultation.id"
                                >
                                    <!-- Personnalisation du point de la timeline -->
                                    <template #dot>
                                        <fonta
                                            icon="calendar-check"
                                            class="text-green-600 text-lg"
                                        />
                                    </template>

                                    <!-- Contenu de l'élément de timeline -->
                                    <div
                                        class="consultation-item overflow-hidden shadow-sm"
                                    >
                                        <!-- En-tête de la consultation (toujours visible) -->
                                        <div
                                            class="flex flex-col md:flex-row justify-between items-start md:items-center p-4 bg-gradient-to-r from-gray-50 to-blue-50 border border-gray-200 rounded-t-lg"
                                        >
                                            <div
                                                class="flex items-center gap-2"
                                            >
                                                <div>
                                                    <h4 class="text-blue-600">
                                                        {{
                                                            formatDate(
                                                                consultation.created_at
                                                            )
                                                        }}
                                                    </h4>
                                                </div>
                                            </div>

                                            <div class="mt-3 md:mt-0">
                                                <p
                                                    class="text-md font-semibold text-blue-600 flex items-center gap-2 px-2 py-1 bg-blue-50 border border-blue-100 rounded-lg"
                                                >
                                                    <fonta icon="user-md" />
                                                    Dr.
                                                    {{
                                                        consultation.medecin
                                                            ?.nom
                                                    }}
                                                    {{
                                                        consultation.medecin
                                                            ?.prenom
                                                    }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Dropdown pour les détails de la consultation -->
                                        <a-collapse
                                            class="border-0 custom-collapse"
                                        >
                                            <a-collapse-panel key="1">
                                                <template #header>
                                                    <div
                                                        class="text-md font-medium text-gray-600 flex items-center gap-2"
                                                    >
                                                        <fonta
                                                            icon="folder-open"
                                                            class="text-blue-500"
                                                        />
                                                        Détails et documents
                                                        médicaux
                                                    </div>
                                                </template>

                                                <div class="p-6 space-y-5">
                                                    <!-- Diagnostic si disponible -->
                                                    <div
                                                        v-if="
                                                            consultation.diagnostic
                                                        "
                                                        class="p-5 bg-white rounded-lg shadow-sm border border-gray-200"
                                                    >
                                                        <h5
                                                            class="font-medium text-sm text-gray-600 mb-2 flex items-center gap-2"
                                                        >
                                                            Diagnostic
                                                        </h5>
                                                        <p
                                                            class="text-gray-700 leading-relaxed"
                                                        >
                                                            {{
                                                                consultation.diagnostic
                                                            }}
                                                        </p>
                                                    </div>

                                                    <!-- Section Documents -->
                                                    <div
                                                        class="border border-gray-200 p-6 rounded-lg bg-white shadow-sm transition-all duration-300"
                                                    >
                                                        <h3
                                                            class="text-sm font-medium mb-6 text-gray-700 flex items-center gap-2"
                                                        >
                                                            Documents associés
                                                        </h3>
                                                        <div
                                                            class="flex sm:flex-row flex-col gap-3 mt-2"
                                                        >
                                                            <button
                                                                class="flex items-center gap-2 px-4 py-2 bg-white text-gray-700 rounded-md text-sm font-medium transition-all duration-200 hover:bg-gray-50 border border-gray-300 shadow-sm"
                                                                @click="
                                                                    viewDocument(
                                                                        'certificat_medical',
                                                                        consultation
                                                                    )
                                                                "
                                                            >
                                                                <fonta
                                                                    icon="file-medical"
                                                                    class="text-blue-500"
                                                                />
                                                                Certificat
                                                                Médical
                                                            </button>
                                                            <button
                                                                v-if="
                                                                    consultation.ordonnance
                                                                "
                                                                class="flex items-center gap-2 px-4 py-2 bg-white text-gray-700 rounded-md text-sm font-medium transition-all duration-200 hover:bg-gray-50 border border-gray-300 shadow-sm"
                                                                @click="
                                                                    viewDocument(
                                                                        'ordonnance',
                                                                        consultation
                                                                    )
                                                                "
                                                            >
                                                                <fonta
                                                                    icon="prescription"
                                                                    class="text-green-600"
                                                                />
                                                                Ordonnance
                                                            </button>
                                                            <button
                                                                v-if="
                                                                    consultation.demande_examen
                                                                "
                                                                class="flex items-center gap-2 px-4 py-2.5 bg-white text-gray-700 rounded-md text-sm font-medium transition-all duration-200 hover:bg-gray-50 border border-gray-300 shadow-sm"
                                                                @click="
                                                                    viewDocument(
                                                                        'demande_examen',
                                                                        consultation
                                                                    )
                                                                "
                                                            >
                                                                <fonta
                                                                    icon="microscope"
                                                                    class="text-blue-600"
                                                                />
                                                                Examen
                                                            </button>
                                                            <button
                                                                v-if="
                                                                    consultation.lettre_reference
                                                                "
                                                                class="flex items-center gap-2 px-4 py-2.5 bg-white text-gray-700 rounded-md text-sm font-medium transition-all duration-200 hover:bg-gray-50 border border-gray-300 shadow-sm"
                                                                @click="
                                                                    viewDocument(
                                                                        'lettre_reference',
                                                                        consultation
                                                                    )
                                                                "
                                                            >
                                                                <fonta
                                                                    icon="envelope-open-text"
                                                                    class="text-blue-600"
                                                                />
                                                                Lettre de
                                                                référence
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a-collapse-panel>
                                        </a-collapse>
                                    </div>
                                </a-timeline-item>
                            </a-timeline>
                        </div>

                        <!-- Message si aucune consultation -->
                        <div
                            v-if="patient.consultations_count === 0"
                            class="flex flex-col items-center justify-center py-16 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl border border-blue-100/70"
                        >
                            <div class="mb-4">
                                <a-empty :description="null" />
                            </div>
                            <h4 class="text-xl font-medium text-gray-600 mb-2">
                                Aucune consultation
                            </h4>
                            <p class="text-gray-500 text-center max-w-md">
                                Ce patient n'a encore aucune consultation
                                enregistrée dans le système.
                            </p>
                        </div>

                        <!-- Note informative pour les consultations -->
                        <div
                            v-if="patient.consultations_count > 0"
                            class="mt-6 p-5 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100/70 text-sm text-gray-600"
                        >
                            <p class="flex items-start gap-2">
                                <fonta
                                    icon="info-circle"
                                    class="text-blue-500 mt-1"
                                />
                                <span
                                    >Cliquez sur les boutons des documents pour
                                    les visualiser, les imprimer ou les
                                    télécharger.</span
                                >
                            </p>
                        </div>
                    </div>
                </a-tab-pane>
            </a-tabs>
        </div>
    </div>
    <!-- Modal de prévisualisation du document -->
    <a-modal
        v-model:open="previewModalVisible"
        width="600px"
        :closable="null"
        style="top: 15px"
        :footer="null"
        class="document-preview-modal"
    >
        <iframe
            :src="documentPreviewUrl"
            style="width: 100%; height: 80vh; border: none"
        ></iframe>
        <div class="flex justify-end gap-4 border-t pt-4">
            <BaseButton @click="printDocument" class="py-2">
                <fonta icon="print" />
                <span>Imprimer</span>
            </BaseButton>
            <BaseButton
                @click="downloadDocument"
                bg-color="bg-green-600"
                hover-color="bg-green-700"
                focus-color="ring-green-400"
                active-color="bg-green-800"
                class="py-2"
            >
                <fonta icon="download" />
                <span>Télécharger PDF</span>
            </BaseButton>
        </div>
    </a-modal>
</template>
<script setup>
import BaseButton from "@/Components/BaseButton.vue";
import TestLayout from "@/Layouts/TestLayout.vue";
import { computed, defineOptions, defineProps, ref } from "vue";

defineOptions({
    layout: TestLayout,
});
const activeKey = ref("1");
const props = defineProps({
    patient: Object,
    totalDossiers: Number,
    lastConsultationDate: String,
});
const initials = computed(() => {
    let init = "";
    if (props.patient.nom) {
        init += props.patient.nom.charAt(0).toUpperCase();
    }
    if (props.patient.prenom) {
        init += props.patient.prenom.charAt(0).toUpperCase();
    }
    return init;
});
const formatDate = (date) => {
    return new Date(date).toLocaleDateString("fr-FR", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};
// Fonction calcul âge
const calculateAge = (dateNaissance) => {
    const birthDate = new Date(dateNaissance);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();

    if (
        monthDiff < 0 ||
        (monthDiff === 0 && today.getDate() < birthDate.getDate())
    ) {
        age--;
    }
    return age;
};

// Fonction pour déterminer la catégorie IMC
const getIMCCategory = (imc) => {
    if (!imc) return "";

    if (imc < 18.5) return "Insuffisance pondérale";
    if (imc < 25) return "Corpulence normale";
    if (imc < 30) return "Surpoids";
    if (imc < 35) return "Obésité modérée";
    if (imc < 40) return "Obésité sévère";
    return "Obésité morbide";
};
// Fonction pour déterminer la couleur selon l'IMC
const getIMCColor = (imc) => {
    if (!imc) return "";

    if (imc < 18.5) return "text-blue-600"; // Insuffisance
    if (imc < 25) return "text-green-600"; // Normal
    if (imc < 30) return "text-orange-500"; // Surpoids
    if (imc < 35) return "text-orange-600"; // Obésité modérée
    return "text-red-600"; // Obésité sévère/morbide
};
// information du badge
const badge = computed(() => {
    return props.patient.badge;
});

// Create a computed property for the QR code value
const qrCodeValue = computed(() => {
    return JSON.stringify({
        id: props.patient.id,
        nom: props.patient.nom,
        prenom: props.patient.prenom,
        numero: props.patient.numero,
        qr_code: badge.value.qr_code,
        // Add any other information you want to include
    });
});
const qrCodeRef = ref(null);
//Pour l'affichage des modals des documents prêts à être imprimé ou telechargé
const previewModalVisible = ref(false);
const previewDocumentTitle = ref("");
const selectedConsultation = ref(null);
const documentPreviewUrl = ref("");
const documentType = ref("");

/**
 * Ouvre le modal apperçu du contenu du document
 */
const viewDocument = async (docType, consultation) => {
    selectedConsultation.value = consultation;
    documentType.value = docType;
    // Définir le titre du document
    switch (docType) {
        case "ordonnance":
            previewDocumentTitle.value = "Ordonnance";
            break;
        case "demande_examen":
            previewDocumentTitle.value = "Demande d'examen";
            break;
        case "lettre_reference":
            previewDocumentTitle.value = "Lettre de référence";
            break;
        case "certificat_medical":
            previewDocumentTitle.value = "Certificat Médical";
            break;
        default:
            previewDocumentTitle.value = "Document";
    }

    // Construire l'URL de prévisualisation
    documentPreviewUrl.value = route("admin.document.preview", {
        type: docType,
        consultation: consultation.id,
    });

    // Afficher le modal
    previewModalVisible.value = true;
};

/**
 * Imprime le contenu du document
 */
const printDocument = () => {
    const iframe = document.querySelector("iframe");
    iframe.contentWindow.print();
};

/**
 * Télécharge le document PDF
 */
const downloadDocument = () => {
    // Fermer le modal de prévisualisation
    previewModalVisible.value = false;

    // Utiliser la route de téléchargement
    const downloadUrl = route("admin.document.download", {
        type: documentType.value,
        consultation: selectedConsultation.value.id,
    });

    // Créer un lien temporaire pour le téléchargement
    const link = document.createElement("a");
    link.href = downloadUrl;
    link.download = `${previewDocumentTitle.value}_${selectedConsultation.value.id}.pdf`; // Force le téléchargement
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};
</script>

<style>
.ant-tabs-card.ant-tabs-top > .ant-tabs-nav .ant-tabs-tab {
    @apply !p-4 rounded-none bg-gray-100;
}
.ant-tabs-card.ant-tabs-top > .ant-tabs-nav .ant-tabs-tab:last-child {
    @apply !rounded-tr-3xl;
}
.ant-tabs-card.ant-tabs-top > .ant-tabs-nav .ant-tabs-tab:first-child {
    @apply !rounded-tl-3xl;
}

.ant-tabs .ant-tabs-tab-btn {
    @apply !text-base font-semibold;
}
.ant-tabs .ant-tabs-tab.ant-tabs-tab-active .ant-tabs-tab-btn {
    @apply !text-white;
}
.ant-tabs-card > .ant-tabs-nav .ant-tabs-tab-active,
:where(.css-dev-only-do-not-override-1p3hq3p).ant-tabs-card
    > div
    > .ant-tabs-nav
    .ant-tabs-tab-active {
    @apply !bg-blue-500 !text-white !important;
}
.ant-tabs > .ant-tabs-nav .ant-tabs-nav-list {
    @apply gap-1 !important;
}
.custom-collapse .ant-collapse-header {
    @apply bg-white rounded-none border-0 border-t border-gray-200 mb-0 hover:shadow-sm transition-all duration-200 !important;
}

.ant-collapse > .ant-collapse-item > .ant-collapse-header .ant-collapse-arrow {
    @apply text-gray-400 !important;
}

.qr-code {
    margin-top: 10px; /* Space between title and QR code */
}

.custom-collapse .ant-collapse-content-box {
    @apply bg-gradient-to-br from-gray-50 border border-gray-200 rounded-xl  rounded-t-none to-blue-50 !important;
}
</style>
