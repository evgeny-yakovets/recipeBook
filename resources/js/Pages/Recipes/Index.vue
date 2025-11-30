<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Header from '@/Pages/Recipes/Common/Header.vue';
import axios from 'axios';
import { reactive, computed, ref } from 'vue';
import RecipeCard from '@/Pages/Recipes/Common/RecipeCard.vue';
import Dropdown from '@/Components/Dropdown.vue';
import { Head } from '@inertiajs/vue3';

const EMPTY_STRING_VALUE = '';
const DEBOUNCE_DELAY = 500;
const STARTING_PAGE = 1;

const props = defineProps({
  recipes: Object, // { data: [...], next_page: ... }
  cuisines: Array,
});

let debounceTimer = null;

const pageData = reactive({
  isLoading: false,
  recipes: [...props.recipes.data],
  page: STARTING_PAGE,
  nextPageAvailable: props.recipes.next_page,
  filters: {
    search: EMPTY_STRING_VALUE,
    cuisine_type: EMPTY_STRING_VALUE,
  },
});

const submitFilters = () => {
  clearTimeout(debounceTimer);

  debounceTimer = setTimeout(async () => {
    pageData.isLoading = true;

    try {
      await axios.get('/recipes/search', {
        params: {
          page: STARTING_PAGE,
          search: pageData.filters.search,
          cuisine_type: pageData.filters.cuisine_type,
        }
      }).then(({data}) => {
        pageData.recipes = data.data;
        pageData.page = STARTING_PAGE;
        pageData.nextPageAvailable = data.next_page;
      });

    } catch (error) {
      console.error('There was an error!', error);
    } finally {
      pageData.isLoading = false;
    }
  }, DEBOUNCE_DELAY);
};

const loadMore = async () => {
  if (!pageData.nextPageAvailable || pageData.isLoading) return;

  pageData.isLoading = true;
  try {
    await axios.get('/recipes/search', {
      params: {
        page: pageData.page + 1,
        search: pageData.filters.search,
        cuisine_type: pageData.filters.cuisine_type,
      }
    }).then(({data}) => {
      pageData.recipes.push(...data.data);
      pageData.page += 1;
      pageData.nextPageAvailable = data.next_page;
    });
    
  } catch (error) {
    console.error('Error loading more recipes', error);
  } finally {
    pageData.isLoading = false;
  }
};
</script>

<template>
  <Head title="Recipes" />

  <AuthenticatedLayout>
    <template #header>
      <Header>
        <template #filter>
          <div class="flex gap-4 items-center">
            <Dropdown
                :searchable="cuisines.length > 1"
                width="160"
            >
                <template #trigger>
                  <button
                    class="input w-48 text-left truncate overflow-hidden whitespace-nowrap"
                    :title="pageData.filters.cuisine_type || 'All cuisines'"
                  >
                    {{ pageData.filters.cuisine_type || 'All cuisines' }}
                  </button>
                </template>

                <template #content="{ search }">
                    <ul class="max-h-60 overflow-auto">
                        <li
                            class="px-3 py-2 hover:bg-indigo-100 cursor-pointer"
                            @click="pageData.filters.cuisine_type = ''; submitFilters(); open = false"
                        >
                            All cuisines
                        </li>
                        <li
                            v-for="cuisine in cuisines.filter(c => c.toLowerCase().includes(search.toLowerCase()))"
                            :key="cuisine"
                            class="px-3 py-2 hover:bg-indigo-100 cursor-pointer"
                            @click="pageData.filters.cuisine_type = cuisine; submitFilters(); open = false"
                        >
                            {{ cuisine }}
                        </li>
                    </ul>
                </template>
            </Dropdown>
            
            <input 
                v-model="pageData.filters.search"
                @input="submitFilters"
                placeholder="Search by Name..."
                class="input w-1/2"
            >
          </div>
        </template>
      </Header>
    </template>

    <div class="p-6">
      <div class="space-y-4">
        <template v-for="recipe in pageData.recipes" :key="recipe.id">
          <RecipeCard :recipe="recipe" :current-user="$page.props.auth.user"/>
        </template>
      </div>
    </div>

    <div class="flex justify-center mt-6">
      <button
        v-if="pageData.nextPageAvailable"
        @click="loadMore()"
        class="px-5 py-3 bg-gray-200 hover:bg-gray-300 rounded-lg transition"
      >
        <span v-if="!pageData.isLoading">Load more</span>
        <span v-else>Loading...</span>
      </button>
    </div>
  </AuthenticatedLayout>
</template>
