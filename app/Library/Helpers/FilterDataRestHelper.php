<?php

namespace App\Library\Helpers;

use App\Models\Language;
use Illuminate\Http\Request;

class FilterDataRestHelper
{
    /**
     * Parsing Tags to correct structure
     *
     * @param Request $request
     * @return Array
     */
    public static function parseTags(Request $request)
    {
        $data = $request->get('tags');

        $mainArray = [];
        if (strlen($data) >= 1) {
            $data = explode(',', $data);
            for ($i = 0; $i < count($data); $i++) {
                is_numeric($data[$i]) ? array_push($mainArray, $data[$i]) : null;
            }
        } else {
            return null;
        }
        return $mainArray;
    }

    /**
     * Get correct language ID
     *
     * @param Request $request
     * @return Object
     */
    public static function getRequestLanguageId(Request $request)
    {
        $lang = $request->get('lang');

        return Language::where('iso_label', $lang)->firstOrFail();
    }

    /**
     * Parsing array to correct structure
     * Should refactor this part
     * @param Request $request
     * @return Array | String
     */
    public static function parseCategory(Request $request)
    {
        $requestCategory = $request->get('category');

        $mainArray = [];
        if ($requestCategory === null) {
            return 'all';
        } else if (strtolower($requestCategory) === 'null') {
            return 'null';
        } else if (strtolower($requestCategory) === '!null') {
            return '!null';
        } else if (strlen($requestCategory) >= 1) {
            $request = explode(',', $requestCategory);
            for ($i = 0; $i < count($request); $i++) {
                is_numeric($request[$i]) ? array_push($mainArray, $request[$i]) : null;
            }
        } else {
            return false;
        }
        return $mainArray;
    }

}
