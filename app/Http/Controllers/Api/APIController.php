<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class APIController extends Controller
{
    use ApiResponses;

    protected const POLICY_CLASS = '';

    public function applyPolicy(string $ability, Model|string $model): void
    {
        Gate::authorize($ability, [$model, static::POLICY_CLASS]);
    }
}
