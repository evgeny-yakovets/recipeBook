<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  recipe: Object
});
</script>

<template>
<Link
        :href="`/recipes/${recipe.id}`"
        class="block bg-white rounded-xl shadow transition hover:shadow-xl hover:-translate-y-1 overflow-hidden"
    >
        <div v-if="recipe.image" class="h-40 overflow-hidden">
        <img 
            :src="recipe.image" 
            class="w-full h-full object-cover transition duration-300 hover:scale-110"
        />
        </div>

        <div class="p-4 space-y-2">
            <h2 class="font-bold text-lg">{{ recipe.name }}</h2>

            <p class="text-sm text-gray-500">
                Created by: {{ recipe.user_name }}
            </p>

            <p class="text-gray-600 text-sm line-clamp-3">
                {{ recipe.description }}
            </p>

            <div class="mt-2 flex gap-4">
                <Link :href="`/recipes/${recipe.id}`" class="text-blue-600 hover:underline">View</Link>
                <Link :href="`/recipes/${recipe.id}/edit`" class="text-blue-600 hover:underline">Edit</Link>
                <Link
                    :href="`/recipes/${recipe.id}`"
                    method="delete"
                    class="text-red-600 hover:underline"
                >
                    Delete
                </Link>
            </div>
        </div>
</Link>
    
</template>
