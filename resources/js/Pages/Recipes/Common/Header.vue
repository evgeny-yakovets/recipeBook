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
    <div class="flex justify-between items-center h-[42px]">
        <div class="flex justify-between items-center gap-4">
            <Link 
                v-if="$page.props.auth.user" 
                data-testid="addNewLink"
                href="/recipes/create" 
                class="btn-primary">
                Add new
            </Link>
            <Link 
                v-if="(!props.recipe && props.isEditMode) || props.recipe"
                data-testid="backLink" 
                href="/" 
                class="text-gray-600">
                Back
            </Link>
            <template v-if="props.recipe">
                <div class="flex alig-center min-w-[36px]">
                    <Link 
                        v-if="props.isEditMode" 
                        data-testid="viewLink" 
                        :href="`/recipes/${props.recipe.id}`" 
                        class="text-gray-600">
                        View
                    </Link>
                    <Link 
                        v-else 
                        data-testid="editLink" 
                        :href="`/recipes/${recipe.id}/edit`" 
                        class="text-gray-600">
                        Edit
                    </Link>
                </div>
                <Link
                    data-testid="deleteLink" 
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
