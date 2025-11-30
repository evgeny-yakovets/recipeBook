<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import Header from '@/Pages/Recipes/Common/Header.vue';

const props = defineProps({
  recipe: {
    type: Object,
    default: null
  }
});

const isEdit = computed(() => props.recipe !== null);
const title = computed(() => isEdit.value ? 'Edit Recipe' : 'Create Recipe');

const previewUrl = ref(props.recipe?.image ?? null); 
const fileInput = ref(null);

const form = useForm({
  name: props.recipe ? props.recipe.name : '',
  description: props.recipe ? props.recipe.description : '',
  ingredients: props.recipe ? props.recipe.ingredients.join('\n') : '',
  steps: props.recipe ? props.recipe.steps.join('\n') : '',
  image: null,
  cuisine_type: props.recipe ? props.recipe.cuisine_type : '',
});

const handleImageChange = (e) => {
  const file = e.target.files[0];
  if (!file) return;

  form.image = file;

  previewUrl.value = URL.createObjectURL(file);
};

const submit = () => {
  if (!validateFrontend()) return;

  const url = isEdit.value
    ? `/recipes/${props.recipe.id}`
    : '/recipes/create';

  form.post(url, {
    forceFormData: true,           
    onSuccess: () => router.visit('/'),
    onError: (errors) => {
      console.log('Validation errors', errors);
    }
  });
};


const validateFrontend = () => {
  const errors = {};

  if (!form.name || form.name.length === 0) {
    errors.name = "Name is required.";
  } else if (form.name.length > 255) {
    errors.name = "Name must be at most 255 characters.";
  }

  if (form.cuisine_type && form.cuisine_type.length > 255) {
    errors.cuisine_type = "Cuisine type must be at most 255 characters.";
  }

  if (form.description && form.description.length > 2000) {
    errors.description = "Description must be at most 2000 characters.";
  }

  if (!form.ingredients || form.ingredients.length === 0) {
    errors.ingredients = "Ingredients is required.";
  } else if (form.ingredients.length > 500) {
    errors.ingredients = "Ingredients must be at most 500 characters.";
  }

  if (!form.steps || form.steps.length === 0) {
    errors.steps = "Steps is required.";
  } else if (form.steps.length > 500) {
    errors.steps = "Steps must be at most 500 characters.";
  }

  const stepsList = form.steps.split("\n").filter(i => i.trim() !== "");
  stepsList.forEach((s, idx) => {
    if (s.length > 500) {
      errors.steps = `Step #${idx + 1} exceeds 500 characters.`;
    }
  });

  form.errors = errors;

  return Object.keys(errors).length === 0;
};


</script>

<template>
  <Head :title="props.recipe?.name ?? 'Create Recipe'" />

  <AuthenticatedLayout>
    <template #header>
      <Header :recipe="props.recipe" :is-edit-mode="true" />
    </template>

    <div class="p-6 max-w-xl mx-auto space-y-6">

      <h1 class="text-3xl font-semibold relative">
        {{ title }}
      </h1>

      <div class="space-y-3">
        <label class="font-medium text-gray-700">Picture</label>

        <input 
          type="file" 
          ref="fileInput"
          class="block w-full text-sm text-gray-600"
          accept="image/*"
          @change="handleImageChange"
        />

        <div v-if="previewUrl" class="mt-2">
          <img
            :src="previewUrl"
            alt="preview"
            class="rounded-lg shadow max-h-56 object-cover"
          >
        </div>
      </div>

      <div class="space-y-1">
        <label class="font-medium text-gray-700">Name</label>
        <input 
          v-model="form.name" 
          class="input w-full" 
          placeholder="Recipe name"
        >
        <div v-if="form.errors.name" class="text-red-600 text-sm">
          {{ form.errors.name }}
        </div>
      </div>

      <div class="space-y-1">
        <label class="font-medium text-gray-700">Cuisine type</label>
        <input 
          v-model="form.cuisine_type" 
          class="input w-full" 
          placeholder="Cuisine type"
        >
        <div v-if="form.errors.cuisine_type" class="text-red-600 text-sm">
          {{ form.errors.cuisine_type }}
        </div>
      </div>

      <div class="space-y-1">
        <label class="font-medium text-gray-700">Description</label>
        <textarea 
          v-model="form.description" 
          class="input w-full h-20"
          placeholder="Short description"
        ></textarea>
        <div v-if="form.errors.description" class="text-red-600 text-sm">
          {{ form.errors.description }}
        </div>
      </div>

      <div class="space-y-1">
        <label class="font-medium text-gray-700">Ingredients</label>
        <textarea 
          v-model="form.ingredients" 
          class="input w-full h-32"
          placeholder="One ingredient per line"
        ></textarea>
        <div v-if="form.errors.ingredients" class="text-red-600 text-sm">
          {{ form.errors.ingredients }}
        </div>
      </div>

      <div class="space-y-1">
        <label class="font-medium text-gray-700">Steps</label>
        <textarea 
          v-model="form.steps" 
          class="input w-full h-32"
          placeholder="One step per line"
        ></textarea>

        <div v-if="form.errors.steps" class="text-red-600 text-sm">
          {{ form.errors.steps }}
        </div>
      </div>

      <div class="flex items-center pt-4">

        <button 
          @click="submit" 
          class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow"
        >
          Save
        </button>
      </div>

    </div>
  </AuthenticatedLayout>
</template>

