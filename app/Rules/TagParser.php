<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TagParser implements Rule
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
        if(strlen($value) >= 1){
            $request = explode(',', $value);
            for($i = 0; $i < count($request); $i++){
                if(is_numeric($request[$i])){
                    return true;
                }else{
                    return false;
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
        return 'The :Tags parameter is not valid!';
    }
}
