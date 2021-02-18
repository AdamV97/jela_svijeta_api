<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->insertIngredients('Milk', 'Mlijeko');
        $this->insertIngredients('Sour Cream', 'Vrhnje');
        $this->insertIngredients('Sugar', 'Šećer');
        $this->insertIngredients('Water', 'Voda');
        $this->insertIngredients('Orange', 'Narandža');
        $this->insertIngredients('Flour', 'Brašno');
    }

    private function insertIngredients(String $en, String $cro): void {
        $ingredients = new Ingredient();
        $ingredients->slug = strtolower(str_replace(' ', '_', $en));
        $ingredients->created_at = Carbon::now();
        $ingredients->updated_at = Carbon::now();
        $ingredients->save();

        $lastId = $ingredients->id;
        $translations = new TranslationSeeder();
        $translations->insertTranslation($lastId,'ingredient_id' ,'ingredients_translations', $en, $cro);
    }
}
