<?php

namespace Jiannius\Scaffold\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

trait Model
{
    /**
     * Apply scope from filters
     *
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    public function scopeFilter($query, $filters)
    {
        foreach ($filters as $filter) {
            $column = $filter['column'];
            $value = $filter['value'];
            $scope = $filter['scope'] ?? null;
            $operator = $filter['operator'] ?? null;

            if ($scope) $query->$scope($value);
            else if ($operator) $query->where($column, $operator, $value);
            else if (is_array($value)) $query->whereIn($column, $value);
            else $query->where($column, $value);
        }

        return $query;
    }

    /**
     * Scope for fetching the query builder
     *
     * @param Builder $query
     * @param boolean $filterFromQuery
     * @return Collection
     */
    public function scopeFetch($query, $filterFromQuery = true)
    {
        if ($filterFromQuery) {
            $filters = $this->getFiltersFromQuery();
            $query->filter($filters);
        }

        $limit = request()->limit ?: null;
        $orders = request()->order_by ?: null;
        $resource = $this->getJsonResource();
        $paginate = true;

        if ($disablePaginate = isset($this->paginate) && $this->paginate === false) {
            $paginate = false;
        }

        if ($orders) {
            foreach (explode(',', $orders) as $order) {
                $split = explode('__', $order);
                $col = $split[0];
                $by = $split[1];

                $query->orderBy($col, $by);
            }
        }

        $collection = $paginate
            ? $resource->collection($query->paginate($limit > 0 ? $limit : 20))
            : $resource->collection($query->get());

        return isset($filters)
            ? $collection->additional(['meta' => ['filters' => $filters]])
            : $collection;
    }

    /**
     * Scope for date range
     *
     * @param Builder $query
     * @param object $range
     * @param string $column
     * @return Builder
     */
    public function scopeDateRange($query, $range, $column = 'date')
    {
        if ($range) {
            if (is_string($range)) $range = date_range($range);
            if (is_array($range)) $range = (object)$range;

            $from = $range->from ?? null;
            $to = $range->to ?? null;

            if ($from && !$to) return $query->whereRaw("date($column) >= '$from'");
            if (!$from && $to) return $query->whereRaw("date($column) <= '$to'");
            if ($from && $to) return $query->whereRaw("date($column) >= '$from' and date($column) <= '$to'");
        }
    }

    /**
     * Check model has a specific column
     * 
     * @return boolean
     */
    public function hasColumn($column)
    {
        $table = $this->getTable();

        return ($this->connection && Schema::connection($this->connection)->hasColumn($table, $column))
                    || (!$this->connection && Schema::hasColumn($table, $column));
    }

    /**
     * Check model column is a date type
     * 
     * @return boolean
     */
    public function isDateColumn($column)
    {
        return $this->hasColumn($column)
            && in_array(Schema::getColumnType($this->getTable(), $column), ['date', 'datetime', 'timestamp']);
    }

    /**
     * Get filters from query string
     */
    public function getFiltersFromQuery()
    {
        if (request()->isMethod('post')) $qs = request()->all();
        else $qs = request()->query();

        if ($qs == '' || is_null($qs)) return [];
        if (is_string($qs)) return ['search' => $qs];

        $filters = [];

        foreach ($qs as $key => $value) {
            if (is_null($value)) continue;

            $column = preg_replace('/(_from|_to)$/', '', $key);
            $fn = Str::camel($column);

            if (method_exists($this, 'scope' . ucfirst($fn))) {
                array_push($filters, ['column' => $column, 'value' => $value, 'scope' => $fn]);
            }
            else {
                if ($this->isDateColumn($column)) {
                    if (Str::endsWith($key, '_from')) array_push($filters, ['column' => $column, 'value' => $value, 'operator' => '>=']);
                    if (Str::endsWith($key, '_to')) array_push($filters, ['column' => $column, 'value' => $value, 'operator' => '<=']);
                }
                else if ($this->hasColumn($column)) {
                    array_push($filters, ['column' => $column, 'value' => $value]);
                }
            }
        }

        return $filters;
    }

    /**
     * Get the Json Resource instance
     * 
     * @return string
     */
    public function getJsonResource()
    {
        $modelname = str_replace('App\\Models\\', '', get_class($this));
        $resourcename = 'App\\JsonResources\\' . $modelname;

        return class_exists($resourcename)
            ? app()->makeWith($resourcename, ['resource' => $this])
            : $this;
    }

    /**
     * Convert model to Json resource
     * 
     * @return JsonResource
     */
    public function toResource()
    {
        return $this->getJsonResource();
    }
}
