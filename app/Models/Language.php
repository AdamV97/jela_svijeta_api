<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    private $iso_label;
    private $name;
    private $locale;

    use HasFactory;

    /**
     * // Todo maybe over mutators -> We are instantiating an object in seeders
     */
    public function getIsoLabel() {
        return $this->iso_label;
    }

    public function setIsoLabel(String $iso_label): void {
        $this->iso_label = $iso_label;
    }

    /**
     * // Todo maybe over mutators -> We are instantiating an object in seeders
     */
    public function getName() {
        return $this->name;
    }

    public function setName(String $name): void {
        $this->name = $name;
    }


    /**
     * // Todo maybe over mutators -> We are instantiating an object in seeders
     */
    public function getLocale() {
        return $this->locale;
    }

    public function setLocale(String $locale): void {
        $this->locale = $locale;
    }
}
