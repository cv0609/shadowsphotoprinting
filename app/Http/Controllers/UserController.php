<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Image\Image;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendAugustCouponCode;
use Illuminate\Support\Str;

class UserController extends Controller
{
 public function index()
{
    // Fetch WP users in chunks
    DB::table('wp_users')->orderBy('ID')->chunk(50, function ($users) {
        foreach ($users as $user) {
            // Check if user already exists
            $existingUser = DB::table('users')->where('email', $user->user_email)->first();
            
            if (!$existingUser) {
                // Insert new user
                $id = DB::table('users')->insertGetId([
                    'username' => $user->user_login,
                    'email' => $user->user_email,
                    'password' => Hash::make('user_1234'), // Laravel hashing
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Insert user details
                if ($id) {
                    DB::table('user_details')->insert([
                        'user_id' => $id,
                        'fname' => $user->display_name,
                    ]);
                }
            }
        }
    });

    return response()->json(['message' => 'Users imported successfully']);
}

public function augustPromotionEmail(Request $request)
{
    // Check if user is logged in
    if (!auth()->check()) {
        return response()->json([
            'success' => false,
            'message' => 'Please login to receive your coupon code.'
        ], 401);
    }

    $user = auth()->user();
    
    // Check if user already received the coupon
    if ($user->is_august_coupon == 1) {
        return response()->json([
            'success' => false,
            'message' => 'You have already received your August promotion coupon.'
        ], 400);
    }

    $coupon_code = "10%FreeAugust2025";
    $email = $user->email;

    $data = [
        'coupon_code' => $coupon_code
    ];

    // Send email
    Mail::to($email)->send(new SendAugustCouponCode($data));

    // Update user record to mark coupon as sent
    User::where('id', $user->id)->update(['is_august_coupon' => 1]);

    return response()->json([
        'success' => true,
        'message' => 'Coupon code has been sent to your email!'
    ]);
}

}
