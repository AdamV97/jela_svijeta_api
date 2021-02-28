<?php

namespace Database\Seeders;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->insertCategories('Fast Food', 'Brza Hrana');
        $this->insertCategories('Barbecue', 'Roštilj');
        $this->insertCategories('Dessert', 'Desert');
        $this->insertCategories('Icecream', 'Sladoled');
        $this->insertCategories('Soup', 'Juha');
    }

    private function insertCategories(String $en, String $cro): void
    {
        $category = new Category();
        $category->slug = strtolower(str_replace(' ', '_', $en));
        $category->created_at = Carbon::now();
        $category->updated_at = Carbon::now();
        $category->save();

        $lastId = $category->id;
        $translations = new TranslationSeeder();
        $translations->insertTranslation($lastId, 'category_id', 'categories_translations', $en, $cro);
    }
}
