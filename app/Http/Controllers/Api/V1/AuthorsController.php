<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\APIController;
use App\Http\Filters\V1\AuthorFilter;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class AuthorsController extends APIController
{
    /**
     * Display a listing of the resource.
     */
    public function index(AuthorFilter $filters)
    {
        return UserResource::collection(User::with('tickets')->filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $author, AuthorFilter $filters)
    {
        return new UserResource($author->load('tickets')->filter($filters)->first());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $author)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $author)
    {
        //
    }
}
