<script setup>
import { UserRole } from '@/Helpers/Enums/UserRole';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  recipe: Object,
  currentUser: Object,
});
</script>

<template>
<Link
        :href="props.currentUser ? `/recipes/${recipe.id}` : '#'"
        class="cursor-default flex gap-[8px] block bg-white rounded-xl shadow overflow-hidden"
        :class="{
            'cursor-default ': !props.currentUser, 
            'hover:shadow-lg hover:scale-[1.02] transition-transform duration-200': props.currentUser 
        }"
    >
        <div class="h-40 overflow-hidden">
            <img 
                :src="`${recipe.image}`" 
                class="w-[160px] h-full"
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

            <div v-if="props.currentUser && (props.currentUser.id === recipe.user_id || props.currentUser.role === UserRole.admin)" class="mt-2 flex gap-4">
                <Link :href="`/recipes/${recipe.id}`" class="text-gray-600">View</Link>
                <Link :href="`/recipes/${recipe.id}/edit`" class="text-gray-600">Edit</Link>
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
