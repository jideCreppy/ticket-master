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
     * Login user
     *
     * Authenticate the user and return their fresh personal access.
     *
     * @unauthenticated
     *
     * @group Authentication
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
     * Register a new user and return their personal access token
     *
     * @unauthenticated
     *
     * @group Authentication
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

        return $this->ok('API token: '.$credentials['email'], compact('token'));
    }

    /**
     * Logout user
     *
     * Logs out the authenticated user and destroy their personal access token
     *
     * @authenticated
     *
     * @group Authentication
     */
    public function logout(): JsonResponse
    {
        //@phpstan-ignore-next-line
        auth()->user()->currentAccessToken()->delete();

        return $this->ok('Logout successfully');
    }
}
