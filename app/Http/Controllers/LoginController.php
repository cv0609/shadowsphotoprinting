<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function registerUser(Request $request)
     {
        $validator = Validator::make($request->all(), [
            'email' => 'unique:users,email',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        $hashedPassword = Hash::make($request->password);
        User::insert(['name'=>$request->name,'email'=>$request->email,'password'=>$hashedPassword]);
        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully!'
        ], 200);
     }
}
