<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Permissions\V1\Abilities;
use Illuminate\Foundation\Http\FormRequest;

class ApiFormRequests extends FormRequest
{
    /**
     * @return mixed
     */
    protected function canCreateOwn()
    {
        return auth()->user()->tokenCan(Abilities::CREATE_OWN_TICKET);
    }

    /**
     * @return mixed
     */
    protected function canCreateAny()
    {
        return auth()->user()->tokenCan(Abilities::CREATE_TICKET);
    }

    /**
     * @return mixed
     */
    protected function canUpdateOwn()
    {
        return auth()->user()->tokenCan(Abilities::UPDATE_OWN_TICKET);
    }

    /**
     * @return mixed
     */
    protected function canUpdateAny()
    {
        return auth()->user()->tokenCan(Abilities::UPDATE_TICKET);
    }
}
