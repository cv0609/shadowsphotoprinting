<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Image\Image;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use DB;

class UserController extends Controller
{
    public function index()
     {
        $wptable = DB::table('wp_users')->all();
        dd($wptable);
        $user = User::insert(['username'=>$request->name,'email'=>$request->email,'password'=>$hashedPassword]);

     }
}
