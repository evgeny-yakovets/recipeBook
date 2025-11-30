<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecipeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_recipe()
    {
        $user = User::factory()->create([
            'role' => User::ROLE_USER,
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user);

        $response = $this->post(route('recipes.store'), [
            'name' => 'Chocolate Cake',
            'ingredients' => 'chocolate, 
            flour, 
            sugar',
            'steps' => 'Mix ingredients, 
            Bake for 30 minutes',
        ]);

        $response->assertRedirect(route('recipes.index'));
        $this->assertDatabaseHas('recipes', [
            'name' => 'Chocolate Cake',
            'ingredients' => "[\"chocolate,\",\"flour,\",\"sugar\"]",
            'steps' => "[\"Mix ingredients,\",\"Bake for 30 minutes\"]",
        ]);
    }
}
