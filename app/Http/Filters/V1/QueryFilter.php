<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    public function __construct(
        protected Builder $builder,
        protected Request $request
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
}
