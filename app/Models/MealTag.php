<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealTag extends Model
{
    private $tag_id;
    private $meal_id;
    public $table = 'meals_tags';

    use HasFactory;

    public function tags()
    {
        return $this->belongsTo(Tag::class, 'id', 'tag_id');
    }

    public function meal()
    {
        return $this->belongsTo(Meal::class, 'id', 'meal_id');
    }

    /**
     * // Todo maybe over mutators -> We are instantiating an object in seeders
     */
    public function getTagsId()
    {
        return $this->tag_id;
    }

    public function setTagsId(String $tag_id): void
    {
        $this->tag_id = $tag_id;
    }

    /**
     * // Todo maybe over mutators -> We are instantiating an object in seeders
     */
    public function getMealsId()
    {
        return $this->meal_id;
    }

    public function setMealsId(String $meal_id): void
    {
        $this->meal_id = $meal_id;
    }
}
