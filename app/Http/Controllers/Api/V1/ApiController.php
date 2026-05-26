<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ApiController extends Controller
{
    use ApiResponses, AuthorizesRequests;

    protected string $policyClass;

    public function include(string $relationship): bool {
        $param = request()->get('include');

        if (!isset($param)) {
            return false;
        }

        $includeValues = explode(',', strtolower($param));

        return in_array(strtolower($relationship), $includeValues);
    }

    public function isAble(string $ability, ?Model $targetModel) {

        $gate = \Gate::policy($targetModel::class, $this->policyClass);

        $gate->authorize(
            $ability,
            $targetModel
        );
    }
}
