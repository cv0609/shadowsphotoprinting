<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Image\Image;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
                    'password' => Hash::make('defaultPassword'), // Laravel hashing
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

}
