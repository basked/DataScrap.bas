<?php

namespace Modules\Nuxt\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Modules\Nuxt\Http\Requests\UserLoginRequest;
use Modules\Nuxt\Http\Requests\UserRegisterRequest;
use Modules\Nuxt\Transformers\UserResource;
use Nwidart\Modules\Routing\Controller;

class AuthController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => bcrypt($request->password),
        ]);

        if (!$token = auth()->attempt($request->only(['email', 'password']))) {
            return abort(401);
        };

        return (new UserResource($request->user()))->additional(
            [
                'meta' => [
                    'token' => $token
                ]
            ]
        );
    }

    public function login(UserLoginRequest $request)
    {
        if (!$token = auth()->attempt($request->only(['email', 'password']))) {
            return response()->json([
                'errors'=>[
                    'email'=>['Sorry we cant find you with those details'],
                ]
                ],422);
        };

        return (new UserResource($request->user()))->additional(
            [
                'meta' => [
                    'token' => $token
                ]
            ]
        );
    }

    public function user(Request $request){
        return new UserResource($request->user());
    }

}
