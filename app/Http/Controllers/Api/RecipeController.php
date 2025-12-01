<?php

namespace App\Http\Controllers\Api;

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

/**
 * @OA\Info(
 *     title="RecipeBook API",
 *     version="1.0.0"
 * )
 *
 * @OA\Tag(
 *     name="Search",
 *     description="Endpoints for searching recipes"
 * )
 *
 * @OA\PathItem(
 *     path="/api/recipes"
 * )
 */
class RecipeController extends Controller
{
    const DEFAULT_PAGINATION_LIMIT = 10;

    /**
     * @OA\Get(
     *     path="/api/recipes",
     *     summary="List recipes",
     *     tags={"Recipes"},
     *     @OA\Response(response=200, description="List of recipes")
     * )
     */
    public function index(Request $request)
    {
        return response()->json($this->getRecipesByRequest($request));
    }

    /**
     * @OA\Get(
     *     path="/api/recipes/search",
     *     summary="Search recipes",
     *     tags={"Recipes"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search string",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="cuisine_type",
     *         in="query",
     *         description="Cuisine type",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Search results")
     * )
     */
    public function search(Request $request)
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
        $recipes = $query->skip($offset)->take(self::DEFAULT_PAGINATION_LIMIT)->get();

        return [
            'data' => $recipes->map->serializeRecipe(),
            'next_page' => ($offset + $recipes->count() < $total) ? $page + 1 : null,
            'current_page' => $page,
            'total' => $total,
        ];
    }

    /**
     * @OA\Post(
     *     path="/api/recipes",
     *     summary="Create recipe",
     *     tags={"Recipes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","ingredients","steps"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="ingredients", type="string"),
     *             @OA\Property(property="steps", type="string"),
     *             @OA\Property(property="cuisine_type", type="string"),
     *         )
     *     ),
     *     @OA\Response(response=201, description="Created")
     * )
     */
    public function store(StoreRecipeRequest $request)
    {
        $data = $this->extractRecipeData($request);
        $recipe = Recipe::create($data);

        return response()->json($recipe->serializeRecipe(), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/recipes/{id}",
     *     summary="Show recipe",
     *     tags={"Recipes"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Recipe details")
     * )
     */
    public function show(Recipe $recipe)
    {
        $user = auth()->user();

        if ($recipe->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json([
                'message' => 'Forbidden'
            ], 403);
        }

        return response()->json($recipe->serializeRecipe());
    }

    /**
     * @OA\Put(
     *     path="/api/recipes/{id}",
     *     summary="Update recipe",
     *     tags={"Recipes"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="ingredients", type="string"),
     *             @OA\Property(property="steps", type="string"),
     *             @OA\Property(property="cuisine_type", type="string"),
     *         )
     *     ),
     *     @OA\Response(response=200, description="Updated")
     * )
     */
    public function update(UpdateRecipeRequest $request, Recipe $recipe)
    {
        $user = auth()->user();

        if ($recipe->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json([
                'message' => 'Forbidden'
            ], 403);
        }

        $data = $this->extractRecipeData($request);
        $recipe->update($data);

        return response()->json($recipe->serializeRecipe());
    }

    /**
     * @OA\Delete(
     *     path="/api/recipes/{id}",
     *     summary="Delete recipe",
     *     tags={"Recipes"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Deleted")
     * )
     */
    public function destroy(Recipe $recipe)
    {
        $user = auth()->user();

        if ($recipe->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json([
                'message' => 'Forbidden'
            ], 403);
        }
        
        $recipe->delete();
        return response()->json(null, 204);
    }

    private function extractRecipeData(Request $request): array
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'ingredients' => 'required|string',
            'steps' => 'required|string',
            'cuisine_type' => 'nullable',
            'image' => 'nullable|image|max:2048',
        ]);

        $data['user_id'] = $request->user()->id;

        $data['ingredients'] = json_encode(array_map('trim', explode("\n", $data['ingredients'])));
        $data['steps']       = json_encode(array_map('trim', explode("\n", $data['steps'])));

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('recipes', 'public');
        }

        return $data;
    }
}
