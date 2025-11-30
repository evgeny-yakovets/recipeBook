<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Header from '@/Pages/Recipes/Common/Header.vue';

const props = defineProps({
  recipe: {
    type: Object,
    required: true
  },
  canEdit: {
    type: Boolean,
    default: false
  }
});
</script>

<template>
  <Head :title="props.recipe.name" />

  <AuthenticatedLayout>
    <template #header>
      <Header :recipe="props.recipe" />
    </template>

    <div class="p-6 max-w-xl mx-auto space-y-6">

      <h1 class="text-3xl font-semibold">
        {{ recipe.name }}
      </h1>

      <div class="text-gray-600 text-sm">
        Author: <b>{{ recipe.user_name ?? "Unknown" }}</b> •
        {{ new Date(recipe.created_at).toLocaleDateString() }}
      </div>

      <div v-if="recipe.image" class="space-y-3">
        <label class="font-medium text-gray-700">Picture</label>
        <img
          :src="recipe.image"
          alt="Recipe image"
          class="rounded-lg max-h-56 object-cover"
        />
      </div>

      <div class="space-y-1">
        <label class="font-medium text-gray-700">Name</label>
        <div class="input w-full bg-gray-100 py-2 px-3 rounded">
          {{ recipe.name }}
        </div>
      </div>

      <div class="space-y-1">
        <label class="font-medium text-gray-700">Cuisine type</label>
        <div class="input w-full bg-gray-100 py-2 px-3 rounded">
          {{ recipe.cuisine_type ?? "—" }}
        </div>
      </div>

      <div class="space-y-1">
        <label class="font-medium text-gray-700">Description</label>
        <div class="input w-full bg-gray-100 py-2 px-3 rounded whitespace-pre-line">
          {{ recipe.description }}
        </div>
      </div>

      <div class="space-y-1">
        <label class="font-medium text-gray-700">Ingredients</label>
        <ul class="bg-gray-100 p-3 list-disc list-inside space-y-1">
          <li v-for="(item, index) in recipe.ingredients" :key="index">
            {{ item }}
          </li>
        </ul>
      </div>

      <div class="space-y-1">
        <label class="font-medium text-gray-700">Steps</label>
        <ol class="bg-gray-100 p-3 list-decimal list-inside space-y-2">
          <li v-for="(step, index) in recipe.steps" :key="index">
            {{ step }}
          </li>
        </ol>
      </div>

    </div>
  </AuthenticatedLayout>
</template>
