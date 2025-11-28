<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';
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

const deleteRecipe = () => {
  if (confirm("Удалить рецепт?")) {
    router.delete(`/recipes/${props.recipe.id}`);
  }
};
</script>

<template>
    <Head :title="props.recipe.name" />

    <AuthenticatedLayout>
        <template #header>
            <Header :recipe="props.recipe"/>
        </template>
        <div class="p-6 max-w-3xl mx-auto space-y-6">
            
            <h1 class="text-3xl font-bold">{{ recipe.name }}</h1>

            <div class="text-gray-600 text-sm">
            Author: <b>{{ recipe.user_name ?? "Unknown" }}</b>  
            •  
            {{ new Date(recipe.created_at).toLocaleDateString() }}
            </div>

            <div v-if="recipe.image" class="mt-4">
            <img :src="recipe.image" alt="" class="rounded-lg shadow" />
            </div>

            <div class="mt-4">
            <h2 class="text-xl font-semibold mb-2">Description</h2>
            <p>{{ recipe.description }}</p>
            </div>

            <div>
            <h2 class="text-xl font-semibold mb-2">Ingredients</h2>
            <ul class="list-disc list-inside space-y-1">
                <li v-for="(item, index) in recipe.ingredients" :key="index">
                {{ item }}
                </li>
            </ul>
            </div>

            <div>
            <h2 class="text-xl font-semibold mb-2">Steps</h2>
            <ol class="list-decimal list-inside space-y-2">
                <li v-for="(step, index) in recipe.steps" :key="index">
                {{ step }}
                </li>
            </ol>
            </div>
        </div>
    </AuthenticatedLayout>
  
</template>
