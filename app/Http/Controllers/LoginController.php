<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\User;

class LoginController extends Controller
{
    public function registerUser(Request $request)
     {
        $request->validate([
            'email' => 'unique:users,email',
        ]);

        User::insert(['name'=>$request->name,'email'=>$request->email,'password'=>$request->password]);
     }
}
