<?php

namespace App\Http\Controllers\Api;

use App\Http\Permissions\V1\Abilities;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends APIController
{
    /**
     * Register User
     *
     * Register a new user and return a new bearer token.
     *
     * @unauthenticated
     *
     * @group Authentication
     *
     * @subgroup User Registration
     *
     * @response 201
     * {
     * "message": "API token: manager@example.com",
     * "status": 200,
     * "data": {"token": "YOUR_AUTH_KEY"}
     * }
     */
    public function register(UserRegistrationRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $user = User::create([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => $credentials['password'], // User model handles encryption for passwords
        ]);

        $token = $user->createToken($credentials['email'], ['*'], now()->addDay())->plainTextToken;

        return $this->ok('API token: '.$credentials['email'], compact('token'), 201);
    }

    /**
     * Login User
     *
     * Authenticate a user and return a new bearer token.
     *
     * @unauthenticated
     *
     * @group Authentication
     *
     * @subgroup User Authentication
     *
     * @responseFile  201 storage/responses/api/V1/auth/login_success.post.json
     *
     * @response 401 {"message": "Invalid credentials."}
     */
    public function login(UserLoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (! Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            return response()->json(
                [
                    'message' => 'Invalid credentials.',
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $user = User::where('email', $credentials['email'])->first();

        return $this->ok(
            $credentials['email'],
            ['token' => $user->createToken('API token: '.$credentials['email'], Abilities::getAbilities($user), now()->addDay())->plainTextToken]
        );
    }

    /**
     * Logout User
     *
     * Sign out the authenticated user and delete their assigned bearer token.
     *
     * @authenticated
     *
     * @group Authentication
     *
     * @subgroup User Authentication
     *
     * @response 200 {"message": "You have been signed out"}
     */
    public function logout(): JsonResponse
    {
        //@phpstan-ignore-next-line
        auth()->user()->currentAccessToken()->delete();

        return $this->ok('You have been signed out');
    }
}
