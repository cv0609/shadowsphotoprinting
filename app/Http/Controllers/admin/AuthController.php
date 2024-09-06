<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\GiftCardCategory;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdminLogin;
use App\Models\Blog;
use Carbon\Carbon;

class AuthController extends Controller
{
   public function login()
    {
        return view('admin.auth.login');
    }

    public function loginPost(AdminLogin $request)
	{
        if (auth()->guard('admin')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])){
            return redirect()->route('admin.dashboard')->with('success','Your Name or password is Wrong!');
        }else{
            return back()->with('error','Your Name or password is Wrong!');
        }

	}

    public function dashboard()
    {
        $blogs = Blog::count();
        $cards = GiftCardCategory::count();
        $products = Product::count();
        $orders = Order::count();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $revenueData = Order::selectRaw('SUM(total) as total, DAY(created_at) as day')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->groupBy('day')
        ->orderBy('day', 'asc')
        ->get();

        $dashboard_index = '0';
        $admin = Admin::all();
        if(isset($admin) && !empty($admin)){
            $dashboard_index = $admin[0]->set_index;
        }
        return view('admin.dashboard',compact('products','cards','blogs','orders','revenueData','dashboard_index'));
    }

    public function logout()
	{
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login.post')->with('status','Admin has been logged out!');
	}

    public function setIndex(Request $request){
       $set_index = $request->set_index;
       Admin::where('id',1)->update(['set_index' => $set_index]);
       return response()->json(['error' => false,'message' => "Index updated successfully."]);
    }
}
