<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Recipe;

class RecipePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
       
    }
    
    public function create(User $user)
    {
        return $this->isUserCanCreate($user);
    } 

    public function show(User $user)
    {
        return $user->hasVerifiedEmail();
    } 
    
    public function edit(User $user, Recipe $recipe)
    {
        return $this->isUserCanModify($user, $recipe);
    }  

    public function delete(User $user, Recipe $recipe)
    {
        return $this->isUserCanModify($user, $recipe);
    }

    private function isUserCanModify(User $user, Recipe $recipe)
    {
        return ($user->id === $recipe->user_id || $user->isAdmin()) && $user->hasVerifiedEmail();
    }

    private function isUserCanCreate(User $user)
    {
        return $user->hasVerifiedEmail();
    }
}
