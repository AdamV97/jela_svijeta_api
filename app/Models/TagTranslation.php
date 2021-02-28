<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagTranslation extends Model
{
    use HasFactory;
    public $table = 'tags_translations';

    public function tagsTranslation()
    {
        return $this->belongsTo(Tag::class, 'id', 'tag_id');
    }
}
