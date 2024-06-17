<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GiftCardCategoryRequest;
use App\Models\GiftCardCategory;
use Illuminate\Http\Request;

class GiftCardController extends Controller
{
    public function giftCard()
    {
        $categories = GiftCardCategory::get();
        return view('admin.gift_cards.index',compact('categories'));
    }

    public function giftCardAdd()
    {
        return view('admin.gift_cards.add');
    }

    public function giftCardSave(GiftCardCategoryRequest $request)
    {
        $slug = \Str::slug($request->name);
        $image = "";
        if($request->has('image'))
         {
             $file = $request->file('image');
             $fileName = $file->getClientOriginalName().'-'.time().'.' . $file->getClientOriginalExtension();
             $destinationPath = 'assets/admin/uploads/categories';
             $file->move($destinationPath, $fileName);
             $image =  $destinationPath.'/'.$fileName;
         }
         GiftCardCategory::insert(["name"=>$request->name,'slug'=>$slug,'image'=>$image]);
        return redirect()->route('gift-card-list')->with('success','Card inserted successfully');
    }

    public function giftCardShow($category_id)
    {
        $category = GiftCardCategory::whereId($category_id)->first();
        return view('admin.gift_cards.edit', compact('category'));
    }

    public function giftCardUpdate(Request $request)
    {

        $slug = \Str::slug($request->name);
        $data = ["name"=>$request->name,'slug'=>$slug];
        if($request->has('image'))
        {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName().'-'.time().'.' . $file->getClientOriginalExtension();
            $destinationPath = 'assets/admin/uploads/categories';
            $file->move($destinationPath, $fileName);
            $image =  $destinationPath.'/'.$fileName;
            $data['image']=$image;
        }
        GiftCardCategory::whereId($request->category_id)->update($data);

        return redirect()->route('gift-card-list')->with('success','Card updated successfully');
    }

    public function giftCardDistroy($category_id)
    {
       $category = GiftCardCategory::whereId($category_id)->delete();
       return redirect()->route('gift-card-list')->with('success','Card is deleted successfully');
    }
}
