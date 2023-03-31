<?php

namespace App\Http\Controllers\Api;
use \App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $user = User::query()->create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => \Hash::make($request->input('password')),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'remember_token' => Str::random(60),
            ]);
            $token = $user->createToken('user_token')->plainTextToken;

            return response()->json(['user' => $user, 'token' => $token], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'AuthController\'da Birirşeyler yanlış gitti'
            ]);
        }

    }

    public function login(Request $request)
    {
        try {
            $user = User::query()->where('email', '=', $request->input('email'))->firstOrFail();

            if (\Hash::check($request->input('password'), $user->password)){
                $token = $user->createToken('user_token')->plainTextToken;

                return response()->json(['user' => $user, 'token' => $token], 200);

            }
            return response()->json([
                'error' => 'Giriş yaparken birşeyler yanlış gitti!'
            ]);


        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'AuthController.login\'de Birirşeyler yanlış gitti'
            ]);
        }

    }

    public function logout(Request $request)
    {
        try {
            $user = User::query()->findOrFail($request->input('user_id'));
            $user->tokens()->delete();

            return response()->json('Kullanıcı çıkış yaptı', 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'AuthController.logout\'da Birirşeyler yanlış gitti'
            ]);
        }

    }

}
