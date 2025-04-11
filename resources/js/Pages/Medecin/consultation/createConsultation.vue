<template>
    <div class="p-2 rounded-md">
        <!-- En-tête avec recherche de patient -->
        <div
            class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-xl shadow-md transition-shadow duration-300 hover:shadow-lg"
        >
            <div class="flex items-center gap-3 text-white mb-6">
                <h1 class="text-xl font-medium">Nouvelle Consultation</h1>
            </div>

            <div
                class="relative flex items-center justify-center md:flex-row flex-col-reverse mb-2 gap-3"
            >
                <div class="relative w-full flex-1 z-50 group">
                    <div class="relative flex items-center">
                        <span class="absolute left-3 text-blue-200">
                            <fonta
                                icon="search"
                                class="transition-transform duration-300 group-hover:scale-110"
                            />
                        </span>
                        <BaseInput
                            type="text"
                            v-model="patientSearchTerm"
                            placeholder="Rechercher un patient par nom, prénom ou numéro..."
                            @focus="dropdownVisible = true"
                            @blur="dropdownVisible = false"
                            class="pl-10 text-md bg-white/95 border-blue-300 hover:bg-white focus:bg-white transition-all duration-300"
                        />
                    </div>

                    <transition
                        name="dropdown"
                        enter-active-class="transition-all duration-200 ease-out"
                        leave-active-class="transition-all duration-150 ease-in"
                        enter-from-class="opacity-0 -translate-y-2"
                        leave-to-class="opacity-0 -translate-y-2"
                    >
                        <div
                            v-if="
                                dropdownVisible && filteredPatients.length > 0
                            "
                            class="absolute w-full bg-white shadow-lg p-2 flex flex-col gap-1 rounded-md mt-2 max-h-64 overflow-y-auto custom-scroll border border-blue-100/60"
                        >
                            <div
                                v-for="(patient, index) in filteredPatients"
                                :key="patient.id"
                                @click="selectPatient(patient)"
                                class="px-4 py-3 hover:bg-blue-50/70 rounded-md cursor-pointer hover:shadow-sm transition-all duration-300"
                                :style="{ transitionDelay: `${index * 30}ms` }"
                            >
                                <div class="flex items-center gap-2">
                                    <div
                                        class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center transition-all duration-300 hover:bg-blue-200 shadow-sm"
                                    >
                                        <span
                                            class="text-blue-500 font-medium text-sm"
                                            >{{ patient.initials }}</span
                                        >
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-600">
                                            {{ patient.nom }}
                                            {{ patient.prenom }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            {{ patient.numero }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </transition>

                    <transition
                        name="fade"
                        enter-active-class="transition-opacity duration-200 ease-out"
                        leave-active-class="transition-opacity duration-150 ease-in"
                    >
                        <div
                            v-if="
                                dropdownVisible && filteredPatients.length === 0
                            "
                            class="absolute z-50 w-full bg-white shadow-lg p-4 rounded-md mt-2 border border-blue-100/60"
                        >
                            <div
                                class="text-gray-500 text-center py-4 animate-pulse"
                            >
                                <fonta
                                    icon="magnifying-glass"
                                    class="text-blue-200 text-3xl mb-3"
                                />
                                <p class="text-blue-400">
                                    Veuillez rechercher un patient
                                </p>
                            </div>
                        </div>
                    </transition>
                </div>

                <BaseButton
                    @click="handleAdd"
                    class="py-2 transition-transform duration-500 hover:scale-105"
                    bg-color="bg-green-600"
                    hover-color="bg-green-700"
                    focus-color="ring-green-400"
                    active-color="bg-green-800"
                >
                    <span class="flex items-center"
                        >Démarrer la consultation</span
                    >
                </BaseButton>
            </div>
        </div>

        <!-- Section des 4 dernières consultations -->
        <div
            class="bg-white rounded-b-xl px-6 py-8 shadow-md transition-all duration-300 hover:shadow-lg"
        >
            <div class="flex items-center justify-between mb-6">
                <h2
                    class="text-lg text-blue-500 font-medium flex items-center gap-2"
                >
                    <fonta icon="history" class="text-blue-500" />
                    Consultations récentes
                </h2>

                <span
                    class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full shadow-inner"
                >
                    Les 4 dernières consultations
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <Link
                    v-for="consultation in consultations"
                    :key="consultation.id"
                    :href="
                        route(
                            'patient.dossier.details',
                            consultation.patient.id
                        )
                    "
                    class="group"
                >
                    <div
                        class="bg-blue-50/70 rounded-xl p-5 border border-blue-100/60 hover:bg-blue-100/50 hover:shadow-md hover:border-blue-300/60 cursor-pointer flex items-start gap-3 transform transition-all duration-500 hover:-translate-y-0.5"
                    >
                        <div
                            class="bg-blue-100 p-3 rounded-full group-hover:bg-blue-200/70 transition-all duration-300 transform group-hover:scale-110"
                        >
                            <fonta icon="user" class="text-blue-500 text-lg" />
                        </div>

                        <div class="space-y-2">
                            <!-- Date -->
                            <div
                                class="text-base font-medium text-blue-500 group-hover:text-blue-600 transition-colors duration-300"
                            >
                                {{
                                    new Date(
                                        consultation.date_consultation
                                    ).toLocaleDateString("fr-FR", {
                                        weekday: "short",
                                        day: "numeric",
                                        month: "short",
                                    })
                                }}
                            </div>

                            <!-- Numéro patient -->
                            <div class="flex items-center gap-2">
                                <fonta
                                    icon="id-card"
                                    class="text-blue-400 text-xs group-hover:text-blue-500 transition-colors duration-300"
                                />
                                <span
                                    class="text-xs font-semibold text-gray-600 group-hover:text-gray-700 transition-colors duration-300"
                                >
                                    {{ consultation.patient.numero }}
                                </span>
                            </div>
                        </div>
                    </div>
                </Link>

                <div
                    v-if="consultations.length === 0"
                    class="col-span-full bg-gray-50/70 p-8 rounded-xl border border-gray-200/60 flex flex-col items-center justify-center transition-all duration-300 hover:bg-gray-100/50"
                >
                    <a-empty :description="null" />

                    <p class="text-gray-500 font-medium">
                        Aucune consultation enregistrée
                    </p>
                    <p class="text-gray-400 text-sm mt-1">
                        Les consultations récentes apparaîtront ici
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de creation de consultations, ordonnance , lettre de reference, demande d'examens -->
    <transition name="custom" mode="out-in">
        <div
            v-if="showConsultation"
            :key="addedPatient.id"
            class="card-container consultation-section py-4 my-6 rounded-md shadow-md bg-white transition-all duration-500"
        >
            <form @submit.prevent="submitConsultation">
                <div
                    class="flex items-center justify-between md:flex-row flex-col gap-4 p-8"
                >
                    <div>
                        <span
                            class="text-md bg-gradient-to-br from-blue-100 to-blue-200 text-blue-800 px-4 py-2 rounded-lg font-medium flex items-center gap-2 shadow-sm hover:shadow-md transition-all duration-300 hover:from-blue-200 hover:to-blue-300"
                        >
                            <fonta icon="id-card" class="text-blue-500" />
                            {{ addedPatient.numero }}
                        </span>
                    </div>

                    <div class="flex items-center justify-center">
                        <BaseButton
                            type="submit"
                            class="py-2  transition-transform duration-500 hover:scale-105"
                            bg-color="bg-green-600"
                            hover-color="bg-green-700"
                            focus-color="ring-green-400"
                            active-color="bg-green-800"
                        >
                            <fonta class="text-lg" icon="floppy-disk" />
                            <span>Enregistrer la consultation</span>
                        </BaseButton>
                    </div>
                </div>

                <a-tabs v-model:activeKey="activeKey" type="card">
                    <a-tab-pane key="1">
                        <template #tab>
                            <span class="flex items-center gap-2">
                                <fonta icon="file-waveform" class="text-lg" />
                                Fiche de Consultation
                            </span>
                        </template>
                        <div
                            class="mt-4 mx-4 border-2 border-gray-200/60 rounded-md p-4 transition-all duration-300 hover:border-blue-200/60 hover:shadow-sm"
                        >
                            <div
                                class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4"
                            >
                                <InputLabel
                                    value="Nom Complet"
                                    class="col-span-1"
                                />
                                <div
                                    class="flex w-full items-center justify-start bg-gray-50/70 col-span-3 py-1 px-4 border-gray-300/70 border rounded-lg shadow-inner transition-all duration-300 hover:bg-blue-50/30"
                                >
                                    <span
                                        class="font-semibold text-lg text-gray-600 flex items-center gap-2"
                                    >
                                        {{ addedPatient.nom }}
                                        {{ addedPatient.prenom }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <InputLabel value="Age" class="col-span-1" />
                                <div
                                    class="w-full bg-gray-50/70 md:col-span-1 col-span-2 py-1 px-4 border-gray-300/70 border rounded-lg shadow-inner flex items-center gap-2 transition-all duration-300 hover:bg-blue-50/30"
                                >
                                    <span
                                        class="font-semibold text-lg text-gray-600"
                                        >{{ patientAge }} ans</span
                                    >
                                </div>

                                <div class="col-span-1 text-base font-semibold">
                                    <h1>Date :</h1>
                                </div>
                                <div
                                    class="flex items-center gap-2 md:col-span-1 w-full border-gray-300/70 border text-gray-600 bg-gray-50/70 px-4 py-1 rounded-lg text-base font-semibold shadow-inner transition-all duration-300 hover:bg-blue-50/30"
                                >
                                    <span>{{
                                        new Date().toLocaleDateString()
                                    }}</span>
                                </div>
                            </div>
                        </div>
                        <div
                            class="mt-4 mx-4 border-2 border-gray-200/60 rounded-md p-6 transition-all duration-300 hover:shadow hover:border-blue-200/60"
                        >
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Section Gauche - Informations Générales -->
                                <div class="space-y-6">
                                    <h3
                                        class="text-lg font-semibold text-blue-500 mb-4 pb-2 border-b border-blue-100/70 flex items-center gap-2"
                                    >
                                        <fonta
                                            icon="clipboard-list"
                                            class="text-blue-500"
                                        />
                                        Informations Générales
                                    </h3>

                                    <div>
                                        <InputLabel
                                            for="type de consultation"
                                            value="Type de consultation"
                                            class="mb-2 text-gray-700"
                                        />
                                        <a-segmented
                                            v-model:value="form.type"
                                            :options="options_type"
                                            block
                                            class="custom-segmented"
                                        />
                                    </div>

                                    <div>
                                        <InputLabel
                                            value="Motif de consultation"
                                            class="mb-2 text-gray-700 flex items-center gap-2"
                                        >
                                            <fonta
                                                icon="circle-info"
                                                class="text-blue-500"
                                            />
                                            Motif de consultation
                                        </InputLabel>
                                        <textarea
                                            v-model="form.motif"
                                            required
                                            class="mt-1 block w-full p-3 border rounded-lg border-gray-300/70 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 shadow-inner bg-white transition-all duration-300 hover:border-blue-300/70"
                                            placeholder="Décrire le motif de la consultation..."
                                        ></textarea>
                                    </div>

                                    <div>
                                        <InputLabel
                                            value="Diagnostique"
                                            class="mb-2 text-gray-700 flex items-center gap-2"
                                        >
                                            <fonta
                                                icon="stethoscope"
                                                class="text-blue-500"
                                            />
                                            Diagnostique
                                        </InputLabel>
                                        <textarea
                                            v-model="form.diagnostic"
                                            class="mt-1 block w-full p-3 border rounded-lg border-gray-300/70 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 shadow-inner bg-white transition-all duration-300 hover:border-blue-300/70"
                                            placeholder="Rédiger le diagnostique..."
                                            required
                                        ></textarea>
                                    </div>
                                </div>

                                <!-- Section Droite - Constantes Vitales -->
                                <div class="space-y-6">
                                    <h3
                                        class="text-lg font-semibold text-blue-500 mb-4 pb-2 border-b border-blue-100/70 flex items-center gap-2"
                                    >
                                        <fonta
                                            icon="heart-pulse"
                                            class="text-blue-500 animate-pulse"
                                        />
                                        Constantes Vitales
                                    </h3>

                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <InputLabel
                                                value="Poids (kg)"
                                                class="mb-2 text-gray-700 flex items-center gap-2"
                                            >
                                                Poids (kg)
                                            </InputLabel>

                                            <BaseInput
                                                type="number"
                                                v-model.number="form.poids"
                                                required
                                                class="bg-white transition-all duration-300 hover:border-blue-300"
                                            />
                                        </div>

                                        <div>
                                            <InputLabel
                                                value="Taille (cm)"
                                                class="mb-2 text-gray-700 flex items-center gap-2"
                                            >
                                                Taille (cm)
                                            </InputLabel>
                                            <BaseInput
                                                type="number"
                                                v-model.number="form.taille"
                                                min="0"
                                                required
                                                class="bg-white transition-all duration-300 hover:border-blue-300"
                                            />
                                        </div>

                                        <div>
                                            <InputLabel
                                                value="Fréquence cardiaque (bpm)"
                                                class="mb-2 text-gray-700 flex items-center gap-2"
                                            >
                                                Fréquence cardiaque
                                            </InputLabel>
                                            <BaseInput
                                                type="number"
                                                v-model.number="form.freq_card"
                                                class="bg-white transition-all duration-300 hover:border-blue-300"
                                                min="0"
                                                required
                                            />
                                        </div>

                                        <div>
                                            <InputLabel
                                                value="Température (°C)"
                                                class="mb-2 text-gray-700 flex items-center gap-2"
                                            >
                                                Température (°C)
                                            </InputLabel>
                                            <BaseInput
                                                type="number"
                                                v-model.number="
                                                    form.temperature
                                                "
                                                min="0"
                                                required
                                                class="bg-white transition-all duration-300 hover:border-blue-300"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a-tab-pane>
                    <a-tab-pane key="2">
                        <template #tab>
                            <span class="flex items-center gap-2">
                                <fonta icon="capsules" class="text-lg" />
                                Rédiger une ordonnance
                            </span>
                        </template>
                        <div
                            class="mt-4 mx-4 border-2 border-gray-200/60 rounded-md p-4"
                        >
                            <div
                                class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4"
                            >
                                <InputLabel
                                    value="Nom Complet"
                                    class="col-span-1"
                                />
                                <div
                                    class="flex w-full items-center justify-start bg-gray-50/70 col-span-3 py-1 px-4 border-gray-300/70 border rounded-lg shadow-inner"
                                >
                                    <span
                                        class="font-semibold text-lg text-gray-600 flex items-center gap-2"
                                    >
                                        {{ addedPatient.nom }}
                                        {{ addedPatient.prenom }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <InputLabel value="Age" class="col-span-1" />
                                <div
                                    class="w-full bg-gray-50/70 md:col-span-1 col-span-2 py-1 px-4 border-gray-300/70 border rounded-lg shadow-inner flex items-center gap-2"
                                >
                                    <span
                                        class="font-semibold text-lg text-gray-600"
                                        >{{ patientAge }} ans</span
                                    >
                                </div>

                                <div class="col-span-1 text-base font-semibold">
                                    <h1>Date :</h1>
                                </div>
                                <div
                                    class="flex items-center gap-2 md:col-span-1 w-full border-gray-300/70 border text-gray-600 bg-gray-50/70 px-4 py-1 rounded-lg text-base font-semibold shadow-inner"
                                >
                                    <span>{{
                                        new Date().toLocaleDateString()
                                    }}</span>
                                </div>
                            </div>
                        </div>
                        <!-- medicament -->
                        <div
                            class="max-w-full mx-4 mt-4 p-6 space-y-6 bg-white rounded-md border-2 border-gray-200/60 shadow-sm"
                        >
                            <!-- En-tête du tableau -->
                            <div
                                class="font-semibold text-lg text-blue-500 flex items-center gap-2 pb-4 mb-2 border-b border-blue-100/70"
                            >
                                <fonta icon="capsules" class="text-blue-500" />
                                <h1>Médicaments prescrits</h1>
                            </div>
                            <div
                                class="grid grid-cols-4 text-base gap-4 font-medium text-gray-700 bg-gray-50/70 py-3 px-2 rounded-lg"
                            >
                                <div class="flex items-center gap-2">
                                    <span>Désignation</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span>Forme</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span>Posologie</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span>Quantité</span>
                                </div>
                            </div>

                            <!-- Lignes des médicaments -->
                            <div
                                v-for="(med, index) in form.medicament"
                                :key="index"
                                class="grid grid-cols-4 gap-4 items-center group py-3 px-2 border-b border-gray-100/70"
                            >
                                <div>
                                    <BaseInput
                                        v-model="med.designation"
                                        placeholder="Paracétamol..."
                                        class="bg-white"
                                    />
                                </div>

                                <div>
                                    <BaseInput
                                        v-model="med.forme"
                                        placeholder="Comprimé..."
                                        class="bg-white"
                                    />
                                </div>

                                <div>
                                    <BaseInput
                                        v-model="med.posologie"
                                        placeholder="1 comprimé 3x/jour..."
                                        class="bg-white"
                                    />
                                </div>

                                <div class="flex items-center gap-4">
                                    <BaseInput
                                        v-model="med.quantite"
                                        type="number"
                                        placeholder="10"
                                        class="bg-white"
                                    />
                                    <button
                                        type="button"
                                        @click="removeMed(index)"
                                        class="text-red-500 hover:text-red-700 bg-red-50 p-2 rounded-full"
                                    >
                                        <fonta icon="trash" class="text-lg" />
                                    </button>
                                </div>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="py-6 space-x-4">
                                <BaseButton
                                    type="button"
                                    @click="addMed"
                                    class="py-2 px-4 flex items-center gap-2"
                                    bg-color="bg-blue-500"
                                    hover-color="bg-blue-600"
                                >
                                    <fonta icon="plus-circle" />
                                    Ajouter médicament
                                </BaseButton>
                            </div>
                        </div>
                    </a-tab-pane>
                    <a-tab-pane key="3">
                        <template #tab>
                            <span class="flex items-center gap-2">
                                <fonta icon="bed" class="text-lg" />
                                Prescrire des jours de repos
                            </span>
                        </template>
                        <div
                            class="mt-4 mx-4 border-2 border-gray-200/60 rounded-md p-4"
                        >
                            <div
                                class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4"
                            >
                                <InputLabel
                                    value="Nom Complet"
                                    class="col-span-1"
                                />
                                <div
                                    class="flex w-full items-center justify-start bg-gray-50/70 col-span-3 py-1 px-4 border-gray-300/70 border rounded-lg shadow-inner"
                                >
                                    <span
                                        class="font-semibold text-lg text-gray-600 flex items-center gap-2"
                                    >
                                        {{ addedPatient.nom }}
                                        {{ addedPatient.prenom }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <InputLabel value="Age" class="col-span-1" />
                                <div
                                    class="w-full bg-gray-50/70 md:col-span-1 col-span-2 py-1 px-4 border-gray-300/70 border rounded-lg shadow-inner flex items-center gap-2"
                                >
                                    <span
                                        class="font-semibold text-lg text-gray-600"
                                        >{{ patientAge }} ans</span
                                    >
                                </div>

                                <div class="col-span-1 text-base font-semibold">
                                    <h1>Date :</h1>
                                </div>
                                <div
                                    class="flex items-center gap-2 md:col-span-1 w-full border-gray-300/70 border text-gray-600 bg-gray-50/70 px-4 py-1 rounded-lg text-base font-semibold shadow-inner"
                                >
                                    <span>{{
                                        new Date().toLocaleDateString()
                                    }}</span>
                                </div>
                            </div>
                        </div>
                        <!-- Conteneur input -->
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4 mx-4 border-2 border-gray-200/60 rounded-md p-6"
                        >
                            <div
                                class="font-semibold text-lg text-blue-500 flex items-center gap-2 pb-4 mb-2 col-span-full border-b border-blue-100/70"
                            >
                                <fonta icon="bed-pulse" class="text-blue-500" />
                                <h1>Jour de repos</h1>
                            </div>

                            <!-- Date de reprise -->
                            <div class="space-y-2">
                                <InputLabel
                                    value="Date de reprise"
                                    class="mb-2 text-gray-700 flex items-center gap-2"
                                >
                                    <fonta
                                        icon="calendar-check"
                                        class="text-blue-500"
                                    />
                                    Date de reprise
                                </InputLabel>
                                <BaseInput
                                    type="date"
                                    v-model="returnDate"
                                    :min="minDate"
                                    @change="calculateRestDays"
                                    class="bg-white"
                                />
                            </div>

                            <!-- Jours de repos calculés , affecter à form.nbr_jours_repos lors de la soumission -->
                            <div class="space-y-2">
                                <InputLabel
                                    value="Jours de repos prescrits"
                                    class="mb-2 text-gray-700 flex items-center gap-2"
                                >
                                    <fonta
                                        icon="hourglass-half"
                                        class="text-blue-500"
                                    />
                                    Jours de repos prescrits
                                </InputLabel>
                                <div class="relative">
                                    <BaseInput
                                        type="number"
                                        :value="restDays"
                                        readonly
                                        class="bg-blue-50 font-medium text-blue-700"
                                    />
                                </div>
                            </div>

                            <!-- Message d'information -->
                            <div class="col-span-full mt-2">
                                <div
                                    v-if="restDays > 0"
                                    class="p-4 bg-green-50 rounded-lg border border-green-200 text-green-700 flex items-start gap-2"
                                >
                                    <fonta
                                        icon="circle-info"
                                        class="text-green-500 mt-1"
                                    />
                                    <span>
                                        Le patient doit se reposer jusqu'au
                                        <strong>{{
                                            formattedReturnDate
                                        }}</strong>
                                    </span>
                                </div>
                                <div
                                    v-else
                                    class="p-4 bg-blue-50 rounded-lg border border-blue-100 text-gray-600 flex items-start gap-2"
                                >
                                    <fonta
                                        icon="circle-info"
                                        class="text-blue-500 mt-1"
                                    />
                                    <span>
                                        Sélectionnez une date de reprise future
                                        pour calculer les jours de repos
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a-tab-pane>
                    <a-tab-pane key="4">
                        <template #tab>
                            <span class="flex items-center gap-2">
                                <fonta
                                    icon="vial-circle-check"
                                    class="text-lg"
                                />
                                Demande d'examen
                            </span>
                        </template>
                        <div
                            class="mt-4 mx-4 border-2 border-gray-200/60 rounded-md p-4"
                        >
                            <div
                                class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4"
                            >
                                <InputLabel
                                    value="Nom Complet"
                                    class="col-span-1"
                                />
                                <div
                                    class="flex w-full items-center justify-start bg-gray-50/70 col-span-3 py-1 px-4 border-gray-300/70 border rounded-lg shadow-inner"
                                >
                                    <span
                                        class="font-semibold text-lg text-gray-600 flex items-center gap-2"
                                    >
                                        {{ addedPatient.nom }}
                                        {{ addedPatient.prenom }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <InputLabel value="Age" class="col-span-1" />
                                <div
                                    class="w-full bg-gray-50/70 md:col-span-1 col-span-2 py-1 px-4 border-gray-300/70 border rounded-lg shadow-inner flex items-center gap-2"
                                >
                                    <span
                                        class="font-semibold text-lg text-gray-600"
                                        >{{ patientAge }} ans</span
                                    >
                                </div>

                                <div class="col-span-1 text-base font-semibold">
                                    <h1>Date :</h1>
                                </div>
                                <div
                                    class="flex items-center gap-2 md:col-span-1 w-full border-gray-300/70 border text-gray-600 bg-gray-50/70 px-4 py-1 rounded-lg text-base font-semibold shadow-inner"
                                >
                                    <span>{{
                                        new Date().toLocaleDateString()
                                    }}</span>
                                </div>
                            </div>
                        </div>
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4 mx-4 border-2 border-gray-200/60 rounded-md p-6"
                        >
                            <div
                                class="font-semibold text-lg text-blue-600 flex items-center gap-2 pb-4 mb-2 col-span-full border-b border-blue-100/70"
                            >
                                <fonta
                                    icon="vial-circle-check"
                                    class="text-blue-500"
                                />
                                <h1>Sélection des examens à réaliser</h1>
                            </div>

                            <!-- Sélection des examens -->
                            <div class="col-span-full">
                                <InputLabel
                                    value="Examens à réaliser"
                                    class="mb-2 text-gray-700 flex items-center gap-2"
                                >
                                    <fonta
                                        icon="microscope"
                                        class="text-blue-500"
                                    />
                                    Examens à réaliser
                                </InputLabel>
                                <div
                                    class="shadow-inner bg-white rounded-lg border border-gray-300/70"
                                >
                                    <a-select
                                        v-model:value="form.liste_examens"
                                        mode="tags"
                                        placeholder="Ajouter des examens à faire..."
                                        :options="options"
                                        class="!w-full custom-select"
                                    >
                                    </a-select>
                                </div>
                            </div>

                            <!-- Section remarques -->
                            <div class="col-span-full mt-4">
                                <InputLabel
                                    value="Remarques"
                                    class="mb-2 text-gray-700 flex items-center gap-2"
                                >
                                    <fonta
                                        icon="circle-info"
                                        class="text-blue-500"
                                    />
                                    Remarques sur les examens
                                </InputLabel>

                                <textarea
                                    v-model="form.remarques"
                                    class="mt-1 block w-full p-2 border rounded-lg border-gray-300/70 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 shadow-inner"
                                    rows="4"
                                    placeholder="Ajouter des remarques concernant les examens..."
                                ></textarea>
                            </div>
                        </div>
                    </a-tab-pane>

                    <a-tab-pane key="5" class="last-tab">
                        <template #tab>
                            <span class="flex items-center gap-2">
                                <fonta icon="envelope" class="text-lg" />
                                Lettre de Reference
                            </span>
                        </template>
                        <div
                            class="mt-4 mx-4 border-2 border-gray-200/60 rounded-md p-4"
                        >
                            <div
                                class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4"
                            >
                                <InputLabel
                                    value="Nom Complet"
                                    class="col-span-1"
                                />
                                <div
                                    class="flex w-full items-center justify-start bg-gray-50/70 col-span-3 py-1 px-4 border-gray-300/70 border rounded-lg shadow-inner"
                                >
                                    <span
                                        class="font-semibold text-lg text-gray-600 flex items-center gap-2"
                                    >
                                        {{ addedPatient.nom }}
                                        {{ addedPatient.prenom }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <InputLabel value="Age" class="col-span-1" />
                                <div
                                    class="w-full bg-gray-50/70 md:col-span-1 col-span-2 py-1 px-4 border-gray-300/70 border rounded-lg shadow-inner flex items-center gap-2"
                                >
                                    <span
                                        class="font-semibold text-lg text-gray-600"
                                        >{{ patientAge }} ans</span
                                    >
                                </div>

                                <div class="col-span-1 text-base font-semibold">
                                    <h1>Date :</h1>
                                </div>
                                <div
                                    class="flex items-center gap-2 md:col-span-1 w-full border-gray-300/70 border text-gray-600 bg-gray-50/70 px-4 py-1 rounded-lg text-base font-semibold shadow-inner"
                                >
                                    <span>{{
                                        new Date().toLocaleDateString()
                                    }}</span>
                                </div>
                            </div>
                        </div>
                        <div
                            class="mt-4 mx-4 border-2 border-gray-200/60 rounded-md p-6"
                        >
                            <div
                                class="font-semibold text-lg text-blue-500 flex items-center gap-2 pb-4 mb-2 border-b border-blue-100/70"
                            >
                                <fonta
                                    icon="envelope-circle-check"
                                    class="text-blue-500"
                                />
                                <h1>Lettre de référence</h1>
                            </div>

                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4"
                            >
                                <div class="space-y-2">
                                    <InputLabel
                                        value="Médecin référent"
                                        class="mb-2 text-gray-700 flex items-center gap-2"
                                    >
                                        <fonta
                                            icon="user-doctor"
                                            class="text-blue-500"
                                        />
                                        Médecin référent
                                    </InputLabel>

                                    <a-select
                                        v-model="form.refere_med_id"
                                        placeholder="Sélectionnez un médecin"
                                        class="w-full custom-antd-select"
                                        @change="onMedecinChange"
                                    >
                                        <a-select-option
                                            v-for="medecin in props.medecins"
                                            :key="medecin.id"
                                            :value="medecin.id"
                                            class="antd-custom-option"
                                        >
                                            <div
                                                class="flex items-center gap-3 hover:bg-blue-50 rounded-md"
                                            >
                                                <div>
                                                    <p
                                                        class="font-medium text-gray-800"
                                                    >
                                                        {{ medecin.nom }}
                                                        {{ medecin.prenom }}
                                                    </p>
                                                    <p
                                                        class="text-xs text-gray-500"
                                                    >
                                                        {{ medecin.specialite }}
                                                    </p>
                                                </div>
                                            </div>
                                        </a-select-option>
                                    </a-select>
                                </div>

                                <div class="space-y-2">
                                    <InputLabel
                                        value="Motif de référence"
                                        class="mb-2 text-gray-700 flex items-center gap-2"
                                    >
                                        <fonta
                                            icon="circle-info"
                                            class="text-blue-500"
                                        />
                                        Motif de référence
                                    </InputLabel>
                                    <textarea
                                        v-model="form.motif_ref"
                                        class="w-full border-gray-300 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 rounded-lg shadow-inner"
                                        rows="5"
                                        placeholder="Décrire le motif de la référence..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                    </a-tab-pane>
                </a-tabs>
            </form>
        </div>
    </transition>
</template>

<script setup>
import BaseButton from "@/Components/BaseButton.vue";
import BaseInput from "@/Components/BaseInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TestLayout from "@/Layouts/TestLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { message } from "ant-design-vue";
import axios from "axios";
import Swal from "sweetalert2";
import { computed, nextTick, ref, watch } from "vue";

const activeKey = ref("1");
defineOptions({
    layout: TestLayout,
});

const props = defineProps({
    patients: {
        type: Array,
        required: true,
        default: () => [],
    },
    medecins: {
        type: Array,
        required: true,
        default: () => [],
    },
    consultations: {
        type: Array,
        required: true,
        default: () => [],
    },
});
//Input de recherche du patient
const patientSearchTerm = ref("");
const dropdownVisible = ref(false);
const selectedPatient = ref(null);
const showConsultation = ref(false);
const addedPatient = ref(null);
//Pour le Input de Recherche et Ajout patient
const filteredPatients = ref([]);
const searchPatients = async () => {
    if (patientSearchTerm.value.length < 3) {
        filteredPatients.value = [];
        return;
    }

    try {
        const response = await axios.get(route("patients.search"), {
            params: { search: patientSearchTerm.value },
        });
        filteredPatients.value = response.data;
    } catch (error) {
        console.error("Error fetching patients:", error);
    }
};
watch(patientSearchTerm, searchPatients);

const selectPatient = (patient) => {
    form.reset();
    selectedPatient.value = patient;
    patientSearchTerm.value = `${patient.nom} ${patient.prenom} ${patient.numero}`;
    dropdownVisible.value = false;
};

//les 5 derniers consultation

//Vers le formulaire de consultation (consultation,ordonnance,repos,examen,lettre de reference)
const handleAdd = () => {
    if (selectedPatient.value) {
        addedPatient.value = null;
        showConsultation.value = false;
        setTimeout(() => {
            addedPatient.value = selectedPatient.value;
            form.patient_id = selectedPatient.value.id;
            showConsultation.value = true;
            // Scroll vers la section de consultation
            nextTick(() => {
                const element = document.querySelector(".consultation-section");
                element?.scrollIntoView({ behavior: "smooth", block: "start" });
                window.scrollBy({ top: -100, behavior: "smooth" });
            });
        }, 0);
    }
};

//retourner l'age du patient
const patientAge = computed(() => {
    if (!addedPatient.value?.date_naissance) return "";

    const birthDate = new Date(addedPatient.value.date_naissance);
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
});
//form pour envoyer les données pour la consultation,ordonnance , examen, restDays, Lettre
const form = useForm({
    //consultation
    date: new Date().toISOString().slice(0, 16),
    patient_id: null,
    type: "maladie",
    motif: "",
    diagnostic: "",
    //patient
    poids: "",
    taille: "",
    freq_card: "",
    temperature: "",
    observations: "",
    //Ordonnance
    medicament: [
        {
            designation: "",
            forme: "",
            posologie: "",
            quantite: 1,
        },
    ],
    nbr_jours_repos: null,
    //Examen Complementaire
    liste_examens: [],
    remarques: "",
    //Lettre de reference
    refere_med_id: null,
    motif_ref: "",
});

const options_type = ref([
    {
        value: "maladie",
        label: "Maladie",
        // Optionnel : personnalisation de style
        class: "text-blue-800",
    },
    {
        value: "visite_aptitude",
        label: "Visite d'Aptitude",
        // Optionnel : personnalisation de style
        class: "text-green-800",
    },
]);
//ordonnance
const addMed = () => {
    form.medicament.push({
        designation: "",
        forme: "",
        posologie: "",
        quantite: null,
    });
};
const removeMed = (index) => {
    form.medicament.splice(index, 1);
};
//Jour de repos
const returnDate = ref("");
const minDate = ref(new Date().toISOString().split("T")[0]); // Date du jour
const restDays = computed(() => {
    if (!returnDate.value) return 0;
    const start = new Date();
    const end = new Date(returnDate.value);
    const diff = end.getTime() - start.getTime();
    return Math.ceil(diff / (1000 * 3600 * 24)); // Arrondi au jour supérieur
});

const calculateRestDays = () => {
    if (returnDate.value) {
        form.nbr_jours_repos = restDays.value;
    } else {
        form.nbr_jours_repos = null;
    }
};

const formattedReturnDate = computed(() => {
    return new Date(returnDate.value).toLocaleDateString("fr-FR", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
});
const value = ref([]);
const options = [
    {
        label: "Examens Sanguins",
        options: [
            { value: "nfs", label: "Numération Formule Sanguine (NFS)" },
            { value: "glycemie", label: "Glycémie" },
            { value: "cholesterol", label: "Bilan lipidique (Cholestérol)" },
            { value: "creatinine", label: "Créatinine" },
            { value: "tsh", label: "TSH (Thyroïde)" },
        ],
    },
    {
        label: "Imagerie Médicale",
        options: [
            { value: "radio_thorax", label: "Radiographie du thorax" },
            { value: "radio_abdomen", label: "Radiographie abdominale" },
            { value: "echographie", label: "Échographie" },
            { value: "scanner", label: "Scanner (TDM)" },
            { value: "irm", label: "IRM" },
        ],
    },
    {
        label: "Examens Cardiaques",
        options: [
            { value: "ecg", label: "Électrocardiogramme (ECG)" },
            { value: "holter", label: "Holter cardiaque" },
            { value: "echo_coeur", label: "Échographie cardiaque" },
        ],
    },
    {
        label: "Autres Examens",
        options: [
            { value: "endoscopie", label: "Endoscopie" },
            { value: "spirometrie", label: "Spirométrie" },
            { value: "emg", label: "Électromyogramme (EMG)" },
        ],
    },
];
// id medecin referencie
const onMedecinChange = (value) => {
    form.refere_med_id = value;
};
//Soumettre la formulaire complète
const submitConsultation = () => {
    // Filtrer les médicaments pour ne garder que ceux qui ont des valeurs valides
    form.medicament = form.medicament.filter(
        (med) =>
            med.designation && med.forme && med.posologie && med.quantite > 0
    );

    // Intégrer les jours de repos
    form.nbr_jours_repos = restDays.value;

    form.patient_id = addedPatient.value.id;
    form.post(route("consultations.store"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            addedPatient.value = null;
            showConsultation.value = false;
            Swal.fire({
                title: "Succès!",
                text: "Consultation enregistrée avec succès!",
                icon: "success",
                confirmButtonText: "OK",
                confirmButtonColor: "#3085d6",
                timer: 3000,
            });
        },

        onError: (e) => {
            message.error({
                content:
                    "Erreur lors de l'enregistrement de la consultation. Veuillez réessayer.",
                duration: 3,
                style: {
                    marginTop: "20px",
                },
            });
        },
    });
};
</script>
<style>
/* Animation personnalisée pour le dropdown */
.dropdown-enter-active {
    animation: slideIn 0.25s ease-out;
}

.dropdown-leave-active {
    animation: slideOut 0.2s ease-in;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideOut {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-15px);
    }
}

/* Pour l'affichage du formulaire consultation */
@keyframes formIn {
    from {
        transform: translateY(20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes formOut {
    from {
        transform: translateY(0);
        opacity: 1;
    }
    to {
        transform: translateY(20px);
        opacity: 0;
    }
}
.custom-enter-active {
    animation: formIn 0.5s ease-out;
}

.custom-leave-active {
    animation: formOut 2ms ease-in;
}

/* Barre de défilement stylisée */
.custom-scroll::-webkit-scrollbar {
    width: 6px;
}

.custom-scroll::-webkit-scrollbar-track {
    @apply bg-gray-100 rounded-full;
}

.custom-scroll::-webkit-scrollbar-thumb {
    @apply bg-blue-500 rounded-full hover:bg-blue-500 transition-colors duration-300 !shadow-md;
}

/* Animation d'entrée des éléments de la liste */
.list-enter-active {
    transition: all 2s ease;
    position: relative;
}

.list-enter-from {
    opacity: 0;
    transform: translateX(-10px);
}

.ant-segmented .ant-segmented-item-label {
    @apply py-2 text-base font-semibold;
}
.ant-segmented .ant-segmented-item-selected {
    @apply bg-blue-500 text-white transition-all duration-500;
}

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

/* Personnalisation du dropdown examen */
.ant-select-multiple .ant-select-selection-item {
    @apply bg-gray-100 text-base items-center py-4 px-2 gap-2  !important;
}

.ant-select-selector {
    @apply py-1 !important;
}

.ant-select-selection-placeholder {
    @apply text-base !text-gray-400 !important;
}

/* Personnalisation du dropdown */
.ant-select-dropdown {
    @apply rounded-lg shadow-lg border border-gray-100 !important;
}

.ant-select-item {
    @apply px-4 py-3 hover:bg-blue-50 transition-colors !important;
}

.ant-select-item-option-selected:not(.ant-select-item-option-disabled) {
    @apply bg-blue-100 font-medium !important;
}

.ant-select-clear {
    @apply bg-gray-100 rounded-full p-1.5 !important;
}

.ant-select-single:not(.ant-select-customize-input) .ant-select-selector {
    @apply h-11;
}

/* Style spécifique pour la section d'examens */
.custom-select .ant-select-selector {
    @apply border-0 shadow-none !important;
}

.ant-card .ant-card-head {
    @apply bg-blue-500 text-white !important;
}

/* card pour les 4 derniers consultations */
</style>
