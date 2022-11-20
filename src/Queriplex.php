<?php

namespace Kyrax324\Queriplex;

use Closure;
use Exception;
use Illuminate\Database\Eloquent\Builder;

class Queriplex
{
	public $sortingKey = "sortBy";

	public $selectedFilterRules;
	public $selectedSortRules;

	protected $queryBuilder;
	protected $input = [];
    protected $extraPayload;

	public function __construct(Builder $queryBuilder, Array $input, $extraPayload = null)
	{
    	if($this->sortingKey == null ){
    		throw new Exception("Variable 'sortingKey' cannot be null", 400);
    	}

		$this->queryBuilder = $queryBuilder;
		$this->input = $input;
        $this->extraPayload = $extraPayload;
	}

    /**
     * Apply the queriplex logic to query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
	public static function make(Builder $queryBuilder, Array $input) : Builder
	{
		$queriplex = new static($queryBuilder, $input);
        $query = $queriplex->applyFilter();

        if($queriplex->getInput($queriplex->sortingKey)){
            $query = $queriplex->applySort();
        }
		return $query;
	}

    /**
     * Apply the queriplex logic for debuging.
     *
     * @return Queriplex
     */
	public static function debug(Builder $queryBuilder, Array $input)
	{
		$queriplex = new static($queryBuilder, $input);
        $queriplex->applyFilter();

        if($queriplex->getInput($queriplex->sortingKey)){
            $queriplex->applySort();
        }
		return $queriplex;
	}

	public function filterRules()
	{
		return [];
	}

	public function sortRules()
	{
		return [];
	}

	protected function getInput($key)
	{
		return $this->input[$key] ?? null;
	}

    /**
     * Apply the related filter rules to query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyFilter() : Builder
    {
    	$query = $this->queryBuilder;
        $filterRules = $this->filterRules();
        $filterables = collect(array_intersect_key($this->input, $filterRules));
        $this->selectedFilterRules = $filterables;

        $filterables->each(function($item, $key) use ($query, $filterRules){
            $rule_instruction = $filterRules[$key];
            if($rule_instruction instanceof Closure){
                $rule_instruction($query, $item);
            }else{
                $query->where($rule_instruction, $item);
            }
        });

        return $query;
    }

    /**
     * Apply the related sort rule to query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applySort() : Builder
    {
		$query = $this->queryBuilder;

        $sortRules = $this->sortRules();

        $key = $this->getInput($this->sortingKey);

        if( !in_array($key, array_keys($sortRules) )){
    		throw new Exception("Sorting Rule '{$key}' is not set in sortRules()", 400);
        }

        $sortable = $sortRules[$key];
        $this->selectedSortRules = $sortable;

        if($sortable instanceof Closure){
            $sortable($query);
        }else{
        	throw new Exception("Items returned in sortRules() methods must be a anonymous function.", 400);
        }

        return $query;
    }
}