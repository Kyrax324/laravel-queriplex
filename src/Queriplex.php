<?php

namespace Kyrax324\Queriplex;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class Queriplex
{   
    /**
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  array $data
     * @param  array $allowed_filters
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    static public function filter(Builder $query, array $data, array $allowed_filters)
    {   
        $valid_filters = collect(
            array_intersect_key($data,$allowed_filters)
        );

        if( $valid_filters->isNotEmpty() ){
            $valid_filters->each( function($item, $key) use ($query, $allowed_filters){
                $rule_instruction = $allowed_filters[$key];
                if($rule_instruction instanceof Closure){
                    $rule_instruction($query,$item);
                }else{
                    $query->where($rule_instruction,$item);
                }
            });
        }

        return $query;
    }
}
