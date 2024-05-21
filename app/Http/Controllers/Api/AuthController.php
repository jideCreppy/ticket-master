<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponses;

    public function login(UserLoginRequest $request): JsonResponse
    {
        $request->validated($request->all());

        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $user = User::where('email', $request->get('email'))->first();

        return $this->ok($request->get('email'), [
            'token' => $user->createToken($request->get('email'), ['*'], now()->addMonth())->plainTextToken]
        );
    }

    public function logout(): JsonResponse
    {
        //@phpstan-ignore-next-line
        request()->user()->currentAccessToken()->delete();

        return $this->ok('Logout successfully');
    }
}
