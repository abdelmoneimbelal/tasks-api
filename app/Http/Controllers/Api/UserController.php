<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function updatePassword(Request $request){

        $user = auth()->user();

        if(!Hash::check($request->password, $user->password)){
            return response()->json(['message' => 'Your current password is incorrect'], 401);
        }

        $validateData = $request->validate([
            'password' => 'required',
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ]);

        $user->password = bcrypt($validateData['new_password']);

        if($user->save()){

            return ['message' => 'Password updated successfully'];

        }else{

            return response()->json(['message' => 'Some error happened, please try again'], 500);

        }
    }


    public function updateProfile(Request $request){

        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,'.auth()->id()
        ]);


        if(auth()->user()->update($validatedData)){
            return ['message' => 'Updated successfully'];
        }

        return response()->json(['message' => 'Please try again later'], 500);
    }

}
