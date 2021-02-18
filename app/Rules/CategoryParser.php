<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CategoryParser implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //Check if there is laravel help method to parse string null to false
        if($value === null){
            return true;
        }else if(strtolower($value) === 'null'){
            return true;
        }else if(strtolower($value) === '!null'){
            return true;
        }else if(strlen($value) >= 1){
            $request = explode(',', $value);
            if(count($request) > 1){
                foreach($request as $r){
                    if(!is_numeric($r)){
                        return false;
                    }
                }
            }
        }else{
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :Category parameter is not valid!';
    }
}
