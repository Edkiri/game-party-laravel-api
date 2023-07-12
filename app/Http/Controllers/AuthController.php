<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;


class AuthController extends Controller
{
    public function register(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'username' => ['required', 'string', 'min:4', 'unique:users,username'],
            'steam_username' => ['string', 'min:4'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', Password::min(8)],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $validData = $validator->validated();

        $newUser = User::create([
            'username' => $validData['username'],
            'steam_username' => $validData['steam_username'] ?? null,
            'email' => $validData['email'],
            'password' => bcrypt($validData['password']),
            'role_id' => 2
        ]);

        $token = $newUser->createToken('apiToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $newUser,
                'token' => $token
            ]
        ], Response::HTTP_CREATED);
    }
}
