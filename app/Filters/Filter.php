<?php

namespace KIPR\Filters;

use Illuminate\Http\Request;

abstract class Filter
{
    /**
     * $request - incomming HTTP Request
     * @var Request
     */
    protected $request;

    /**
     * $builder - DB Query Builder
     * @var Illuminate\Database\Query\Builder
     */
    protected $builder;
    /**
     * $filters - Array of enabled filters
     * @var Array
     */
    protected $filters = [];

    /**
     * Filter Constructor - Creates a new Filter
     * @param Request $request incomming request to filter from
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply - Runs each query filter on the given query builder
     * @param  lluminate\Database\Query\Builder $builder query builder
     * @return lluminate\Database\Query\Builder          resulting query
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }
        return $this->builder;
    }

    /**
     * Get Filters - Gets an array of supported filters from the request
     * @return Array  array of filters that are supported
     */
    private function getFilters()
    {
        return array_filter($this->request->only($this->filters));
    }
}
