<?php

namespace Database\Seeders;

use App\Models\Tag;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    private $faker;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $this->insertTags('Rare', 'Rijetko', $faker->realText(10));
        $this->insertTags('Expensive', 'Skupo', $faker->realText(10));
        $this->insertTags('Tasty', 'Ukusno', $faker->realText(10));
        $this->insertTags('Hot', 'Vruće', $faker->realText(10));
        $this->insertTags('Dinner', 'Večera', $faker->realText(10));
        $this->insertTags('Spicey', 'Začinjeno', $faker->realText(10));
    }

    private function insertTags(String $en, String $cro, String $slug): void
    {
        $category = new Tag();
        $category->slug = strtolower(str_replace(' ', '_', $en) . $slug);
        $category->created_at = Carbon::now();
        $category->updated_at = Carbon::now();
        $category->save();

        $lastId = $category->id;
        $translations = new TranslationSeeder();
        $translations->insertTranslation($lastId, 'tag_id', 'tags_translations', $en, $cro);
    }
}
