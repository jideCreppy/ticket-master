<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

abstract class QueryFilter
{
    protected $builder;

    protected $sortable = [];

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach (request()->query() as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }

        return $this->builder;
    }

    public function filter($arr)
    {
        foreach ($arr as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }

        return $this->builder;
    }

    protected function sort($values): void
    {
        $sortables = explode(',', $values);

        foreach ($sortables as $sortable) {
            $direction = Str::startsWith($sortable, '-') ? 'desc' : 'asc';

            $column = Str::of($sortable)->remove('-')->snake()->value();

            if (in_array($column, $this->sortable)) {
                $this->builder->orderBy($column, $direction);
            }
        }
    }
}
