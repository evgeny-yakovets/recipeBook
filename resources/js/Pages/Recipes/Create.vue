<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Link, router } from '@inertiajs/vue3';
import axios from 'axios';
import { computed } from 'vue';
import Header from '@/Pages/Recipes/Common/Header.vue';

const props = defineProps({
  recipe: {
    type: Object,
    default: null
  }
});

const isEdit = computed(() => props.recipe !== null);
const title = computed(() => isEdit.value ? 'Edit Recipe' : 'Create Recipe');

const form = useForm({
  name: props.recipe ? props.recipe.name : '',
  description: props.recipe ? props.recipe.description : '',
  ingredients: props.recipe ? props.recipe.ingredients.join('\n') : '',
  steps: props.recipe ? props.recipe.steps.join('\n') : '',
});

const submit = () => {
  const payload = {
    id: null,
    name: form.name,
    description: form.description,
    ingredients: form.ingredients.split('\n'),
    steps: form.steps.split('\n'),
  };

  if(isEdit.value) {
    payload.id = props.recipe.id;

    axios.put(`/recipes/${props.recipe.id}`,  payload)
    .then(() => {
      router.visit('/recipes');
    })
    .catch(error => {
      console.error('There was an error!', error);
    });
  } else {
    axios.post('/recipes/store',  payload)
    .then(() => {
      router.visit('/recipes');
    })
    .catch(error => {
      console.error('There was an error!', error);
    });
  }
};



</script>

<template>
  <Head :title="props.recipe?.name ?? 'Create Recipe'" />

  <AuthenticatedLayout>
    <template #header>
        <Header :recipe="props.recipe" :is-edit-mode="true"/>
    </template>

    <div class="p-6 max-w-xl mx-auto space-y-4">
      <h1 class="text-2xl font-bold">{{title}}</h1>

      <input v-model="form.name" class="input" placeholder="Name">

      <textarea v-model="form.description" class="input" placeholder="Description"></textarea>

      <textarea v-model="form.ingredients" class="input" placeholder="Ingredients"></textarea>

      <textarea v-model="form.steps" class="input" placeholder="Steps"></textarea>

      <button @click="submit" class="btn-primary">Save</button>

      <Link href="/recipes" class="text-gray-600">Back</Link>
    </div>
  </AuthenticatedLayout>

  
</template>

