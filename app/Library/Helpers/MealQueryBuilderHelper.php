<?php

namespace App\Library\Helpers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MealQueryBuilderHelper
{
    /**
     * Helper function for building category query
     */
    public static function  buildDiffTimeQuery(Request $request, Builder $builder) {

        if($request->get('diff_time') !== null){
            $timeDate = Carbon::createFromTimestamp($request->get('diff_time'))->toDateTimeString();

            $builder->where('meals.created_at', '>', $timeDate);
            $builder->orWhere('meals.updated_at', '>', $timeDate);
            return $builder;
        } else {
            return $builder;
        };
    }

    /**
     * Helper function for building category query
     */
    public static function buildCategoryQuery(Builder $builder, $category) {

        if($category === 'null'){
            $builder->where('category_id',null);
        }else if($category === '!null'){
            $builder->whereNotNull('category_id');
        }else if($category !== 'all' && count($category) > 0){
            foreach ($category as $tstId) {
                $builder->orWhere('category_id', $tstId);
            }
        }

        return $builder;

    }

    /**
     * Helper function for building tag query
     */
    public static function buildTagQuery(Builder $builder, $tags) {

        foreach ($tags as $tstId) {
            $builder->whereHas('tags',function($query) use ($tstId) {
                $query->where('tags.id',$tstId);
            });
        }

        return $builder;

    }
}
