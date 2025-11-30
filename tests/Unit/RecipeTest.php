<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Recipe;
use App\Models\User;

class RecipeTest extends TestCase
{
    public function test_recipe_can_store_ingredients_and_steps_as_array()
    {
        $recipe = new Recipe([
            'name' => 'Test Recipe',
            'ingredients' => ['egg', 'flour'],
            'steps' => ['mix', 'bake'],
        ]);

        $this->assertIsArray($recipe->ingredients);
        $this->assertIsArray($recipe->steps);
        $this->assertCount(2, $recipe->ingredients);
        $this->assertEquals('mix', $recipe->steps[0]);
    }

    public function test_recipe_has_default_image()
    {
        $recipe = new Recipe(['name' => 'No Image Recipe']);
        $this->assertNull($recipe->image);

        $this->assertEquals(Recipe::DEFAULT_IMG_URL, $recipe->getImageUrl());
    }

    public function test_recipe_belongs_to_user()
    {
        $user = User::factory()->make();
        $recipe = new Recipe(['name' => 'User Recipe']);
        $recipe->user()->associate($user);

        $this->assertInstanceOf(User::class, $recipe->user);
    }

    public function test_recipe_name_is_required()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Recipe::create([
            'description' => 'No name provided',
            'ingredients' => ['egg'],
            'steps' => ['mix'],
        ]);
    }

    public function test_ingredients_and_steps_are_mutators_working()
    {
        $recipe = new Recipe([
            'name' => 'Mutator Test',
            'ingredients' => ['milk', 'sugar'],
            'steps' => ['stir', 'boil'],
        ]);

        $this->assertEquals(['milk', 'sugar'], $recipe->ingredients);
        $this->assertEquals(['stir', 'boil'], $recipe->steps);
    }

    public function test_recipe_returns_array_for_empty_ingredients_and_steps()
    {
        $recipe = new Recipe(['name' => 'Empty Arrays Test']);

        $this->assertEmpty($recipe->ingredients);
        $this->assertEmpty($recipe->steps);
    }
}
