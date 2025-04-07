<template>
    <div class="mx-auto bg-white rounded-lg shadow-md p-8 space-y-8">
        <!-- En-tête avec dégradé -->
        <div
            class="patient-header bg-gray-50 p-8 rounded-2xl shadow-sm border border-blue-100"
        >
            <div
                class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 transition-all duration-300 "
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
                                class="w-40 h-40 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 border-8 border-white shadow-lg flex items-center justify-center transform transition-all duration-500 hover:scale-105"
                            >
                                <span class="text-4xl font-bold text-white">{{
                                    initials
                                }}</span>
                            </div>
                            <div
                                class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-lg min-w-32 text-center"
                            >
                                {{ patient.numero }}
                            </div>
                        </div>

                        <!-- Informations principales -->
                        <div
                            class="text-center flex flex-col gap-4 md:text-left mt-10 md:mt-3"
                        >
                            <h1 class="text-xl font-bold text-gray-600 mb-2">
                                {{ patient.nom }} {{ patient.prenom }}
                            </h1>
                            <div
                                class="flex flex-wrap items-center gap-2 justify-center md:justify-start"
                            >
                                <span
                                    class="px-3 py-1 rounded-full bg-blue-100 text-blue-600 border border-blue-200 font-normal"
                                >
                                    <fonta icon="user-tag" class="mr-1" />{{
                                        patient.type
                                    }}
                                </span>
                                <span
                                    v-if="patient.telephone"
                                    class="px-3 py-1 rounded-full bg-green-50 text-green-700 font-medium border border-green-200"
                                >
                                    <fonta icon="phone" class="mr-1" />{{
                                        patient.telephone
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Colonne droite QR Code -->
                    <div
                        class="flex flex-col items-center bg-gray-50 p-4 rounded-xl border border-gray-100"
                    >
                        <a-qrcode
                            :value="qrCodeValue"
                            :size="100"
                            class="qr-code"
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
                    class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4  "
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
                        <p class="text-xl font-bold text-blue-700">
                            {{ patient.consultations_count }}
                        </p>
                    </div>
                </div>

                <div
                    class="bg-white p-5 rounded-xl border borde-graborder-gray-100 shadow-sm flex items-center gap-4 "
                >
                    <div
                        class="bg-gradient-to-br from-green-100 to-green-200 p-4 rounded-xl shadow-sm"
                    >
                        <fonta
                            icon="file-medical"
                            class="text-green-600 text-xl"
                        />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Dossiers actifs
                        </p>
                        <p class="text-xl font-bold text-green-700">
                            {{ totalDossiers }}
                        </p>
                    </div>
                </div>

                <div
                    class="bg-white p-5 rounded-xl border border-graborder-gray-100 shadow-sm flex items-center gap-4 "
                >
                    <div
                        class="bg-gradient-to-br from-purple-100 to-purple-200 p-4 rounded-xl shadow-sm"
                    >
                        <fonta icon="user-md" class="text-purple-600 text-xl" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Dernière visite
                        </p>
                        <p class="text-xl font-bold text-purple-700">
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
                        class="bg-white rounded-xl shadow-md border border-gray-100 p-8"
                    >
                        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                            <!-- Colonne 1 - Identité -->
                            <div class="space-y-5">
                                <h3
                                    class="text-xl font-semibold text-blue-500 flex items-center gap-2 pb-2 border-b border-blue-100"
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
                                        class="info-card bg-blue-50 p-5 rounded-xl border border-gray-100 "
                                    >
                                        <label
                                            class="text-sm text-gray-600 font-medium block mb-2"
                                            >Date naissance</label
                                        >
                                        <p
                                            class="font-medium  text-gray-600 flex items-center"
                                        >
                                            {{
                                                formatDate(
                                                    patient.date_naissance
                                                )
                                            }}
                                        </p>
                                    </div>

                                    <div
                                        class="info-card bg-blue-50 p-5 rounded-xl border border-gray-100 "
                                    >
                                        <label
                                            class="text-sm text-gray-600 font-medium block mb-2"
                                            >Âge</label
                                        >
                                        <p
                                            class="font-medium  text-gray-600 flex items-center"
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
                                    class="text-xl font-semibold text-blue-500 flex items-center gap-2 pb-2 border-b border-blue-100"
                                >
                                    <fonta
                                        icon="address-book"
                                        class="text-blue-500"
                                    />
                                    Contacts
                                </h3>

                                <div class="space-y-4">
                                    <div
                                        class="info-card bg-green-50 p-5 rounded-xl border border-gray-100 "
                                    >
                                        <label
                                            class="text-sm text-gray-600 font-medium block mb-2"
                                            >Téléphone</label
                                        >
                                        <p
                                            class="font-medium  text-gray-600 flex items-center"
                                        >
                                            {{
                                                patient.telephone ||
                                                "Non renseigné"
                                            }}
                                        </p>
                                    </div>

                                    <div
                                        class="info-card bg-green-50 p-5 rounded-xl border border-gray-100"
                                    >
                                        <label
                                            class="text-sm text-gray-600 font-medium block mb-2"
                                            >Email</label
                                        >
                                        <p
                                            class="font-medium  text-gray-600 flex items-center"
                                        >
                                            {{
                                                patient.email || "Non renseigné"
                                            }}
                                        </p>
                                    </div>

                                    <div
                                        class="info-card bg-green-50 p-5 rounded-xl border border-gray-100"
                                    >
                                        <label
                                            class="text-sm text-gray-600 font-medium block mb-2"
                                            >Adresse</label
                                        >
                                        <p
                                            class="font-medium  text-gray-600 flex items-center"
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
                                    class="text-xl font-semibold text-blue-500 flex items-center gap-2 pb-2 border-b border-blue-100"
                                >
                                    <fonta icon="users" class="text-blue-500" />
                                    Référent Familial
                                </h3>

                                <div class="space-y-4">
                                    <div
                                        class="info-card bg-gray-50 p-4 rounded-lg border border-gray-100"
                                    >
                                        <label
                                            class="text-sm text-gray-500 block mb-1"
                                            >Nom complet</label
                                        >
                                        <p
                                            class="font-medium  text-gray-600 flex items-center"
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
                                        class="info-card bg-gray-50 p-4 rounded-lg border border-gray-100"
                                    >
                                        <label
                                            class="text-sm text-gray-500 block mb-1"
                                            >Lien de parenté</label
                                        >
                                        <p
                                            class="font-medium  text-gray-600 flex items-center"
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
                                    class="text-xl font-semibold text-blue-500 flex items-center gap-2 pb-2 border-b border-blue-100"
                                >
                                    Situation Professionnelle
                                </h3>

                                <div
                                    class="grid grid-cols-1 sm:grid-cols-2 gap-4"
                                >
                                    <div
                                        class="info-card bg-gray-50 p-4 rounded-lg border border-gray-100"
                                    >
                                        <label
                                            class="text-sm text-gray-500 block mb-1"
                                            >Société</label
                                        >
                                        <p
                                            class="font-medium  text-gray-600 flex items-center"
                                        >
                                            {{ patient.societe?.nom }}
                                        </p>
                                    </div>

                                    <div
                                        class="info-card bg-gray-50 p-4 rounded-lg border border-gray-100"
                                    >
                                        <label
                                            class="text-sm text-gray-500 block mb-1"
                                            >Poste</label
                                        >
                                        <p
                                            class="font-medium  text-gray-600 flex items-center"
                                        >
                                            {{ patient.poste }}
                                        </p>
                                    </div>

                                    <div
                                        class="info-card bg-gray-50 p-4 rounded-lg border border-gray-100"
                                    >
                                        <label
                                            class="text-sm text-gray-500 block mb-1"
                                            >Statut</label
                                        >
                                        <p
                                            class="font-medium  text-gray-600 flex items-center"
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
                                        class="info-card bg-gray-50 p-4 rounded-lg border border-gray-100"
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
                        class="bg-white rounded-xl shadow-md border border-gray-100 p-8"
                    >
                        <h3
                            class="text-xl font-semibold text-blue-500 flex items-center gap-2 pb-4 mb-6 border-b border-blue-100"
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
                                class="bg-gradient-to-br from-white to-blue-50 p-6 rounded-xl border border-blue-100 transition-all duration-300 hover:border-blue-300"
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
                                                class=" font-bold text-lg  text-gray-600"
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
                                class="bg-gradient-to-br from-white to-green-50 p-6 rounded-xl border border-green-100 transition-all duration-300 hover:border-green-300"
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
                                                class="font-bold text-lg  text-gray-600"
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
                                class="bg-gradient-to-br from-white to-red-50 p-6 rounded-xl border border-red-100 transition-all duration-300 hover:border-red-300"
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
                                                class="font-bold text-lg  text-gray-600"
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
                                class="bg-gradient-to-br from-white to-orange-50 p-6 rounded-xl border border-orange-100 transition-all duration-300 hover:border-orange-300"
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
                                                class="font-bold text-lg  text-gray-600"
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
                                class="bg-gray-50 p-6 rounded-xl border border-gray-200"
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
                                                {{ patient.imc }}
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
                            class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-100 text-sm text-gray-600"
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
                            <fonta icon="file-medical" class="text-xl" />
                            Consultations & Dossiers Medicaux
                        </span>
                    </template>

                    <div
                        class="bg-white rounded-xl shadow-md border border-gray-100 p-8"
                    >
                        <h3
                            class="text-xl font-semibold text-blue-500 flex items-center gap-2 pb-4 mb-6 border-b border-blue-100"
                        >
                            <fonta icon="history" class="text-blue-500" />
                            Historique médical du patient
                        </h3>

                        <div
                            v-if="patient.consultations_count > 0"
                            class="space-y-6"
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
                                                class="text-green-600 text-sm"
                                            />
                                       
                                    </template>

                                    <!-- Contenu de l'élément de timeline -->
                                    <div
                                        class="consultation-item border border-gray-100 rounded-xl overflow-hidden  shadow-sm"
                                    >
                                        <!-- En-tête de la consultation (toujours visible) -->
                                        <div
                                            class="flex flex-col md:flex-row justify-between items-start md:items-center p-5 bg-gradient-to-r from-gray-50 to-blue-50"
                                        >
                                            <div
                                                class="flex items-center gap-3"
                                            >
                                                <div>
                                                    <h4
                                                        class="  text-blue-600"
                                                    >
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
                                                    class="text-md font-semibold text-blue-600 flex items-center gap-2 px-3 py-1 bg-blue-50 border border-blue-100 rounded-lg"
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
                                                        />
                                                        Détails et documents
                                                        médicaux
                                                    </div>
                                                </template>

                                                <div
                                                    class="p-6 space-y-5 bg-white"
                                                >
                                                    <!-- Diagnostic si disponible -->
                                                    <div
                                                        v-if="
                                                            consultation.diagnostic
                                                        "
                                                        class="p-5 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl border border-gray-200"
                                                    >
                                                        <h5
                                                            class="font-medium text-blue-500 mb-3 flex items-center gap-2"
                                                        >

                                                            Diagnostique
                                                        </h5>
                                                        <p
                                                            class="text-gray-700 leading-relaxed"
                                                        >
                                                            {{
                                                                consultation.diagnostic
                                                            }}
                                                        </p>
                                                    </div>

                                                    <!-- Documents médicaux -->
                                                    <div
                                                        class="space-y-3 bg-gray-50 p-5 rounded-xl border border-gray-100"
                                                    >
                                                        <h5
                                                            class="font-medium text-gray-600 mb-4 flex items-center gap-2"
                                                        >

                                                            Documents associés
                                                        </h5>

                                                        <div
                                                            class="flex flex-wrap gap-3"
                                                        >
                                                            <button
                                                                v-if="
                                                                    consultation.ordonnance
                                                                "
                                                                @click="
                                                                    viewDocument(
                                                                        'ordonnance',
                                                                        consultation
                                                                    )
                                                                "
                                                                class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-100 to-green-200 text-green-700 rounded-lg text-sm font-medium border border-green-200 transition-all duration-300 hover:bg-green-200"
                                                            >
                                                                <fonta
                                                                    icon="prescription"
                                                                />
                                                                Ordonnance
                                                            </button>

                                                            <button
                                                                v-if="
                                                                    consultation.demande_examen
                                                                "
                                                                @click="
                                                                    viewDocument(
                                                                        'demande_examen',
                                                                        consultation
                                                                    )
                                                                "
                                                                class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-100 to-blue-200 text-blue-700 rounded-lg text-sm font-medium border border-blue-200 transition-all duration-300 hover:bg-blue-200"
                                                            >
                                                                <fonta
                                                                    icon="microscope"
                                                                />
                                                                Examen
                                                            </button>

                                                            <button
                                                                v-if="
                                                                    consultation.lettre_reference
                                                                "
                                                                @click="
                                                                    viewDocument(
                                                                        'lettre_reference',
                                                                        consultation
                                                                    )
                                                                "
                                                                class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-100 to-purple-200 text-purple-700 rounded-lg text-sm font-medium border border-purple-200 transition-all duration-300 hover:bg-purple-200"
                                                            >
                                                                <fonta
                                                                    icon="envelope-open-text"
                                                                />
                                                                Lettre de
                                                                référence
                                                            </button>

                                                            <span
                                                                v-if="
                                                                    !consultation.ordonnance &&
                                                                    !consultation.demande_examen &&
                                                                    !consultation.lettre_reference
                                                                "
                                                                class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm border border-gray-200"
                                                            >
                                                                <fonta
                                                                    icon="file-alt"
                                                                    class="mr-2"
                                                                />
                                                                Aucun document
                                                                associé
                                                            </span>
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
                            class="flex flex-col items-center justify-center py-16 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl border border-blue-100"
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
                            class="mt-6 p-5 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100 text-sm text-gray-600"
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
        width="700px"
        :closable="null"
        style="top: 15px"
        :footer="null"
    >
        <div
            id="document-content"
            class="mt-4"
            v-html="previewDocumentContent"
        ></div>
        <div class="flex justify-end gap-4 border-t pt-4">
            <BaseButton @click="printDocument" class="py-2">
                <fonta icon="print" />
                <span>imprimer</span>
            </BaseButton>
            <BaseButton
                @click="downloadPDF"
                bg-color="bg-green-600"
                hover-color="bg-green-700"
                focus-color="ring-green-400"
                active-color="bg-green-800"
                class="py-2"
            >
                <fonta icon="download" />
                <span>Telecharger PDF</span>
            </BaseButton>
        </div>
    </a-modal>
</template>
<script setup>
import BaseButton from "@/Components/BaseButton.vue";
import TestLayout from "@/Layouts/TestLayout.vue";
import html2canvas from "html2canvas";
import jsPDF from "jspdf";
import Swal from "sweetalert2";
import { computed, defineOptions, defineProps, ref } from "vue";
import logo from "../../../../assets/medicare_dark.svg";

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
// information du badge
const badge = computed(() => {
    return props.patient.badge;
});

// Create a computed property for the QR code value
const qrCodeValue = computed(() => {
    return JSON.stringify({
        nom: props.patient.nom,
        prenom: props.patient.prenom,
        numero: props.patient.numero,
        qr_code: badge.value.qr_code,
        // Add any other information you want to include
    });
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
//Pour l'affichage des modals des documents prêts à être imprimé ou telechargé
const previewModalVisible = ref(false);
const previewDocumentContent = ref("");
const previewDocumentTitle = ref("");
const selectedConsultation = ref(null);

/**
 * Contenu HTML pour une ordonnance
 */
const getOrdonnanceContent = (consultation) => {
    const medicaments = consultation.ordonnance?.medicaments || [];
    const medicamentRows = medicaments
        .map(
            (med) => `
        <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 8px; text-align: left;">${med.designation}</td>
            <td style="padding: 8px; text-align: left;">${med.forme}</td>
            <td style="padding: 8px; text-align: left;">${med.posologie}</td>
            <td style="padding: 8px; text-align: center;">${med.quantite}</td>
        </tr>
    `
        )
        .join("");

    return `
    <div style="font-family: Arial, sans-serif; padding: 15px; border: 1px solid #ddd;">
        <div style="text-align: center; margin-bottom: 1rem;">
            <h1 style="font-size: 20px; font-weight: bold;display: flex; align-items: center justify-content: center; margin: 0;"><img src="${logo}" alt="Logo" style="height: 40px; vertical-align: middle; margin-right: 10px;" /><span style="margin-top:8px;">MEDICARE</span></h1>
            <p style="font-size: 13px; margin: 2px 0;">Centre Médical Agréé</p>
            <p style="font-size: 13px; margin: 2px 0;">123 Rue de la Santé, Adresse - Tél: 0000000000</p>
        </div>
        <!-- Date et informations médecin -->
        <div style="text-align: right; font-size: 13px; margin-bottom: 1rem;">
            <p style="margin: 2px 0;">Le ${formatDate(
                new Date(consultation.date_consultation)
            )}</p>
            <div style="margin-top: 0.5rem;">
                <p style="margin: 2px 0; font-weight: bold;">Dr. ${consultation.medecin.nom.toUpperCase()} ${
        consultation.medecin.prenom
    }</p>
                <p style="margin: 2px 0;">Médecin ${consultation.medecin.type.toLowerCase()}</p>
                <p style="margin: 2px 0;">N° RPPS : ________________</p>
            </div>
        </div>
        <h3 style="text-align: center; margin-bottom: 15px; font-weight: bold; font-size: 16px;">Ordonnance</h3>

         <!-- Informations patient -->
        <div style="margin-bottom: 15px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <tr style="background-color: #f3f4f6;">
                    <th colspan="2" style="padding: 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">
                        Informations du Patient
                    </th>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600; width: 150px;">Nom et Prénom</td>
                    <td style="padding: 8px;">${props.patient.nom} ${
        props.patient.prenom
    }</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600;">Âge</td>
                    <td style="padding: 8px;">${calculateAge(
                        props.patient.date_naissance
                    )} ans</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600;">Numéro de dossier</td>
                    <td style="padding: 8px;">${
                        props.patient.numero || "N/A"
                    }</td>
                </tr>
            </table>
        </div>

        <hr style="margin: 15px 0;">

        <h4 style="margin-bottom: 10px; font-size: 14px;">Médicaments prescrits :</h4>

        <div style="max-width: 100%; overflow-x: auto; margin-bottom: 15px; border: 1px solid #e5e7eb; border-radius: 8px;">
            <table style="width: 100%; border-collapse: collapse; white-space: nowrap; min-width: 100%; font-size: 13px;">
                <thead>
                    <tr style="background-color: #f3f4f6;">
                        <th style="padding: 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Désignation</th>
                        <th style="padding: 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Forme</th>
                        <th style="padding: 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Posologie</th>
                        <th style="padding: 8px; text-align: center; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Quantité</th>
                    </tr>
                </thead>
                <tbody>
                    ${medicamentRows}
                </tbody>
            </table>
        </div>

        ${
            consultation.ordonnance.nbr_jours_repos
                ? `
        <div style="margin: 15px 0; padding: 8px; background-color: #f3f4f6; border-radius: 8px; border: 1px solid #e5e7eb; font-size: 13px;">
            <p style="margin: 0;"><strong>Repos prescrit :</strong> ${consultation.ordonnance.nbr_jours_repos} jours</p>
        </div>
        `
                : ""
        }

        <hr style="margin: 15px 0;">

        <!-- Signature -->
            <div style="margin-top: 2rem; text-align: right;">
                <div style="display: inline-block; text-align: center; border-top: 1px solid #666; padding-top: 6px;">
                    <p style="margin: 2px 0;">
                        Signature et cachet du médecin
                    </p>
                    <p style="margin: 2px 0; font-style: italic; font-size: 11px; color: #666;">
                        (Article R.4127-76 du code de la santé publique)
                    </p>
                </div>
            </div>
    </div>
    `;
};

/**
 * Contenu HTML pour une demande d'examen
 */
const getDemandeExamenContent = (consultation) => {
    const examens = JSON.parse(consultation.demande_examen.liste_examens);
    const examensList = examens
        .map(
            (examen) => `
        <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 8px;font-wh: bold; text-align: left;">${examen.toUpperCase()}</td>
        </tr>
    `
        )
        .join("");

    return `
    <div style="font-family: Arial, sans-serif; padding: 15px; border: 1px solid #ddd;">
        <div style="text-align: center; margin-bottom: 1rem;">
            <h1 style="font-size: 20px; font-weight: bold;display: flex; align-items: center justify-content: center; margin: 0;"><img src="${logo}" alt="Logo" style="height: 40px; vertical-align: middle; margin-right: 10px;" /><span style="margin-top:8px;">MEDICARE</span></h1>
            <p style="font-size: 13px; margin: 2px 0;">Centre Médical Agréé</p>
            <p style="font-size: 13px; margin: 2px 0;">123 Rue de la Santé, Adresse - Tél: 0000000000</p>
        </div>
        <!-- Date et informations médecin -->
        <div style="text-align: right; font-size: 13px; margin-bottom: 1rem;">
            <p style="margin: 2px 0;">Le ${formatDate(
                new Date(consultation.date_consultation)
            )}</p>
            <div style="margin-top: 0.5rem;">
                <p style="margin: 2px 0; font-weight: bold;">Dr. ${consultation.medecin.nom.toUpperCase()} ${
        consultation.medecin.prenom
    }</p>
                <p style="margin: 2px 0;">Médecin ${consultation.medecin.type.toLowerCase()}</p>
                <p style="margin: 2px 0;">N° RPPS : ________________</p>
            </div>
        </div>
        <h3 style="text-align: center; margin-bottom: 15px; font-weight: bold; font-size: 16px;">Demande d'Examen</h3>

        <!-- Informations patient -->
        <div style="margin-bottom: 15px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <tr style="background-color: #f3f4f6;">
                    <th colspan="2" style="padding: 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">
                        Informations du Patient
                    </th>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600; width: 150px;">Nom et Prénom</td>
                    <td style="padding: 8px;">${props.patient.nom} ${
        props.patient.prenom
    }</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600;">Âge</td>
                    <td style="padding: 8px;">${calculateAge(
                        props.patient.date_naissance
                    )} ans</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600;">Numéro de dossier</td>
                    <td style="padding: 8px;">${
                        props.patient.numero || "N/A"
                    }</td>
                </tr>
            </table>
        </div>

        <hr style="margin: 15px 0;">

        <h4 style="margin-bottom: 10px; font-size: 14px;">Examens demandés :</h4>
        <div style="max-width: 100%; overflow-x: auto; margin-bottom: 15px; border: 1px solid #e5e7eb; border-radius: 8px;">
            <table style="width: 100%; border-collapse: collapse; min-width: 100%; font-size: 13px;">
                <thead>
                    <tr style="background-color: #f3f4f6;">
                        <th style="padding: 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">
                            Désignation de l'examen
                        </th>
                    </tr>
                </thead>
                <tbody>
                    ${examensList}
                </tbody>
            </table>
        </div>

        ${
            consultation.demande_examen.remarques
                ? `
        <div style="margin: 15px 0; padding: 8px; background-color: #f3f4f6; border-radius: 8px; border: 1px solid #e5e7eb; font-size: 13px;">
            <p style="margin: 0;"><strong>Remarques :</strong></p>
            <p style="margin: 8px 0 0 0; line-height: 1.4;">
                ${consultation.demande_examen.remarques}
            </p>
        </div>
        `
                : ""
        }

        <hr style="margin: 15px 0;">

        <!-- Signature -->
            <div style="margin-top: 2rem; text-align: right;">
                <div style="display: inline-block; text-align: center; border-top: 1px solid #666; padding-top: 6px;">
                    <p style="margin: 2px 0;">
                        Signature et cachet du médecin
                    </p>
                    <p style="margin: 2px 0; font-style: italic; font-size: 11px; color: #666;">
                        (Article R.4127-76 du code de la santé publique)
                    </p>
                </div>
            </div>
    </div>
    `;
};
/**
 * Génère le contenu HTML pour une lettre de référence
 */
const getLettreReferenceContent = (consultation) => {
    return `
    <div style="font-family: Arial, sans-serif; padding: 15px; border: 1px solid #ddd;">
        <!-- En-tête -->
        <div style="text-align: center; margin-bottom: 1rem;">
            <h1 style="font-size: 20px; font-weight: bold;display: flex; align-items: center justify-content: center; margin: 0;"><img src="${logo}" alt="Logo" style="height: 40px; vertical-align: middle; margin-right: 10px;" /><span style="margin-top:8px;">MEDICARE</span></h1>
            <p style="font-size: 13px; margin: 2px 0;">Centre Médical Agréé</p>
            <p style="font-size: 13px; margin: 2px 0;">123 Rue de la Santé, Adresse - Tél: 0000000000</p>
        </div>
        <!-- Date et informations médecin -->
        <div style="text-align: right; font-size: 13px; margin-bottom: 1rem;">
            <p style="margin: 2px 0;">Le ${formatDate(
                new Date(consultation.date_consultation)
            )}</p>
            <div style="margin-top: 0.5rem;">
                <p style="margin: 2px 0; font-weight: bold;">Dr. ${consultation.medecin.nom.toUpperCase()} ${
        consultation.medecin.prenom
    }</p>
                <p style="margin: 2px 0;">Médecin ${consultation.medecin.type.toLowerCase()}</p>
                <p style="margin: 2px 0;">N° RPPS : ________________</p>
            </div>
        </div>
         <h3 style="text-align: center; margin-bottom: 15px; font-weight: bold; font-size: 16px;">Lettre de Référence</h3>
        <!-- Date et lieu -->


        <!-- Informations patient -->
        <div style="margin-bottom: 15px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <tr style="background-color: #f3f4f6;">
                    <th colspan="2" style="padding: 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">
                        Informations du Patient
                    </th>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600; width: 150px;">Nom et Prénom</td>
                    <td style="padding: 8px;">${props.patient.nom} ${
        props.patient.prenom
    }</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600;">Âge</td>
                    <td style="padding: 8px;">${calculateAge(
                        props.patient.date_naissance
                    )} ans</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600;">Numéro de dossier</td>
                    <td style="padding: 8px;">${
                        props.patient.numero || "N/A"
                    }</td>
                </tr>
            </table>
        </div>

        <!-- Destinataire -->
        <div style="margin-bottom: 15px; font-size: 13px;">
            <p style="margin: 3px 0;"><strong>Docteur</strong></p>
            <p style="margin: 3px 0;">${
                consultation.lettre_reference?.refere_med.nom ||
                "_________________"
            }</p>
            <p style="margin: 3px 0;">${
                consultation.lettre_reference?.refere_med.specialite ||
                "_________________"
            }</p>
        </div>

        <!-- Objet et Introduction -->
        <div style="margin-bottom: 15px; font-size: 13px;">
            <p style="margin: 6px 0;"><strong>Objet :</strong> Lettre de référence médicale</p>
            <p style="margin: 6px 0;">Cher Confrère, Chère Consœur,</p>
            <p style="margin: 6px 0;">Je me permets de vous adresser en consultation le patient cité ci-dessus pour les raisons suivantes :</p>
        </div>

        <!-- Informations médicales -->
        <div style="margin-bottom: 15px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <tr style="background-color: #f3f4f6;">
                    <th colspan="2" style="padding: 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">
                        Informations Médicales
                    </th>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600; width: 150px;">Motif de référence</td>
                    <td style="padding: 8px;">${
                        consultation.lettre_reference?.motif_ref ||
                        "Non spécifié"
                    }</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600;">Diagnostic actuel</td>
                    <td style="padding: 8px;">${
                        consultation.diagnostic || "Non spécifié"
                    }</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600;">Antécédents notables</td>
                    <td style="padding: 8px;">${
                        props.patient?.antecedents || "Aucun antécédent notable"
                    }</td>
                </tr>
            </table>
        </div>

        <!-- Conclusion -->
        <div style="margin-bottom: 15px; font-size: 13px; line-height: 1.4;">
            <p style="margin: 6px 0;">Je vous remercie de votre attention et vous prie de me tenir informé de vos conclusions.</p>
            <p style="margin: 6px 0;">Confraternellement,</p>
        </div>

       <!-- Signature -->
            <div style="margin-top: 2rem; text-align: right;">
                <div style="display: inline-block; text-align: center; border-top: 1px solid #666; padding-top: 6px;">
                    <p style="margin: 2px 0;">
                        Signature et cachet du médecin
                    </p>
                    <p style="margin: 2px 0; font-style: italic; font-size: 11px; color: #666;">
                        (Article R.4127-76 du code de la santé publique)
                    </p>
                </div>
            </div>
    </div>
    `;
};
const getDocumentContent = (docType, consultation) => {
    if (docType === "ordonnance") {
        previewDocumentTitle.value = "Ordonnance";
        return getOrdonnanceContent(consultation);
    } else if (docType === "demande_examen") {
        previewDocumentTitle.value = "Demande d'examen";
        return getDemandeExamenContent(consultation);
    } else if (docType === "lettre_reference") {
        previewDocumentTitle.value = "Lettre de référence";
        return getLettreReferenceContent(consultation);
    } else if (docType === "certificat_medical") {
        previewDocumentTitle.value = "Certificat Médical";
        return getCertificatMedicalContent(consultation);
    } else {
        return "";
    }
};

/**
 * Ouvre le modal apperçu du contenu du document
 */
const viewDocument = (docType, consultation) => {
    selectedConsultation.value = consultation;
    previewDocumentContent.value = getDocumentContent(docType, consultation);
    previewModalVisible.value = true;
};

/**
 * Imprime le contenu du document dans une nouvelle fenêtre
 */
const printDocument = () => {
    const printWindow = window.open("", "_blank");
    printWindow.document.write(`
    <html>
      <head>
        <title>${previewDocumentTitle.value}</title>
        <style>
          body { font-family: Arial, sans-serif; margin: 20px; }
          h2 { text-align: center; }
        </style>
      </head>
      <body>${previewDocumentContent.value}</body>
    </html>
  `);
    printWindow.document.close();
    // Attendre que le contenu soit chargé avant d'imprimer
    setTimeout(() => {
        printWindow.focus();
        printWindow.print();
        // Fermer la fenêtre après l'impression
        printWindow.onafterprint = function () {
            printWindow.close();
        };
    }, 200);
};

/**
 * Télécharge le contenu du document en PDF
 */
const downloadPDF = async () => {
    try {
        // Capture le contenu HTML
        const element = document.getElementById("document-content");
        const canvas = await html2canvas(element, {
            scale: 3,
            useCORS: true,
            logging: true,
            backgroundColor: "#FFFFFF",
        });

        // Création du PDF
        const doc = new jsPDF({
            format: "a4",
            unit: "mm",
            hotfixes: ["px_scaling"],
        });

        const imgData = canvas.toDataURL("image/png", 1.0);
        const imgWidth = doc.internal.pageSize.getWidth();
        const imgHeight = (canvas.height * imgWidth) / canvas.width;

        doc.addImage(imgData, "PNG", 0, 0, imgWidth, imgHeight);

        // Métadonnées du PDF
        const patientNumber = props.patient?.numero || "unknown";
        const docType = previewDocumentTitle.value
            .toLowerCase()
            .replace(" ", "_");
        const consultationDate = selectedConsultation.value?.date_consultation
            ? formatDate(selectedConsultation.value.date_consultation)
            : "unknown";
        const filename = `Consultation_${consultationDate}_${docType}_${patientNumber}.pdf`;

        doc.setProperties({
            title: `${previewDocumentTitle.value} - ${selectedConsultation.id}- ${patientNumber}`,
            subject: "Document médical officiel",
            author: "Medicare",
            keywords: "medical,certificat,medecin,ordonnance",
        });

        Swal.fire({
            title: "Telechargé !",
            text: "PDF telecharger avec succès",
            icon: "success",
            confirmButtonColor: "#3b82f6",
        });
        previewModalVisible.value = false;
        selectedConsultation.value = null;

        // Téléchargement
        doc.save(filename);
    } catch (error) {
        console.error("Erreur de génération PDF :", error);
    }
};
</script>

<style >
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
    @apply bg-white rounded-xl border border-gray-200 mb-2 hover:shadow-md !important;
    transition: all 0.3s ease;
}

.custom-collapse .ant-collapse-content {
    @apply border-t-0 rounded-b-xl !important;
}

.ant-collapse > .ant-collapse-item > .ant-collapse-header .ant-collapse-arrow {
    @apply text-gray-400 !important;
}

.qr-code {
    margin-top: 10px; /* Space between title and QR code */
}
</style>
