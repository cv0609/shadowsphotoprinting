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
        DB::table('wp_users')->chunk(50, function ($users) {
            $userData = [];
    
            foreach ($users as $user) {
                $userData = [
                    'username' => $user->user_login, // Assuming 'user_login' is the username
                    'email' => $user->user_email, // Assuming 'user_email' is the email
                    'password' => $user->user_pass, // Set a default password or migrate from WP
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $id = DB::table('users')->insertGetId($userData);
                if($id)
                 {
                    DB::table('user_details')->insert(['user_id'=>$id,'fname'=>$user->display_name]);
                 }
            }
    
            // Insert batch into user table
            
        });
    
        return response()->json(['message' => 'Users imported successfully']);
    }
}
