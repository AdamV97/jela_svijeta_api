<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    private $slug;

    use HasFactory;

    public function meal()
    {
        return $this->belongsToMany(Meal::class, 'tags_on_dish');
    }

    public function tagsTranslations()
    {
        return $this->hasMany(TagTranslation::class);
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
