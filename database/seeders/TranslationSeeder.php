<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    /**
     * Use given data to insert translation
     */
    public function insertTranslation(Int $id, String $mainColumn, String $table, String $en, String $cro, String $desc_cro=null, String $desc_en=null): void {
        if($mainColumn === 'meal_id'){
            $arrayCro = [
                $mainColumn => $id,
                'language_id' => 1,
                'translation' => $cro,
                'description' => $desc_cro
            ];
            $arrayEn = [
                $mainColumn => $id,
                'language_id' => 2,
                'translation' => $en,
                'description' => $desc_en
            ];
        }else{
            $arrayCro = [
                $mainColumn => $id,
                'language_id' => 1,
                'translation' => $cro
            ];
            $arrayEn = [
                $mainColumn => $id,
                'language_id' => 2,
                'translation' => $en
            ];
        }

        DB::table($table)->insert($arrayCro);
        DB::table($table)->insert($arrayEn);
    }
}
