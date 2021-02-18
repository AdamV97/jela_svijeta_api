<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\IngredientMeal;
use App\Models\Meal;
use App\Models\MealTag;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class MealSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    private $faker;

    public function run(Faker $faker)
    {
        $this->faker = $faker;

        //List of meals
        $meals= [
                [
                    'en' => 'Fried chicken',
                    'cro' => 'Pohana piletina',
                    'desc_cro' => 'Jednostavna pohana piletina'
                ],
                [
                    'en' => 'French fries',
                    'cro' => 'Pomfri',
                    'desc_cro' => 'Slani krumpirići'
                ],
                [
                    'en' => 'Chicken nuggets',
                    'cro' => 'Medaljoni',
                    'desc_cro' => 'Ukusni pileći medaljoni'
                ],
                [
                    'en' => 'Stick roast',
                    'cro' => 'Ražnjići',
                    'desc_cro' => 'Popularno jelo'
                ],
                [
                    'en' => 'Meat',
                    'cro' => 'Meso',
                    'desc_cro' => 'Svjetski poznata hrana'
                ],
                [
                    'en' => 'Lamb',
                    'cro' => 'Janjetina',
                    'desc_cro' => 'Najbolji komad mesa'
                ],
                [
                    'en' => 'Pancake',
                    'cro' => 'Palačinka',
                    'desc_cro' => 'Slatki desert'
                ],
                [
                    'en' => 'Cake',
                    'cro' => 'Torta',
                    'desc_cro' => 'Najbolja torta'
                ],
                [
                    'en' => 'Wafells',
                    'cro' => 'Vafl',
                    'desc_cro' => 'Mekani vafli'
                ],
                [
                    'en' => 'Chocolate',
                    'cro' => 'Čokolada',
                    'desc_cro' => 'Čokoladni okus'
                ],
                [
                    'en' => 'Vannila',
                    'cro' => 'Vanilija',
                    'desc_cro' => 'Okus Vanilije'
                ],
                [
                    'en' => 'Coconut',
                    'cro' => 'Kokos',
                    'desc_cro' => 'Okus kokosa'
                ],
                [
                    'en' => 'Mushrooms',
                    'cro' => 'Gljive',
                    'desc_cro' => 'Prilog'
                ],
                [
                    'en' => 'Vegetable',
                    'cro' => 'Povrće',
                    'desc_cro' => 'Svježe povrće'
                ],
                [
                    'en' => 'Chicken',
                    'cro' => 'Piletina',
                    'desc_cro' => 'Pečena piletina'
                ]
        ];

        // Get random number of meals to insert
        $numberOfDishes = $faker->numberBetween(8, count($meals)-1);

        for($i = 0; $i < $numberOfDishes; $i++){
            $rndCategory = $this->getRandomCategory();
            $randomMeal = $faker->numberBetween(1, count($meals)-1);
            $randomMeal = $meals[$randomMeal];

            $enTranslation = $randomMeal['en'];
            $croTranslation = $randomMeal['cro'];
            $enDescription = $faker->realText(20);
            $croDescription = $randomMeal['desc_cro'];

            $mealId = $this->insertMeal($rndCategory, $enTranslation, $croTranslation, $enDescription, $croDescription);
            $rndTags = $this->getRandomTags();
            $rndIngredients = $this->getRandomIngredients();

            $this->insertTagsOnMeal($mealId, $rndTags);

            $this->QueryinsertIngredientsOnMeal($mealId, $rndIngredients);
        }

    }

    /**
    * Insert new Meal and Translation
    * @param int || null $category_id
    * @param string $en
    * @param string $cro
    * @param string $enDescription
    * @param string $croDescription
    */
    private function insertMeal($category_id, String $en, String $cro, String $enDescription, String $croDescription) {
        $meal = new Meal();
        $meal->category_id = $category_id;
        $meal->created_at = Carbon::now();
        $meal->updated_at = Carbon::now();
        $meal->save();

        $lastId = $meal->id;
        $translations = new TranslationSeeder();
        $translations->insertTranslation($lastId, 'meal_id', 'meals_translations', $en, $cro, $croDescription, $enDescription);

        return $lastId;
    }

    /**
    * Insert array of tags on meal
    * @param int $meal_id
    * @param Array $tag_id
    */
    private function insertTagsOnMeal(Int $meal_id, Array $tag_id): void {

        for($i = 0; $i < count($tag_id); $i++){
            $tagsOnMeal = new MealTag();
            $tagsOnMeal->meal_id = $meal_id;
            $tagsOnMeal->tag_id = $tag_id[$i];
            $tagsOnMeal->created_at = Carbon::now();
            $tagsOnMeal->updated_at = Carbon::now();
            $tagsOnMeal->save();
        }

    }

    /**
    * Insert array of ingredients on meal
    * @param int $meal_id
    * @param Array $ingredient_id
    */
    private function QueryinsertIngredientsOnMeal(Int $meal_id, Array $ingredient_id): void {

        for($i = 0; $i < count($ingredient_id); $i++){
            $ingredientsOnMeal = new IngredientMeal();
            $ingredientsOnMeal->meal_id = $meal_id;
            $ingredientsOnMeal->ingredient_id = $ingredient_id[$i];
            $ingredientsOnMeal->created_at = Carbon::now();
            $ingredientsOnMeal->updated_at = Carbon::now();
            $ingredientsOnMeal->save();
        }

    }

    /**
    * Return random Category or null
    *
    * @return int || null
    */
    private function getRandomCategory(){
        $category = Category::all();
        $category = $this->faker->boolean(60) ? $this->faker->randomElement($category)->id : null;
        return $category;
    }

    /**
    * Return random Tags
    *
    * @return Array
    */
    private function getRandomTags(){
        $tag = Tag::all();
        $rndTags = [];

        $nTags = $this->faker->numberBetween(1, count($tag)-1);
        for($i = 0; $i < $nTags; $i++){
            $random = $this->faker->numberBetween(1, count($tag)-1);
            if(!in_array($random, $rndTags)){
                array_push($rndTags, $random);
            };
        }

        return $rndTags;
    }

    /**
    * Return random Ingredients
    *
    * @return Array
    */
    private function getRandomIngredients(){
        $ingredients = Ingredient::all();
        $rndIngredients = [];

        $nIngredients = $this->faker->numberBetween(1, count($ingredients)-1);
        for($i = 0; $i < $nIngredients; $i++){
            $random = $this->faker->numberBetween(1, count($ingredients)-1);
            if(!in_array($random, $rndIngredients)){
                array_push($rndIngredients, $random);
            };
        }

        return $rndIngredients;
    }
}
