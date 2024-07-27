<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends APIController
{
    /**
     * Login user
     *
     * Authenticates the user and returns a user token
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
            ['token' => $user->createToken($credentials['email'], ['*'], now()->addMonth())->plainTextToken]
        );
    }

    /**
     * Logout user
     *
     * Logs out the authenticated user and destroys the user token
     *
     * @authenticated
     *
     * @group Authentication
     */
    public function logout(Request $request): JsonResponse
    {
        //@phpstan-ignore-next-line
        $request->user()->currentAccessToken()->delete();

        return $this->ok('Logout successfully');
    }

    /**
     * Register a new user
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
            'password' => $credentials['password'], // User model will handle cast operation for password
        ]);

        $token = $user->createToken($credentials['email'], ['*'], now()->addMonth())->plainTextToken;

        return $this->ok($credentials['email'], compact('token'));
    }
}
