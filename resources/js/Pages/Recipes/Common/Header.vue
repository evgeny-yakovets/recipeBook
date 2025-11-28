<script setup>  
import { Link} from '@inertiajs/vue3';

const props = defineProps({
  recipe: {
    type: Object,
    default: null
  },
  isEditMode: {
    type: Boolean,
    default: false
  }
});
</script>
<template>
    <div class="flex justify-between items-center">
        <div class="flex justify-between items-center gap-4">
            <Link href="/recipes/create" class="btn-primary">Add new</Link>
            <template v-if="props.recipe">
                <Link href="/recipes" class="text-gray-600">Back</Link>
                <div class="flex alig-center min-w-[36px]">
                    <Link v-if="props.isEditMode" :href="`/recipes/${props.recipe.id}`" class="text-blue-600 hover:underline">View</Link>
                    <Link v-else :href="`/recipes/${recipe.id}/edit`" class="text-blue-600 hover:underline">Edit</Link>
                </div>
                <Link
                    :href="`/recipes/${recipe.id}`"
                    method="delete"
                    class="text-red-600 hover:underline"
                >
                    Delete
                </Link>
            </template>
        </div>
        <div>
            <slot name="filter"></slot>
        </div>
    </div>
</template>
