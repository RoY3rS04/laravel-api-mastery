<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class TicketFilter extends QueryFilter
{
    public function status(string $value): Builder {

        return $this->builder->whereIn('status', explode(',', $value));
    }

    public function include(string $value): Builder {
        return $this->builder->with($value);
    }

    public function title(string $value): Builder {
        $likeStr = Str::of($value)
            ->replace('*', '%');

        return $this->builder->where('title', 'LIKE', $likeStr);
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
