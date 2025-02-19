<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Http\Requests\ProductCategoryRequest;
use App\Http\Requests\ProductRequest;
use App\Models\product_sale;
use App\Models\SalePopUp;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use PhpParser\Node\Expr\FuncCall;

class SalePopupController extends Controller
{
    public function index(){
        $sale_popups = SalePopUp::all();
        return view('admin.sale_popup.index', compact('sale_popups'));
    }

    public function addSalePopup(){
        return view('admin.sale_popup.add');
    }

    public function addSalePopupSave(Request $request){
       $request->validate([
            'name' => 'required',
            'image' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
       ]);

       $image = "";

       if($request->has('image'))
        {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName().'-'.time().'.' . $file->getClientOriginalExtension();
            $destinationPath = 'assets/admin/uploads/sale_popup';
            $file->move($destinationPath, $fileName);
            $image =  $destinationPath.'/'.$fileName;
        }
        $sale_arr = ["name"=>$request->name,'start_date'=>$request->start_date,'end_date'=>$request->end_date,'image'=>$image];  
        SalePopUp::create($sale_arr);
        return redirect()->route('popup-index')->with('success','Sale popup added successfully');
    }

    public function salePopupUpdateStatus(Request $request){
       SalePopUp::where('id', $request->id)->update(['status' => $request->checkedValue]);
       return response()->json(['error' => false, 'message' => 'Status updated successfully.','checked' =>$request->checkedValue]);
    }

    public function editPopupShow($id){
       $sale_popup_details = SalePopUp::where('id',$id)->first();
       return view('admin.sale_popup.edit', compact('sale_popup_details'));
    }

    public function editSalePopupSave(Request $request){

        $data = ["name"=>$request->name,'start_date'=>$request->start_date,'end_date'=>$request->end_date];

        if($request->has('image'))
        {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName().'-'.time().'.' . $file->getClientOriginalExtension();
            $destinationPath = 'assets/admin/uploads/sale_popup';
            $file->move($destinationPath, $fileName);
            $image =  $destinationPath.'/'.$fileName;
            $data['image'] = $image;
        }

        SalePopUp::whereId($request->id)->update($data);

        return redirect()->route('edit-popup-show',['id' => $request->id])->with('success','Sale popup updated successfully');
    }

    public function salePopupDelete($id){
        $saleDetail = SalePopUp::whereId($id)->delete();
        return redirect()->route('popup-index')->with('success','Sale popup deleted successfully');
    }
}
