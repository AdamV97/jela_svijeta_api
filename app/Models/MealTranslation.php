<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealTranslation extends Model
{
    use HasFactory;
    public $table = 'meals_translations';

    public function meal()
    {
        return $this->belongsTo(Meal::class, 'id', 'meal_id');
    }
}
