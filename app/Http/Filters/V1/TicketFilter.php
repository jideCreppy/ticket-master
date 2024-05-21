<?php

namespace App\Http\Filters\V1;

class TicketFilter extends QueryFilter
{
    protected $sortable = ['title', 'created_at', 'updated_at'];

    public function include($value)
    {
        if (! $value) {
            return $this->builder;
        }

        $this->builder->with($value);
    }

    public function status(string $value): void
    {
        $this->builder->whereIn('status', explode(',', $value));
    }

    public function title($title): void
    {
        $title = str_replace('*', '%', $title);

        $this->builder->where('title', 'like', $title);
    }

    public function createdAt($value): void
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            $this->builder->whereBetween('created_at', $dates);
        }

        $this->builder->whereDate('created_at', $value);

    }

    public function updatedAt($value): void
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            $this->builder->whereBetween('updated_at', $dates);
        }

        $this->builder->whereDate('updated_at', $value);

    }
}
