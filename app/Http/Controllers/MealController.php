<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestValidation;
use App\Http\Resources\MealCollection;
use App\Library\Helpers\FilterDataRestHelper;
use App\Library\Helpers\MealQueryBuilderHelper;
use App\Library\Helpers\MetaRestHelper;
use App\Models\Meal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class MealController extends Controller
{
    public function index(RequestValidation $request)
    {
        // Build query and retrive data from query
        $queryData = $this->buildQuery($request);

        // Build response
        $response = $this->buildResponse($request, $queryData);

        return response()->json($response);
    }

    public function deleteMeal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mealId' => 'numeric',
        ]);

        // Response if validator fails
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }

        $meal = Meal::find($request->get('mealId'));
        if ($meal === null) {
            return response()->json('Meal with that ID doesn\'t exist!');
        } else {
            // TODO: add response if delete is sucess or fail
            $meal->delete();
        }

    }

    /**
     * Generate links
     *
     * @param Object $data
     * @return string
     */
    private function parseLinks($data)
    {
        $data = json_decode($data->toJson());
        $links = [
            'prev' => $data->prev_page_url,
            'next' => $data->next_page_url,
            'self' => URL::full(),
        ];

        return $links;
    }

    /**
     * Building query
     *
     * @param Request $request
     * @return Array
     */
    private function buildQuery(Request $request)
    {
        // Parse tags
        $tags = FilterDataRestHelper::parseTags($request);

        // Get correct language ID from iso
        $languageId = FilterDataRestHelper::getRequestLanguageId($request);

        // Parse category
        $category = FilterDataRestHelper::parseCategory($request);

        // Start Meal query
        $mealQry = Meal::query();

        // Add translations for meal
        $mealQry->with([
            'mealTranslations' => function ($query) use ($languageId) {
                $query->where('meals_translations.language_id', '=', $languageId->id);
            },
        ]);

        //Build category query
        $mealQry = MealQueryBuilderHelper::buildCategoryQuery($mealQry, $category, $request->get('with'), $languageId);

        //Build ingredients query
        $mealQry = MealQueryBuilderHelper::buildIngredientQuery($mealQry, $request->get('with'), $languageId);

        //Build tag query
        $mealQry = MealQueryBuilderHelper::buildTagQuery($mealQry, $tags, $languageId);

        //Build diff time query
        $mealQry = MealQueryBuilderHelper::buildDiffTimeQuery($request, $mealQry);

        $numberResults = $request->get('per_page') === null ? Meal::all()->count() : $request->get('per_page');

        $mealData = $mealQry->simplePaginate((int) $numberResults)
            ->appends(
                request()->query()
            );

        return $mealData;
    }

    /**
     * Building response
     *
     * @param String $request
     * @param Object $queryData
     * @return Array
     */
    private function buildResponse(Request $request, $queryData)
    {
        // Create MetaData
        $metaData = new MetaRestHelper($request);

        $response['meta'] = [
            "currentPage" => $metaData->getCurrentPage(),
            "totalItems" => $metaData->getTotalItems(),
            "itemsPerPage" => is_numeric($metaData->getItemsPerPage()) ? $metaData->getItemsPerPage() : count($queryData),
            "filtered_data" => count($queryData),
        ];

        $response['data'] = new MealCollection($queryData);

        $response['links'] = $this->parseLinks($queryData);

        return $response;
    }

}
