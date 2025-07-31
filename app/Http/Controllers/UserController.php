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
use App\Models\Coupon;
use Illuminate\Support\Str;
use Carbon\Carbon;

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
            'message' => 'Please Login/SignUp to receive your coupon code.'
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

    $generateGiftCardCoupon = generateGiftCardCoupon(8);

    $coupon_code = "FreeAug".$generateGiftCardCoupon;
    $email = $user->email;

    $data = [
        'coupon_code' => $coupon_code
    ];

    // Send email
    Mail::to($email)->send(new SendAugustCouponCode($data));

    // Update user record to mark coupon as sent
    User::where('id', $user->id)->update(['is_august_coupon' => 1]);

    Coupon::create([
        'code' => $coupon_code,
        'type' => '1',
        'amount' => '10',
        'minimum_spend' => 0.00,
        'maximum_spend' => 10000000000.00,
        'start_date' => Carbon::create(2025, 8, 1)->format('Y-m-d'),
        'end_date' => Carbon::create(2025, 8, 31)->format('Y-m-d'),
        'is_active' => 1,
        'use_limit' => 1
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Coupon code has been sent to your email!'
    ]);
}

}
