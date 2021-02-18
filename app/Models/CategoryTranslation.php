<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    use HasFactory;
    public $table = 'categories_translations';

    public function categoryTranslation() {
        return $this->belongsTo(Category::class);
    }
}
