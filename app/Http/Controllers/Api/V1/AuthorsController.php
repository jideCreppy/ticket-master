<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\APIController;
use App\Http\Filters\V1\AuthorFilter;
use App\Http\Resources\Api\V1\Author\AuthorResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AuthorsController extends APIController
{
    /**
     * Get Author(s)
     *
     * Return all authors registered in the system. Results can be filtered and sorted.
     *
     * @authenticated
     *
     * @group Author(s)
     *
     * @queryParam filter[id] string Authors Id(s). Multiple values separated by comma supported. Example: filter[id]=1,2,3
     * @queryParam filter[name] string Authors name. Asterisks will be replaced with supported DB wildcards. Example: filter[name]=*manager*
     * @queryParam filter[createdAt] string Authors created date or date range separated by comma. Example: filter[createdAt]=2020-01-01,2020-01-02
     * @queryParam filter[updatedAt] string Authors updated date or date range separated by comma. Example: filter[updatedAt]=2020-01-01,2020-01-02
     * @queryParam sort string Sort all returned attributes including the authors id. Sort direction can be specified by prepending a minus sign in front of the target field(s). Example: sort=-name,email
     */
    public function index(AuthorFilter $filters): AnonymousResourceCollection
    {
        return AuthorResource::collection(User::with('tickets')->filter($filters)->paginate());
    }

    /**
     * Get Author
     *
     * @authenticated
     *
     * @group Author(s)
     *
     * @queryParam filter[name] string Author name. Asterisks will be replaced with supported DB wildcards. Example: filter[name]=*manager*
     * @queryParam filter[createdAt] string Author created date or date range separated by comma. Example: filter[createdAt]=2020-01-01,2020-01-02
     * @queryParam filter[updatedAt] string Author updated date or date range separated by comma. Example: filter[updatedAt]=2020-01-01,2020-01-02
     * @queryParam sort string Sort all returned attributes. Sort direction can be specified by prepending a minus sign in front of the target field(s). Example: sort=-name,email
     */
    public function show(User $author, AuthorFilter $filters): AuthorResource
    {
        return new AuthorResource($author->load('tickets')->filter($filters)->first());
    }
}
