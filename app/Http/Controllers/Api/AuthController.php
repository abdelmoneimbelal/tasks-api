<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //register a new user
    public function register(Request $request){

        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        return ['user' => $user, 'access_token' => $accessToken];
    }


    public function login(Request $request){

        $validatedData = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if(!auth()->attempt($validatedData)){

            return response()->json(['message' => 'invalid login details'], 401);

        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return ['user' => auth()->user(), 'access_token' => $accessToken];
    }
}
