<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Recipe;
use App\Models\User;

class RecipeFactory extends Factory
{
    protected $model = Recipe::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),                    
            'description' => $this->faker->paragraph(),             
            'cuisine_type' => $this->faker->randomElement([
                'Italian', 'Chinese', 'Mexican', 'Indian', 'French'
            ]),
            'ingredients' => json_encode($this->faker->words(5)),
            'steps' => json_encode($this->faker->sentences(3)),
            'image' => null,                                       
            'user_id' => User::factory(),                          
        ];
    }
}
