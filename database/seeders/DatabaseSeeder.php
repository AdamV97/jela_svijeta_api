<?php

namespace Database\Seeders;

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    private $faker;

    public function run(Faker $faker)
    {
        $this->faker = $faker;

        $this->call([
            LanguageSeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
            IngredientSeeder::class,
            MealSeeder::class,
        ]);
    }
}
