<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    public function __construct(
        protected Builder $builder,
        protected Request $request,
        protected array $sortable = []
    ) {}

    protected function filter(array $filters): Builder {
        foreach ($filters as $filterKey => $filterValue) {
            if (method_exists($this, $filterKey)) {
                $this->$filterKey($filterValue);
            }
        }

        return $this->builder;
    }

    public function apply(Builder $builder): Builder {
        $this->builder = $builder;

        foreach ($this->request->all() as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }

        return $builder;
    }

    protected function sort($value) {
        $sortAttributes = explode(',', $value);

        foreach ($sortAttributes as $sortAttribute) {
            $direction = 'asc';

            if(($attr = \Str::of($sortAttribute))->startsWith('-')) {
                $direction = 'desc';
                $sortAttribute = $attr->replace('-', '')->value();
            }

            if (!in_array($sortAttribute, $this->sortable) &&
                !array_key_exists($sortAttribute, $this->sortable)
            ) {
                continue;
            }

            $columnName = $this->sortable[$sortAttribute] ?? $sortAttribute;

            $this->builder->orderBy(
                $columnName,
                $direction
            );
        }
    }
}
