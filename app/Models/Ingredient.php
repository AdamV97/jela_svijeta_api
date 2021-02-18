<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    private $slug;

    use HasFactory;

    public function meal(){
        return $this->hasManyThrough(IngredientMeal::class, Meal::class);
    }

    public function ingredientsTranslations() {
        return $this->hasMany(IngredientTranslation::class);
    }

    /**
     * // Todo maybe over mutators -> We are instantiating an object in seeders
     */
    public function getSlug() {
        return $this->slug;
    }

    public function setSlug(String $slug): void {
        $this->slug = $slug;
    }
}
