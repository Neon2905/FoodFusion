<?php

namespace App\Policies;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RecipePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true; // Anyone can view recipes
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Recipe $recipe): bool
    {
        // Public recipes can be viewed by anyone
        if ($recipe->visibility === 'public' && $recipe->moderation_status === 'approved') {
            return true;
        }

        // Only the owner can view private/pending recipes
        return $user && $user->id === $recipe->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only creators and admins can create recipes
        return $user->is_creator || $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Recipe $recipe): bool
    {
        // Only the owner or admin can update
        return $user->id === $recipe->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Recipe $recipe): bool
    {
        // Only the owner or admin can delete
        return $user->id === $recipe->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Recipe $recipe): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Recipe $recipe): bool
    {
        return false;
    }
}
