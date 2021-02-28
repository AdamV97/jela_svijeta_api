<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MealCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $filter = $request->request->get('with');

        $data = [];

        foreach ($this->collection as $item) {
            $singleMeal = [];
            $singleMeal['id'] = $item->id;

            $singleMeal['title'] = $item->mealTranslations[0]->translation;
            $singleMeal['description'] = $item->mealTranslations[0]->description;
            if (isset($item->deleted_at)) {
                $singleMeal['status'] = 'deleted';
            } else if (isset($item->updated_at)) {
                $singleMeal['status'] = 'updated';
            } else if (isset($item->created_at)) {
                $singleMeal['status'] = 'created';
            };

            // Check if category is needed
            if (!is_null($filter) && str_contains(strtolower($filter), 'category')) {
                if ($item->category_id !== null) {
                    $singleMeal['category'] = [
                        'id' => $item->category->id,
                        'title' => $item->category->categoryTranslations[0]->translation,
                        'slug' => $item->category->slug,
                    ];
                } else {
                    $singleMeal['category'] = null;
                }
            }

            // Check if tag is needed
            if (!is_null($filter) && str_contains(strtolower($filter), 'tags')) {
                $singleMeal['tags'] = $this->parseArray($item->tags, 'tagsTranslations');
            }

            // Check if ingredient is needed
            if (!is_null($filter) && str_contains(strtolower($filter), 'ingredients')) {
                $singleMeal['ingredients'] = $this->parseArray($item->ingredientsOnMeal, 'ingredientsTranslations');
            }

            array_push($data, $singleMeal);
        }
        return $data;
    }

    /**
     * Parsing array to correct structure
     *
     * @param Array $array
     * @param string $string
     * @return Array
     */
    private function parseArray($array, $item)
    {
        $mainArray = [];

        for ($i = 0; $i < count($array); $i++) {
            $object = [
                'id' => $array[$i]->id,
                'title' => $array[$i][$item][0]->translation,
                'slug' => $array[$i]->slug,
            ];
            array_push($mainArray, $object);
        }
        return $mainArray;
    }
}
