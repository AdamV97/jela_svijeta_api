<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientMeal extends Model
{
    private $ingredient_id;
    private $meal_id;
    public $table = 'ingredients_meals';
    use HasFactory;

    /**
     * // Todo maybe over mutators -> We are instantiating an object in seeders
     */
    public function getIngredientsId()
    {
        return $this->ingredient_id;
    }

    public function setIngredientsId(String $ingredient_id): void
    {
        $this->ingredient_id = $ingredient_id;
    }

    /**
     * // Todo maybe over mutators -> We are instantiating an object in seeders
     */
    public function getMealId()
    {
        return $this->meal_id;
    }

    public function setMealId(String $meal_id): void
    {
        $this->meal_id = $meal_id;
    }
}
