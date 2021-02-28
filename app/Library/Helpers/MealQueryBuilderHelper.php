<?php

namespace App\Library\Helpers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MealQueryBuilderHelper
{
    /**
     * Helper function for building timestamp query
     * @param Request $request
     * @param Builder $builder
     * @return Builder
     */
    public static function buildDiffTimeQuery(Request $request, Builder $builder)
    {
        if ($request->get('diff_time') !== null) {
            $timeDate = Carbon::createFromTimestamp($request->get('diff_time'))->toDateTimeString();

            $builder->where(function ($query) use ($timeDate) {
                $query->where('meals.created_at', '>', $timeDate);
                $query->where('meals.created_at', '>', $timeDate);
                $query->orWhere('meals.deleted_at', '>', $timeDate);
            });
            return $builder->withTrashed();
        } else {
            return $builder;
        };
    }

    /**
     * Helper function for building category query
     * @param Builder $builder
     * @param $category
     * @param $withParam
     * @param $languageId
     * @return Builder
     */
    public static function buildCategoryQuery(Builder $builder, $category, $withParam, $languageId)
    {
        if ($category === 'null') {
            // $builder->where('category_id',null);
            $builder->where(function ($query) {
                $query->where('category_id', null);
            });
        } else if ($category === '!null') {
            $builder->where(function ($query) {
                $query->whereNotNull('category_id');
            });
        } else if ($category !== 'all' && count($category) > 0) {
            $builder->where(function ($query) use ($category) {
                foreach ($category as $tstId) {
                    $query->orWhere('category_id', $tstId);
                }
            });
        }

        if (!is_null($withParam) && str_contains(strtolower($withParam), 'category')) {
            $builder->with([
                'category.categoryTranslations' => function ($query) use ($languageId) {
                    $query->where('categories_translations.language_id', '=', $languageId->id);
                },
            ]);
        }

        return $builder;

    }

    /**
     * Helper function for building tag query
     * @param Builder $builder
     * @param $tags
     * @param $languageId
     * @return Builder
     */
    public static function buildTagQuery(Builder $builder, $tags, $languageId)
    {
        if ($tags !== null) {
            $builder->with([
                'tags.tagsTranslations' => function ($query) use ($languageId) {
                    $query->where('tags_translations.language_id', '=', $languageId->id);
                },
            ]);

            foreach ($tags as $tstId) {
                $builder->whereHas('tags', function ($query) use ($tstId) {
                    $query->where('tags.id', $tstId);
                });
            }
        }

        return $builder;

    }

    /**
     * Helper function for building tag query
     * @param Builder $builder
     * @param $withParam
     * @param $languageId
     * @return Builder
     */
    public static function buildIngredientQuery(Builder $builder, $withParam, $languageId)
    {

        if (!is_null($withParam) && str_contains(strtolower($withParam), 'ingredients')) {
            $builder->with([
                'ingredientsOnMeal.ingredientsTranslations' => function ($query) use ($languageId) {
                    $query->where('ingredients_translations.language_id', '=', $languageId->id);
                },
            ]);
        }

        return $builder;

    }
}
