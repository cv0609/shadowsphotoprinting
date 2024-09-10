<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\RegisterMail;
use App\Mail\ForgotPasswordMail;

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
        $user = User::insert(['username'=>$request->name,'email'=>$request->email,'password'=>$hashedPassword]);
        if(isset($user) && !empty($user)){
            $register = User::where(['email' =>$request->email])->first();
            Mail::to('ashishyadav.avology@gmail.com')->send(new RegisterMail($register));
        }
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

    public function forgotPassword(){
        $page_content = ["meta_title"=>config('constant.pages_meta.forgot_password.meta_title'),"meta_description"=>config('constant.pages_meta.forgot_password.meta_description')];
        return view('front-end.forgot-password',compact('page_content'));
    }

    public function forgotSave(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();
    
        if (isset($user) && !empty($user)) {
            $randomPassword = Str::random(6);
    
            $hashedPassword = Hash::make($randomPassword);
    
            $user->update(['password' => $hashedPassword]);

            $data = [
               'username' => $user->username,
               'email' => $request->email,
               'password' => $randomPassword
            ];
    
            Mail::to($user->email)->send(new ForgotPasswordMail($data));
    
            return back()->with('success', 'A new password has been sent successfully. Please check your mail.');
        } else {
            return back()->with('error', 'Email not found.');
        }
    }
}
