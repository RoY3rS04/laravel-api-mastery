<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class AuthorFilter extends QueryFilter
{
    protected array $sortable = [
        'name',
        'email',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];

    public function id(string $value): Builder {

        return $this->builder->whereIn('id', explode(',', $value));
    }

    public function include(string $value): Builder {
        return $this->builder->with($value);
    }

    public function email(string $value): Builder {
        $likeStr = Str::of($value)
            ->replace('*', '%');

        return $this->builder->where('email', 'LIKE', $likeStr);
    }

    public function name(string $value): Builder {
        $likeStr = Str::of($value)
            ->replace('*', '%');

        return $this->builder->where('name', 'LIKE', $likeStr);
    }

    public function createdAt(string $value): Builder {

        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('created_at', $dates);
        }

        return $this->builder->whereDate('created_at', $dates);
    }

    public function updatedAt(string $value): Builder {

        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('created_at', $dates);
        }

        return $this->builder->whereDate('updated_at', $dates);
    }
}
