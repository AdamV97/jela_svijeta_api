<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    private $slug;

    use HasFactory;

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    public function categoryTranslations()
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    /**
     * // Todo maybe over mutators -> We are instantiating an object in seeders
     */
    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug(String $slug): void
    {
        $this->slug = $slug;
    }
}
