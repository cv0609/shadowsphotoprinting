<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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

    public function login(Request $request)
     {
          // Attempt to login
          if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            // Redirect to dashboard
            return response()->json([
                'status' => 'success',
                'message' => 'User login successfully!'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'errors' => 'Invalid email or password'
            ], 422);
        }
     }

   public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
