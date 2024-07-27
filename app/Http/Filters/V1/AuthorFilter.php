<?php

namespace App\Http\Filters\V1;

class AuthorFilter extends QueryFilter
{
    protected array $sortable = ['id', 'created_at', 'updated_at', 'email_verified_at'];

    public function id($ids): void
    {
        $arrIds = explode(',', $ids);

        if (count($arrIds) > 1) {
            $this->builder->whereIn('id', $arrIds);
        }

        $this->builder->where('id', $ids);

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
