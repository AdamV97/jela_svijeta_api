<?php

namespace App\Http\Requests;

use App\Rules\CategoryParser;
use App\Rules\TagParser;
use Illuminate\Foundation\Http\FormRequest;

class RequestValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // TODO: Create json response?
        return [
            // Za lang isto custom parser u kojem će biti error response!
            'lang' => 'string|required',
            'page' => 'numeric',
            'per_page' => 'numeric',
            'category'=> ['string', 'nullable', new CategoryParser],
            'tags'=> ['string', new TagParser],
            'with'=> 'string',
            'diff_time' => 'numeric'
        ];
    }
}
