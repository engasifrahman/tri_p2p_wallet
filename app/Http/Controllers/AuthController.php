<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\RegRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registration(RegRequest $request){
        $request->merge([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(10),
            'email_verified_at' => now()
        ]);

        try {
            $user = User::create($request->all());

            response()->setResponse(true, 'Registration successfull!', null, $user, 200);
        } catch (\Exception $e) {
            response()->setResponse(true, 'Registration failed!', null, null, 200);
        }

        return response()->getResponse();
    }

    public function login(LoginRequest $request){
        $user = User::where('email', $request->email)->first();

        if(! $user || ! Hash::check($request->password, $user->password)) {
            $errors = ['email' => ['The provided credentials are incorrect.']];
            response()->setResponse(false, 'Validation failed!', $errors, null, 422);
        } else{
            $data = ['user' => $user, 'token' => $user->createToken($request->email)->plainTextToken];
            response()->setResponse(true, 'Login successfull!', null, $data, 200);
        }

        return response()->getResponse();
    }

    public function logout(Request $request){
        // Revoke the token that was used to authenticate the current request...
        if($request->user()->currentAccessToken()->delete()){
            response()->setResponse(true, 'Logout successfull!', null, null, 200);
        } else{
            response()->setResponse(true, 'Logout failed!', null, null, 200);
        }

        return response()->getResponse();
    }
}
