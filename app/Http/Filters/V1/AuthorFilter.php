<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;

class AuthorFilter extends QueryFilter
{
    protected array $sortable = [
        'id',
        'name',
        'email',
        'created_at',
        'updated_at',
        'email_verified_at',
    ];

    public function id($ids): Builder
    {
        $arrIds = explode(',', $ids);

        if (count($arrIds) > 1) {
            return $this->builder->whereIn('id', $arrIds);
        }

        return $this->builder->where('id', $ids);
    }

    public function name($name): void
    {
        $title = str_replace('*', '%', $name);

        $this->builder->where('title', 'like', $name);
    }

    public function createdAt(string $date): void
    {
        $dates = explode(',', $date);

        if (count($dates) > 1) {
            $this->builder->whereBetween('created_at', $dates);
        }

        $this->builder->whereDate('created_at', $date);

    }

    public function updatedAt(string $date): void
    {
        $dates = explode(',', $date);

        if (count($dates) > 1) {
            $this->builder->whereBetween('updated_at', $dates);
        }

        $this->builder->whereDate('updated_at', $date);

    }
}
