<?php

namespace Database\Seeders;

use App\Models\Language;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $this->insertLanguage('hr', 'Croatian', 'Hrvatski');
       $this->insertLanguage('en', 'English', 'English');
    }

    /**
     * Insert given data to languages helper funcion
     */
    private function insertLanguage(String $label, String $name, String $local): void {
        $language = new Language();
        $language->iso_label = $label;
        $language->name = $name;
        $language->local = $local;
        $language->created_at = Carbon::now();
        $language->updated_at = Carbon::now();
        $language->save();
    }
}
