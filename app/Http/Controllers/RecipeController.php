<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Recipe;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class RecipeController extends Controller
{
    use AuthorizesRequests;

    const DEFAULT_PAGINATION_LIMIT = 10;
    
    public function index(Request $request)
    {
        $cuisines = Recipe::select('cuisine_type')->distinct()->pluck('cuisine_type');

        return Inertia::render('Recipes/Index', [
            'recipes' => $this->getRecipesByRequest($request),
            'cuisines' => $cuisines,
        ]);
    }

    public function recipesSearch(Request $request)
    {
        return response()->json($this->getRecipesByRequest($request));
    }

    private function getRecipesByRequest(Request $request)
    {
        if ($request->user()->isAdmin()) {
            $query = Recipe::with('user')->orderBy('created_at', 'desc');
        } else {
            $query = Recipe::with('user')->where('user_id', $request->user()->getId())->orderBy('created_at', 'desc');
        }

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
            ->get()
            ->map->serializeRecipe();

        $nextPage = ($offset + $recipes->count() < $total) ? $page + 1 : null;

        return [
            'data' => $recipes,
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

    public function store(Request $request)
    {
        $this->authorize('create', Recipe::class);

        $data = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'ingredients' => 'array',
            'steps' => 'array',
            'cuisine_type' => 'nullable',
            'image' => 'nullable|image|max:2048',
        ]);

        $data['user_id'] = $request->user()->getId();
        $data['ingredients'] = json_encode($data['ingredients']);
        $data['steps'] = json_encode($data['steps']);

        Recipe::create($data);

        return Redirect::route('recipes.index');
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

    public function update(Request $request, Recipe $recipe)
    {
        $this->authorize('edit', $recipe);

        $data = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'ingredients' => 'array',
            'steps' => 'array',
            'cuisine_type' => 'nullable',
            'image' => 'nullable|image|max:2048',
        ]);

        $recipe->update($data);

        return Inertia::render('Recipes/Create', [
            'recipe' => $recipe,
        ]);
    }

    public function destroy(Recipe $recipe)
    {
        $this->authorize('delete', $recipe);

        $recipe->delete();

        return Redirect::route('recipes.index');
    }
}
