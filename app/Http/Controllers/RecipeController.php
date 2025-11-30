<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Recipe;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

class RecipeController extends Controller
{
    const DEFAULT_PAGINATION_LIMIT = 10;
    
    public function index(Request $request)
    {
        return Inertia::render('Recipes/Index', [
            'recipes' => $this->getRecipesByRequest($request),
            'cuisines' => Recipe::whereNotNull('cuisine_type')
                ->where('cuisine_type', '<>', '')
                ->distinct()
                ->pluck('cuisine_type'),
        ]);
    }

    public function recipesSearch(Request $request)
    {
        return response()->json($this->getRecipesByRequest($request));
    }

    private function getRecipesByRequest(Request $request)
    {
        $query = Recipe::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('cuisine_type')) {
            $query->where('cuisine_type', $request->cuisine_type);
        }


        $page = $request->input('page', 1);
        $offset = ($page - 1) * self::DEFAULT_PAGINATION_LIMIT;

        $total = $query->count();
        $recipes = $query
            ->skip($offset)
            ->take(self::DEFAULT_PAGINATION_LIMIT)
            ->get();

        $nextPage = ($offset + $recipes->count() < $total) ? $page + 1 : null;

        return [
            'data' => $recipes->map->serializeRecipe(),
            'next_page' => $nextPage,
            'current_page' => $page,
            'total' => $total,
        ];
    }

    public function create(Request $request)
    {
        $this->authorize('create', Recipe::class);

        return Inertia::render('Recipes/Create');
    }

    public function show(Recipe $recipe)
    {        
        $this->authorize('show', $recipe);

        return Inertia::render('Recipes/View', [
            'recipe' => $recipe->serializeRecipe(),
        ]);
    }

    public function edit(Recipe $recipe)
    {        
        $this->authorize('edit', $recipe);

        return Inertia::render('Recipes/Create', [
            'recipe' => $recipe->serializeRecipe(),
        ]);
    }

    public function store(StoreRecipeRequest $request)
    {
        $this->authorize('create', Recipe::class);

        Recipe::create($this->getRecipeDataFromRequest($request));

        return Redirect::route('recipes.index');
    }

    public function update(UpdateRecipeRequest $request, Recipe $recipe)
    {
        $this->authorize('edit', $recipe);

        

        $recipe->update($this->getRecipeDataFromRequest($request));

        return Inertia::render('Recipes/Create', [
            'recipe' => $recipe->serializeRecipe(),
        ]);
    }

    private function getRecipeDataFromRequest(Request $request): array
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'ingredients' => 'required|string',
            'steps' => 'required|string',
            'cuisine_type' => 'nullable',
            'image' => 'nullable|image|max:2048',
        ]);

        $data['user_id'] = $request->user()->getId();
        $data['ingredients'] = json_encode(array_filter(array_map('trim', explode("\n", $request->input('ingredients')))));
        $data['steps'] = json_encode(array_filter(array_map('trim', explode("\n", $request->input('steps')))));

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('recipes', 'public');
            $data['image'] = $path;
        }

        return $data;
    }

    public function destroy(Recipe $recipe)
    {
        $this->authorize('delete', $recipe);

        $recipe->delete();

        return Inertia::location(route('recipes.index'));
    }
}
