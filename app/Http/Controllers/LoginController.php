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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
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
            $user = User::where(['email' =>$request->email])->first();

            $urls = route('email.verify', [
                'token' => base64_encode($request->email),
            ]);

            $data = [
                'email_verify_url' => $urls,
                'username' => $user->username,
            ];
            Mail::to('ashishyadav.avology@gmail.com')->send(new RegisterMail($data));
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful. Please verify your email.'
        ], 200);
     }

     public function login(Request $request)
     {
         $request->validate([
             'email' => 'required|email',
             'password' => 'required',
         ]);
     
         if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
             $user = Auth::user();
             if ($user->is_email_verified == '0') {
                 Auth::logout();
                 return response()->json([
                     'status' => 'error',
                     'errors' => 'Please verify your email address before logging in.'
                 ], 403); 
             }
     
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
        $page_content = ["meta_title"=>config('constant.forgot.forgot_password.meta_title'),"meta_description"=>config('constant.forgot.forgot_password.meta_description')];
        return view('front-end.forgot-password',compact('page_content'));
    }

    public function forgotSave(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();
    
        if (isset($user) && !empty($user)) {
            
            $token = Str::random(64);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email], 
                [
                    'token' => Hash::make($token),  
                    'created_at' => Carbon::now()  
                ]
            );

            $resetUrl = route('password.reset', [
                'token' => base64_encode($user->email.'_'.Carbon::now()->toDateTimeString()), 
            ]);

            $data = [
                'resetUrl' => $resetUrl,
                'username' => $user->username,
            ];
    
            Mail::to($user->email)->send(new ForgotPasswordMail($data));
    
            return back()->with('success', 'Reset link sent to your email.');
        } else {
            return back()->with('error', 'Email not found.');
        }
    }

    public function resetPasswordForm(Request $request)
    {
        $token = base64_decode($request->token);
        $token_arr = explode('_', $token);

        $email = $token_arr[0]; 
        $timestamp = $token_arr[1];

        $page_content = ["meta_title"=>config('constant.forgot.reset_password.meta_title'),"meta_description"=>config('constant.forgot.reset_password.meta_description')];

        $createdAt = Carbon::parse($timestamp);
        if ($createdAt->diffInHours(Carbon::now()) > 24) {
            return view('front-end.token_error',['message' => 'Token expired.Please resend the email.','page_content' => $page_content]); // 
        }
        return view('front-end.reset_password', compact('email','page_content'));
    }

    public function resetPasswordSave(Request $request){
       $request->validate([
          'password' => 'required|min:6|confirmed',
       ]);

       $user = User::where('email', $request->email)->first();

       if(isset($user) && !empty($user)){
           $hashedPassword = Hash::make($request->password);
           $user->update(['password' => $hashedPassword]);
       }
       return back()->with('success','Your password has been reset successfully.');
    }

    public function emailVerify(Request $request){

      $page_content = ["meta_title"=>config('constant.email_verify.verify.meta_title'),"meta_description"=>config('constant.email_verify.verify.meta_description')];

      $email = base64_decode($request->token);
      $user = User::where('email',$email)->first();

      return view('front-end.email_verify',compact('user','page_content'));
    }

    public function emailVerification(Request $request){
        $email = $request->email;
        User::where('email',$email)->update(['is_email_verified' => '1']);
        return back()->with('success','Your email has been successfully verified. You can now log in.');
    }
}
