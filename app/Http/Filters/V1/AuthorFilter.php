<?php

namespace App\Http\Filters\V1;

class AuthorFilter extends QueryFilter
{
    protected $sortable = ['id', 'created_at', 'updated_at', 'email_verified_at'];

    public function include($value)
    {
        if (! $value) {
            return $this->builder;
        }

        $this->builder->with($value);
    }

    public function id($ids): void
    {
        $ids = explode(',', $ids);

        if (count($ids) > 1) {
            $this->builder->whereIn('id', $ids);
        }

        $this->builder->whereIn('id', $ids);

    }

    public function createdAt($dates): void
    {
        $dates = explode(',', $dates);

        if (count($dates) > 1) {
            $this->builder->whereBetween('created_at', $dates);
        }

        $this->builder->whereDate('created_at', $dates);

    }

    public function updatedAt($dates): void
    {
        $dates = explode(',', $dates);

        if (count($dates) > 1) {
            $this->builder->whereBetween('updated_at', $dates);
        }

        $this->builder->whereDate('updated_at', $dates);

    }
}
