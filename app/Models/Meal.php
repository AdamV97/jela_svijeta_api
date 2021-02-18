<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    private $categories_id;

    use HasFactory;

    public function category() {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function tagsOnMeal() {
        return $this->belongsToMany(Tag::class)->withPivot('tag_id');
    }

    public function tags() {
        return $this->belongsToMany(Tag::class, 'meals_tags');
    }

    public function ingredientsOnMeal() {
        return $this->belongsToMany(Ingredient::class, 'ingredients_meals');
    }

    public function mealTranslations(){
        return $this->hasMany(MealTranslation::class, 'meal_id', 'id');
    }
}
