<template>
    <div class="p-2 rounded-md transform transition-all duration-300">
        <div class="bg-blue-500 p-4 rounded-t-md">
            <div class="text-white text-2xl font-bold mb-6">
                <h1>Selectionner un Patient</h1>
            </div>
            <div
                class="relative flex items-center justify-center md:flex-row flex-col-reverse mb-4 gap-2"
            >
                <div class="relative w-full flex-1 z-50 group">
                    <BaseInput
                        type="text"
                        v-model="patientSearchTerm"
                        placeholder="Rechercher un nom, prenom, numero ..."
                        @focus="dropdownVisible = true"
                        @blur="dropdownVisible = false"
                    />

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
                            class="absolute w-full bg-white shadow-lg p-2 flex flex-col gap-1 rounded-md mt-4 max-h-60 overflow-y-auto custom-scroll"
                        >
                            <div
                                v-for="(patient, index) in filteredPatients"
                                :key="patient.id"
                                @click="selectPatient(patient)"
                                class="px-4 py-2 hover:bg-blue-50 rounded-md transition-all duration-300 cursor-pointer hover:shadow-sm"
                                :style="{ transitionDelay: `${index * 30}ms` }"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center transition-all duration-300 group-hover:bg-blue-200"
                                    >
                                        <span
                                            class="text-blue-500 font-medium text-sm"
                                            >{{ patient.initials }}</span
                                        >
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">
                                            {{ patient.nom }}
                                            {{ patient.prenom }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-0.5">
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
                            class="absolute z-50 w-full bg-white shadow-lg p-4 rounded-md mt-2"
                        >
                            <div
                                class="text-gray-500 text-center py-2 animate-pulse"
                            >
                                <a-empty
                                    description="veuillez rechecher un patient"
                                />
                            </div>
                        </div>
                    </transition>
                </div>

                <BaseButton
                    @click="handleAdd"
                    class="py-3"
                    bg-color="bg-green-600"
                    hover-color="bg-green-700"
                    focus-color="ring-green-400"
                    active-color="bg-green-800"
                >
                    <span class="flex items-center"> Ajouter le patient </span>
                </BaseButton>
            </div>
        </div>

        <!-- Section 4 derniers consultations -->
        <div class="bg-white rounded-b-md px-4 py-6">
            <h2 class="text-xl text-blue-500 font-medium mb-6">
                Les 4 derniers consultations
                <fonta icon="calendar-check" class="ml-2" />
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <Link
                    v-for="consultation in consultations"
                    :key="consultation.id"
                    :href="
                        route(
                            'patient.dossier.details',
                            consultation.patient.id
                        )
                    "
                    class="group transition-all duration-300"
                >
                    <div
                        class="bg-blue-50 rounded-lg p-6 border border-blue-100 hover:bg-green-100/50 hover:scale-105 duration-500 transition-all hover:border-blue-200 cursor-pointer"
                    >
                        <div class="space-y-3">
                            <!-- Date -->
                            <div class="text-sm font-medium text-blue-500">
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
                                    class="text-blue-400 text-sm"
                                />
                                <span
                                    class="text-base font-semibold text-blue-800"
                                >
                                    {{ consultation.patient.numero }}
                                </span>
                            </div>
                        </div>
                    </div>
                </Link>

                <div v-if="consultations.length === 0" class="col-span-full">
                    <a-empty
                        description="Aucune consultation enregistrée"
                        class="text-blue-200"
                    />
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de creation de consultations, ordonnance , lettre de reference, demande d'examens -->
    <transition name="custom" mode="out-in">
        <div
            v-if="showConsultation"
            :key="addedPatient.id"
            class="card-container consultation-section py-6 my-6 rounded-md shadow-md bg-white"
        >
            <form @submit.prevent="submitConsultation">
                <div class="flex items-center justify-between p-8">
                    <div>
                        <h2 class="text-xl font-bold text-gray-600">
                            {{ addedPatient.nom }}
                            {{ addedPatient.prenom }}
                        </h2>
                        <span
                            class="text-sm bg-blue-100 text-blue-800 px-3 py-1 rounded-full"
                        >
                            {{ addedPatient.numero }}
                        </span>
                    </div>

                    <div class="flex items-center justify-center">
                        <BaseButton
                            type="submit"
                            class="py-2"
                            bg-color="bg-green-600"
                            hover-color="bg-green-700"
                            focus-color="ring-green-400"
                            active-color="bg-green-800"
                        >
                            <fonta
                                class="w-5 h-5 fill-current"
                                icon="floppy-disk"
                            />
                            <span> Enregistrer la consultation </span>
                        </BaseButton>
                    </div>
                </div>

                <a-tabs v-model:activeKey="activeKey" type="card" >
                    <a-tab-pane key="1">
                        <template #tab >
                            <span class="flex items-center gap-2">
                                <fonta icon="file-waveform" class="text-lg" />
                                Fiche de Consultation
                            </span>
                        </template>
                        <div
                            class="mt-4 mx-4 border-2 border-gray-200 rounded-md p-4"
                        >
                            <div
                                class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4"
                            >
                                <InputLabel
                                    value="Nom Complet"
                                    class="col-span-1"
                                />
                                <div
                                    class="flex w-full items-center justify-start bg-gray-50 col-span-3 py-1 px-4 border-gray-300 border rounded-md"
                                >
                                    <span
                                        class="font-semibold text-lg text-gray-500"
                                    >
                                        {{ addedPatient.nom }}
                                        {{ addedPatient.prenom }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <InputLabel value="Age" class="col-span-1" />
                                <div
                                    class="w-full bg-gray-50 md:col-span-1 col-span-2 py-1 px-4 border-gray-300 border rounded-md"
                                >
                                    <span
                                        class="font-semibold text-lg text-gray-500"
                                        >{{ patientAge }} ans</span
                                    >
                                </div>

                                <div class="col-span-1 text-base font-semibold">
                                    <h1>Date :</h1>
                                </div>
                                <div
                                    class="flex items-center gap-2 md:col-span-1 w-full border-gray-300 border text-gray-500 bg-gray-50 px-4 py-2 rounded-md text-base font-semibold"
                                >
                                    <fonta icon="fa-regular fa-calendar" />
                                    <span>{{
                                        new Date().toLocaleDateString()
                                    }}</span>
                                </div>
                            </div>
                        </div>
                        <div
                            class="mt-4 mx-4 border-2 border-gray-200 rounded-md p-4"
                        >
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Section Gauche - Informations Générales -->
                                <div class="space-y-4">
                                    <h3
                                        class="text-lg font-semibold text-blue-500 mb-4"
                                    >
                                        Informations Générales
                                    </h3>

                                    <div >
                                        <InputLabel
                                            for="type de consultation"
                                            value="Type de consultation"
                                            class="mb-2"
                                        />
                                        <a-segmented
                                            v-model:value="form.type"
                                            :options="options_type"
                                            block
                                            class="custom-segmented"
                                        />
                                    </div>

                                    <div >

                                        <textarea
                                            v-model="form.motif"
                                            required
                                            class="mt-1 block w-full p-2 border rounded-lg border-gray-300 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                            placeholder="Décrire le motif de la consultation..."
                                        ></textarea>
                                    </div>

                                    <div>

                                        <textarea
                                            v-model="form.diagnostic"
                                            class="mt-1 block w-full p-2 border rounded-lg border-gray-300 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                            placeholder="Rédiger le diagnostic..."
                                            required
                                        ></textarea>
                                    </div>
                                </div>

                                <!-- Section Droite - Constantes Vitales -->
                                <div class="space-y-4">
                                    <h3
                                        class="text-lg font-semibold text-blue-500 mb-4"
                                    >
                                        Constantes Vitales
                                    </h3>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <InputLabel
                                                value="Poids (kg)"
                                                class="mb-2"
                                            />

                                            <BaseInput
                                                type="number"
                                                v-model.number="form.poids"
                                                min="0"
                                                step="5"
                                                required
                                            />
                                        </div>

                                        <div>
                                            <InputLabel
                                                value="Taille (cm)"
                                                class="mb-2"
                                            />
                                            <BaseInput
                                                type="number"
                                                v-model.number="form.taille"
                                                min="0"
                                                required
                                            />
                                        </div>

                                        <div>
                                            <InputLabel
                                                value="Fréquence cardiaque (bpm)"
                                                class="mb-2"
                                            />
                                            <BaseInput
                                                type="number"
                                                v-model.number="form.freq_card"
                                                class="mt-1 block w-full p-2 border rounded-lg border-gray-300 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                                min="0"
                                                required
                                            />
                                        </div>

                                        <div>
                                            <InputLabel
                                                value="Pression artérielle (mmHg)"
                                                class="mb-2"
                                            />
                                            <BaseInput
                                                type="text"
                                                v-model.number="form.press_art"
                                                placeholder="120/80"
                                                required
                                            />
                                        </div>
                                    </div>

                                    <div>
                                        <InputLabel
                                            value="Température (°C)"
                                            class="mb-2"
                                        />
                                        <BaseInput
                                            type="number"
                                            v-model.number="form.temperature"
                                            min="0"
                                            required
                                        />
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
                            class="mt-4 mx-4 border-2 border-gray-200 rounded-md p-4"
                        >
                            <div
                                class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4"
                            >
                                <InputLabel
                                    value="Nom Complet"
                                    class="col-span-1"
                                />
                                <div
                                    class="flex w-full items-center justify-start bg-gray-50 col-span-3 py-1 px-4 border-gray-300 border rounded-md"
                                >
                                    <span
                                        class="font-semibold text-lg text-gray-500"
                                    >
                                        {{ addedPatient.nom }}
                                        {{ addedPatient.prenom }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <InputLabel value="Age" class="col-span-1" />
                                <div
                                    class="w-full bg-gray-50 md:col-span-1 col-span-2 py-1 px-4 border-gray-300 border rounded-md"
                                >
                                    <span
                                        class="font-semibold text-lg text-gray-500"
                                        >{{ patientAge }} ans</span
                                    >
                                </div>

                                <div class="col-span-1 text-base font-semibold">
                                    <h1>Date :</h1>
                                </div>
                                <div
                                    class="flex items-center gap-2 md:col-span-1 w-full border-gray-300 border text-gray-500 bg-gray-50 px-4 py-2 rounded-md text-base font-semibold"
                                >
                                    <fonta icon="fa-regular fa-calendar" />
                                    <span>{{
                                        new Date().toLocaleDateString()
                                    }}</span>
                                </div>
                            </div>
                        </div>
                        <!-- medicament -->
                        <div
                            class="max-w-full mx-4 mt-4 p-4 space-y-6 bg-white rounded-md border-2 border-gray-200 shadow-xl shadow-blue-100/50 pb-2"
                        >
                            <!-- En-tête du tableau -->
                            <div
                                class="font-bold text-lg text-gray-600 flex justify-center items-center gap-4"
                            >
                                <h1>Ajouter des médicaments</h1>
                                <fonta icon="capsules" class="text-2xl mb-2" />
                            </div>
                            <div
                                class="grid grid-cols-4 text-base gap-4 font-semibold text-blue-500"
                            >
                                <div>Désignation</div>
                                <div>Forme</div>
                                <div>Posologie</div>
                                <div>Quantité</div>
                            </div>

                            <!-- Lignes des médicaments -->
                            <div
                                v-for="(med, index) in form.medicament"
                                :key="index"
                                class="grid grid-cols-4 gap-4 items-center group transition-all duration-300 ease-in-out transform"
                            >
                                <div>
                                    <BaseInput
                                        v-model="med.designation"
                                        placeholder="Paracétamol..."
                                    />
                                </div>

                                <div>
                                    <BaseInput
                                        v-model="med.forme"
                                        placeholder="Comprimé..."
                                    />
                                </div>

                                <div>
                                    <BaseInput
                                        v-model="med.posologie"
                                        placeholder="1 comprimé 3x/jour..."
                                    />
                                </div>

                                <div class="flex items-center gap-4">
                                    <BaseInput
                                        v-model="med.quantite"
                                        type="number"
                                        placeholder="10"
                                    />
                                    <button
                                        type="button"
                                        @click="removeMed(index)"
                                        class="text-red-500 hover:text-red-700 group-hover:scale-110 transition-all duration-500"
                                    >
                                        <fonta icon="trash" class="text-xl" />
                                    </button>
                                </div>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="py-6 space-x-4">
                                <BaseButton
                                    type="button"
                                    @click="addMed"
                                    class="py-2"
                                >
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
                            class="mt-4 mx-4 border-2 border-gray-200 rounded-md p-4"
                        >
                            <div
                                class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4"
                            >
                                <InputLabel
                                    value="Nom Complet"
                                    class="col-span-1"
                                />
                                <div
                                    class="flex w-full items-center justify-start bg-gray-50 col-span-3 py-1 px-4 border-gray-300 border rounded-md"
                                >
                                    <span
                                        class="font-semibold text-lg text-gray-500"
                                    >
                                        {{ addedPatient.nom }}
                                        {{ addedPatient.prenom }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <InputLabel value="Age" class="col-span-1" />
                                <div
                                    class="w-full bg-gray-50 md:col-span-1 col-span-2 py-1 px-4 border-gray-300 border rounded-md"
                                >
                                    <span
                                        class="font-semibold text-lg text-gray-500"
                                        >{{ patientAge }} ans</span
                                    >
                                </div>

                                <div class="col-span-1 text-base font-semibold">
                                    <h1>Date :</h1>
                                </div>
                                <div
                                    class="flex items-center gap-2 md:col-span-1 w-full border-gray-300 border text-gray-500 bg-gray-50 px-4 py-2 rounded-md text-base font-semibold"
                                >
                                    <fonta icon="fa-regular fa-calendar" />
                                    <span>{{
                                        new Date().toLocaleDateString()
                                    }}</span>
                                </div>
                            </div>
                        </div>
                        <!-- Conteneur input -->
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 mx-4 border-2 border-gray-200 rounded-md p-4 items-end"
                        >
                            <!-- Date de reprise -->
                            <div class="space-y-2">
                                <InputLabel value="Date de reprise" />
                                <BaseInput
                                    type="date"
                                    v-model="returnDate"
                                    :min="minDate"
                                    @change="calculateRestDays"
                                />
                            </div>

                            <!-- Jours de repos calculés , affecter à form.nbr_jours_repos lors de la soumission -->
                            <div class="space-y-2">
                                <InputLabel value="Jours de repos prescrits" />
                                <div class="relative">
                                    <BaseInput
                                        type="number"
                                        :value="restDays"
                                        readonly
                                    />
                                </div>
                            </div>

                            <!-- Message d'information -->
                            <p class="text-base font-medium mt-2">
                                <span
                                    v-if="restDays > 0"
                                    class="text-green-500"
                                >
                                    Le patient dois se reposer jusqu'au
                                    {{ formattedReturnDate }}
                                </span>
                                <span v-else class="text-gray-500">
                                    Sélectionnez une date de reprise future pour
                                    calculer les jours de repos
                                </span>
                            </p>
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
                            class="mt-4 mx-4 border-2 border-gray-200 rounded-md p-4"
                        >
                            <div
                                class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4"
                            >
                                <InputLabel
                                    value="Nom Complet"
                                    class="col-span-1"
                                />
                                <div
                                    class="flex w-full items-center justify-start bg-gray-50 col-span-3 py-1 px-4 border-gray-300 border rounded-md"
                                >
                                    <span
                                        class="font-semibold text-lg text-gray-500"
                                    >
                                        {{ addedPatient.nom }}
                                        {{ addedPatient.prenom }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <InputLabel value="Age" class="col-span-1" />
                                <div
                                    class="w-full bg-gray-50 md:col-span-1 col-span-2 py-1 px-4 border-gray-300 border rounded-md"
                                >
                                    <span
                                        class="font-semibold text-lg text-gray-500"
                                        >{{ patientAge }} ans</span
                                    >
                                </div>

                                <div class="col-span-1 text-base font-semibold">
                                    <h1>Date :</h1>
                                </div>
                                <div
                                    class="flex items-center gap-2 md:col-span-1 w-full border-gray-300 border text-gray-500 bg-gray-50 px-4 py-2 rounded-md text-base font-semibold"
                                >
                                    <fonta icon="fa-regular fa-calendar" />
                                    <span>{{
                                        new Date().toLocaleDateString()
                                    }}</span>
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-white p-6 rounded-md border-2 border-gray-200 m-4"
                        >
                            <h2
                                class="text-lg font-semibold text-gray-600 justify-center mb-4 flex items-center gap-2"
                            >
                                Sélection des examens à réaliser
                                <fonta
                                    class="text-2xl"
                                    icon="vial-circle-check"
                                />
                            </h2>

                            <a-select
                                v-model:value="form.liste_examens"
                                mode="tags"
                                placeholder="Ajouter des examens à faire..."
                                :options="options"
                                :class="{ '!w-full': true }"
                            >
                            </a-select>
                            <div class="w-full my-4">
                                <InputLabel value="Remarques" class="mb-2" />

                                <textarea
                                    v-model="form.remarques"
                                    class="mt-1 block w-full p-2 border rounded-lg border-gray-300 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                    placeholder="Ajouter des remarques..."
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
                            class="mt-4 mx-4 border-2 border-gray-200 rounded-md p-4"
                        >
                            <div
                                class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4"
                            >
                                <InputLabel
                                    value="Nom Complet"
                                    class="col-span-1"
                                />
                                <div
                                    class="flex w-full items-center justify-start bg-gray-50 col-span-3 py-1 px-4 border-gray-300 border rounded-md"
                                >
                                    <span
                                        class="font-semibold text-lg text-gray-500"
                                    >
                                        {{ addedPatient.nom }}
                                        {{ addedPatient.prenom }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <InputLabel value="Age" class="col-span-1" />
                                <div
                                    class="w-full bg-gray-50 md:col-span-1 col-span-2 py-1 px-4 border-gray-300 border rounded-md"
                                >
                                    <span
                                        class="font-semibold text-lg text-gray-500"
                                        >{{ patientAge }} ans</span
                                    >
                                </div>

                                <div class="col-span-1 text-base font-semibold">
                                    <h1>Date :</h1>
                                </div>
                                <div
                                    class="flex items-center gap-2 md:col-span-1 w-full border-gray-300 border text-gray-500 bg-gray-50 px-4 py-2 rounded-md text-base font-semibold"
                                >
                                    <fonta icon="fa-regular fa-calendar" />
                                    <span>{{
                                        new Date().toLocaleDateString()
                                    }}</span>
                                </div>
                            </div>
                        </div>
                        <div
                            class="mt-4 mx-4 border-2 border-gray-200 rounded-md p-4"
                        >
                            <div
                                class="font-bold text-lg mb-6 text-gray-600 flex items-center justify-center gap-4"
                            >
                                <h1>Information pour la lettre de reference</h1>
                                <fonta icon="envelope" class="text-2xl mb-2" />
                            </div>
                            <div
                                class="grid grid-cols-1 md:grid-cols-[200px_1fr] items-center gap-4 mb-4"
                            >
                                <InputLabel value="Médecin référent" />

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
                            <div
                                class="grid grid-cols-1 md:grid-cols-[200px_1fr] items-center gap-4 mb-4"
                            >
                                <InputLabel
                                    value="Motif de reference"
                                    class="mb-1"
                                />
                                <textarea
                                    v-model="form.motif_ref"
                                    class="mt-1 block w-full p-2 border rounded-lg border-gray-300 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                    placeholder="Décrire le motif de la reference..."
                                ></textarea>
                            </div>
                        </div>
                    </a-tab-pane>
                </a-tabs>
                <!-- Boutton enregistrer consultation -->
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
    press_art: "",
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
    form.patient_id = addedPatient.value.id;
    form.post(route("consultations.store"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            addedPatient.value = null;
            showConsultation.value = false;
            message.success({
                content: "Consultation enregistrée avec succès!",
                duration: 3,
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
    @apply !bg-blue-500 !text-white;
}
.ant-tabs > .ant-tabs-nav .ant-tabs-nav-list {
    @apply gap-1;
}

/* Personnalisation du dropdown examen */
.ant-select-multiple .ant-select-selection-item {
    @apply bg-gray-100 text-base items-center py-4 px-2 gap-2  !important;
}

.ant-select-selector {
    @apply py-1 !important;
}

.ant-select-selection-placeholder {
    @apply text-base !text-gray-400;
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
.ant-card .ant-card-head {
    @apply bg-blue-500 text-white;
}

/* card pour les 4 derniers consultations */
</style>
