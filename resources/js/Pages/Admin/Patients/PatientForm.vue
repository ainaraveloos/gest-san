<template>
    <a-modal
      :visible="visible"
      :width="600"
      :footer="null"
      @cancel="$emit('close')"
    >
        <h1 class="mb-4 text-lg font-bold text-gray-500">{{ patient?'Modifier Information':'Ajouter un Patient' }}</h1>
      <a-form
        layout="vertical"
        :model="form"
        @finish="$emit('submit', form)"
        class="!px-4"
      >
        <!-- Section Type et Référence -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
          <a-form-item
            label="Type de patient"
            name="type"
            :rules="[{ required: true, message: 'Ce champ est obligatoire' }]"
          >
            <a-select
              v-model:value="form.type"
              @change="handleTypeChange"
              placeholder="Sélectionner un type"
              class="rounded-lg border-gray-300 border-2"
            >
              <a-select-option value="SALARIE">Salarié</a-select-option>
              <a-select-option value="FAMILLE">Bénéficiaire</a-select-option>
            </a-select>
          </a-form-item>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4" v-if="form.type === 'SALARIE'">
          <a-form-item
            label="Société"
            name="societe_id"
            :rules="[{ required: true, message: 'Ce champ est obligatoire' }]"
          >
            <a-select
              v-model:value="form.societe_id"
              placeholder="Sélectionner une société"
              class="rounded-lg border-gray-300 border-2  ">
              <a-select-option class="p-2"
                v-for="societe in societes"
                :key="societe.id"
                :value="societe.id"
              >
                {{ societe.nom }}
              </a-select-option>
            </a-select>
          </a-form-item>

          <a-form-item
            label="Poste occupé"
            name="poste"
          >
            <a-input v-model:value="form.poste" class="rounded-lg border-gray-300 border-2 !p-1 " />
          </a-form-item>
        </div>

          <a-form-item
            v-if="form.type === 'FAMILLE'"
            label="Salarié Référent"
            name="parent_id"
            :rules="[{ required: true, message: 'Ce champ est obligatoire' }]"
          >
            <a-select
              v-model:value="form.parent_id"
              show-search
              option-filter-prop="label"
              placeholder="Rechercher un salarié..."
              class="rounded-lg border-gray-300 border-2"
            >
              <a-select-option
                v-for="salarie in salaries"
                :key="salarie.id"
                :value="salarie.id"
                :label="`${salarie.nom} ${salarie.prenom}`"
              >
                {{ salarie.nom }} {{ salarie.prenom }} ({{ salarie.numero }})
              </a-select-option>
            </a-select>
          </a-form-item>
        </div>

        <!-- Informations de base -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <a-form-item
            label="Nom"
            name="nom"
            :rules="[{ required: true, message: 'Ce champ est obligatoire' }]"
          >
            <a-input v-model:value="form.nom" class="rounded-lg border-gray-300 border-2 " />
          </a-form-item>

          <a-form-item
            label="Prénom"
            name="prenom"
            :rules="[{ required: true, message: 'Ce champ est obligatoire' }]"
          >
            <a-input v-model:value="form.prenom" class="rounded-lg border-gray-300 border-2 " />
          </a-form-item>
        </div>

        <!-- Informations supplémentaires -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <a-form-item
            label="Date de naissance"
            name="date_naissance"
            :rules="[{ required: true, message: 'Ce champ est obligatoire' }]"

          >
            <a-date-picker v-model:value="form.date_naissance"  format="YYYY-MM-DD" class="rounded-lg !w-full hover:border-blue-600 transition-colors duration-300 border-gray-300 border-2 p-2 " />
          </a-form-item>

          <a-form-item
            label="Adresse"
            name="adresse"
            :rules="[{ required: true, message: 'Ce champ est obligatoire' }]"
          >
            <a-input v-model:value="form.adresse" class="rounded-lg border-gray-300 border-2 " />
          </a-form-item>
        </div>
        <a-form-item
          label="Lien de parenté"
          name="lien_parente"
          v-if="form.type === 'FAMILLE'"
          :rules="[{ required: true, message: 'Ce champ est obligatoire' }]"
        >
          <a-input v-model:value="form.lien_parente" class="rounded-lg border-gray-300 border-2 " />
        </a-form-item>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <a-form-item
            label="Téléphone"
            name="telephone"
            :rules="[{ required: false, message: 'Veuillez entrer un numéro de téléphone valide', pattern: /^[0-9]{10}$/ }]"
          >
            <a-input v-model:value="form.telephone" class="rounded-lg border-gray-300 border-2 " />
          </a-form-item>

          <a-form-item
            label="Email"
            name="email"
            :rules="[{ required: false, type: 'email', message: 'Veuillez entrer un email valide' }]"
          >
            <a-input v-model:value="form.email" class="rounded-lg border-gray-300 border-2 " />
          </a-form-item>
        </div>

        <!-- Informations société -->


        <!-- Actions -->
        <div class="flex justify-end gap-4 mt-6">
          <a-button @click="$emit('close')">Annuler</a-button>
          <a-button
            type="primary"
            html-type="submit"
            :loading="processing"
          >
            {{ patient ? 'Modifier' : 'Créer' }}
          </a-button>
        </div>
      </a-form>
    </a-modal>
  </template>

  <script setup>
  import { useForm } from '@inertiajs/vue3';
  import { ref, watch, defineEmits } from 'vue';

  const props = defineProps({
    visible: Boolean,
    patient: Object,
    societes: Array,
    salaries: Array
  });

  const emit = defineEmits(['submit', 'close']);


  const form = useForm({
    type: 'SALARIE',
    parent_id: null,
    societe_id: null,
    lien_parente:'',
    nom: '',
    prenom: '',
    date_naissance: null,
    adresse: '',
    telephone: '',
    email: '',
    poste: ''
  });


  // Réinitialiser le formulaire quand on ouvre la modal
  watch(() => props.patient, (newPatient) => {
    if (newPatient) {
      form.value = { ...newPatient };
    } else {
      resetForm();
    }
  });

  const handleTypeChange = (type) => {
    if (type === 'SALARIE') {
      form.value.parent_id = null;
    }
  };

  const resetForm = () => {
    form.value = {
      type: 'SALARIE',
      salarie_id: null,
      societe_id: null,
      nom: '',
      prenom: '',
      date_naissance: null,
      adresse: '',
      telephone: '',
      email: '',
      poste: ''
    };
  };
  </script>
