<?php

namespace Kyrax324\Queriplex;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class Queriplex
{

    /**
     * The listing array of filtering rules.
     *
     * @var Array
     */
    public $filterRules = [];

    /**
     * The listing array of sorting rules.
     *
     * @var Array
     */
    public $sortRules = [];

    /**
     * The related array of filtering rules.
     *
     * @var Array
     */
    public $filterables = [];

    /**
     * The related sorting rule.
     *
     * @var string|null
     */
    public $sortable = null;

    /**
     * The query builder of a eloquent model.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    public $query;

    /**
     * Get related array of filtering rules.
     *
     * @return Array
     */
    public function getFilterables()
    {
        return $this->filterables;
    }

    /**
     * Get related sorting rule.
     *
     * @return string|null
     */
    public function getSortable()
    {
        return $this->sortable;
    }

    /**
     * Set the query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return $this
     */
    public function make(Builder $query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Set the filterables rules.
     *
     * @param  Array  $request
     * @param  Array  $filterRules
     * @return $this
     */
    public function withFilters(Array $request, Array $filterRules)
    {   
        $this->filterRules = $filterRules;
        $this->filterables = array_intersect_key($request,$filterRules);
        return $this;
    }

    /**
     * Set the sortable rules.
     *
     * @param  string|null  $key
     * @param  Array  $filterRules
     * @return $this
     */
    public function withSort($key, Array $sortRules)
    {
        $this->sortRules = $sortRules;
        $this->sortable = $sortRules[$key] ?? null;
        return $this;
    }

    /**
     * Apply the queriplex logic to query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply()
    {
        $query = $this->query;

        $query = self::applyFilter($query);
        $query = self::applySort($query);

        return $query;
    }

    /**
     * Apply the related filter rules to query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyFilter(Builder $query)
    {
        $filterRules = $this->filterRules;
        $filterables = collect($this->filterables);

        $filterables->each( function($item, $key) use ($query, $filterRules){
            $rule_instruction = $filterRules[$key];
            if($rule_instruction instanceof Closure){
                $rule_instruction($query,$item);
            }else{
                $query->where($rule_instruction,$item);
            }
        });

        return $query;
    }

    /**
     * Apply the related sort rule to query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applySort(Builder $query)
    {
        $sortable = $this->sortable;
        $sortable($query);

        return $query;
    }
}
