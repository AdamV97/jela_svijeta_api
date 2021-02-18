<?php

namespace App\Http\Controllers;

use App\Library\Helpers\MealQueryBuilderHelper;
use App\Library\Helpers\MetaRestHelper;
use App\Models\Language;
use App\Models\Meal;
use App\Rules\CategoryParser;
use App\Rules\TagParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class MealController extends Controller
{
    public function index(Request $request) {

        $validator = Validator::make($request->all(), [
            'lang' => 'string|required',
            'page' => 'numeric',
            'per_page' => 'numeric',
            'category'=> ['string', 'nullable', new CategoryParser],
            'tags'=> ['string', new TagParser],
            'with'=> 'string',
            'diff_time' => 'numeric'
        ]);

        // Response if validator fails
        if($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }

        // Parse tags
        $tags = $request->get('tags') !== null ? $this->parseTags($request->get('tags')) : null;

        // Create metaData
        $metaData = new MetaRestHelper($request);

        // Get correct language ID form iso
        $languageId = $this->getRequestLanguageId($request->get('lang'));

        // Start Meal query
        $mealQry = Meal::query();

        $withHlp = $request->get('with');

        $mealQry->with([
            'mealTranslations' => function ($query) use ($languageId) {
                $query->where('meals_translations.language_id', '=', $languageId->id);
            },
        ]);

        if(!is_null($withHlp) && str_contains(strtolower($withHlp), 'category')){
            $mealQry->with([
                'category.categoryTranslations' => function ($query) use ($languageId) {
                    $query->where('categories_translations.language_id', '=', $languageId->id);
                }
            ]);
        }

        if(!is_null($withHlp) && str_contains(strtolower($withHlp), 'ingredients')){
            $mealQry->with([
                'ingredientsOnMeal.ingredientsTranslations'=> function ($query) use ($languageId) {
                    $query->where('ingredients_translations.language_id', '=', $languageId->id);
                }
            ]);
        }

        $mealQry = MealQueryBuilderHelper::buildDiffTimeQuery($request, $mealQry);

        $category = $this->parseCategory($request->get('category'));

        $mealQry = MealQueryBuilderHelper::buildCategoryQuery($mealQry, $category);

        if($tags !== null){
            $mealQry = MealQueryBuilderHelper::buildTagQuery($mealQry, $tags);
        }

        $mealData = $mealQry->simplePaginate($request->get('per_page'))
            ->appends(
                request()->query()
            );

        $response['meta'] = [
            "currentPage" => $metaData->getCurrentPage(),
            "totalItems" => $metaData->getTotalItems(),
            "itemsPerPage" => is_numeric($metaData->getItemsPerPage()) ? $metaData->getItemsPerPage() : count($mealData),
            "filtered_data" => count($mealData)
        ];

        $response['data'] = $this->parseMeal($mealData, $request->get('with'));
        $response['links'] = $this->parseLinks($mealData);

        return response()->json($response);

    }

    /**
    * Get correct language ID
    *
    * @param String $lang
    * @return Object
    */
    private function getRequestLanguageId($lang){
        return Language::where('iso_label', $lang)->firstOrFail();
    }

    /**
    * Generate links
    *
    * @param Object $data
    * @return string
    */
    private function parseLinks($data){
        $links = (object)array();
        $data = json_decode($data->toJson());
        $links->prev = $data->prev_page_url;
        $links->next = $data->next_page_url;
        $links->self = URL::full();

        return $links;
    }


    /**
    * Parsing array to correct structure
    *
    * @param Object $meal
    * @param String $with
    * @return Object
    */
    private function parseMeal($meal, $with){

        $withParsed = $with !== null ? explode(',', strtolower($with)) : null;

        $data = [];
        foreach($meal as $item){
            $singleMeal = (object)array();
            $singleMeal->id = $item->id;

            $singleMeal->title = $item->mealTranslations[0]->translation;
            $singleMeal->description = $item->mealTranslations[0]->description;
            if(isset($item->deleted_at)){
                $singleMeal->status = 'deleted';
            }else if(isset($item->updated_at)){
                $singleMeal->status = 'updated';
            }else if(isset($item->created_at)){
                $singleMeal->status = 'created';
            };

            // Check if with contains category
            if(!is_null($withParsed) && in_array('category', $withParsed)){
                if($item->category === null){
                    $singleMeal->category = $item->category;
                }else{
                    $singleMeal->category = (object)array();
                    $singleMeal->category->id = $item->category->id;
                    $singleMeal->category->title = $item->category->categoryTranslations[0]->translation;
                    $singleMeal->category->slug = $item->category->slug;
                }
            }

            // Check if with contains tags
            if(!is_null($withParsed) && in_array('tags', $withParsed)){
                $singleMeal->tags = $this->parseArray($item->tags);
            }

            // Check if with contains ingredients
            if(!is_null($withParsed) &&  in_array('ingredients', $withParsed)){
                $singleMeal->ingredients = $this->parseArray($item->ingredientsOnMeal);
            }

            array_push($data, $singleMeal);
        }

        return $data;
    }

    /**
    * Parsing array to correct structure
    *
    * @param Array $array
    * @return Array
    */
    private function parseArray($array){
        $mainArray = [];

        for($i = 0; $i < count($array); $i++){
            $object = (object)array();
            $object->id = $array[$i]->id;
            $object->title = isset($array[$i]->tagsTranslations) ? $array[$i]->tagsTranslations[0]->translation : $array[$i]->ingredientsTranslations[0]->translation;
            $object->slug = $array[$i]->slug;

            array_push($mainArray, $object);
        }

        return $mainArray;
    }
    /**
    * Parsing array to correct structure
    * Should refactor this part
    * @param String $requestCategory
    * @return Array | String
    */
    private function parseCategory($requestCategory){
        $mainArray = [];
        if($requestCategory === null){
            return 'all';
        }else if(strtolower($requestCategory) === 'null'){
            return 'null';
        }else if(strtolower($requestCategory) === '!null'){
            return '!null';
        }else if(strlen($requestCategory) >= 1){
            $request = explode(',', $requestCategory);
            for($i = 0; $i < count($request); $i++){
                is_numeric($request[$i]) ? array_push($mainArray, $request[$i]) : null;
            }
        }else{
            return false;
        }
        return $mainArray;
    }

    /**
    * Parsing array to correct structure
    *
    * @param String $request
    * @return Array
    */
    private function parseTags($request){
        $mainArray = [];
        if(strlen($request) >= 1){
            $request = explode(',', $request);
            for($i = 0; $i < count($request); $i++){
                is_numeric($request[$i]) ? array_push($mainArray, $request[$i]) : null;
            }
        }else{
            return false;
        }
        return $mainArray;
    }
}
