<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GiftCardCategoryRequest;
use App\Models\GiftCardCategory;
use Illuminate\Validation\Rule;
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
        $emailImage = null;
        $useDifferentEmailImage = $request->use_different_email_image == '1' ? 1 : 0;
        if($request->has('image'))
         {
             $file = $request->file('image');
             $fileName = $file->getClientOriginalName().'-'.time().'.' . $file->getClientOriginalExtension();
             $destinationPath = 'assets/admin/uploads/categories';
             $file->move($destinationPath, $fileName);
             $image =  $destinationPath.'/'.$fileName;
         }
         if ($useDifferentEmailImage && $request->has('email_image')) {
             $emailFile = $request->file('email_image');
             $emailFileName = $emailFile->getClientOriginalName().'-'.time().'.' . $emailFile->getClientOriginalExtension();
             $destinationPath = 'assets/admin/uploads/categories';
             $emailFile->move($destinationPath, $emailFileName);
             $emailImage =  $destinationPath.'/'.$emailFileName;
         }

         GiftCardCategory::insert([
            "product_title" => $request->name,
            'slug' => $slug,
            'product_image' => $image,
            'use_different_email_image' => $useDifferentEmailImage,
            'email_product_image' => $emailImage
        ]);
        return redirect()->route('gift-card-list')->with('success','Card inserted successfully');
    }

    public function giftCardShow($category_id)
    {
        $category = GiftCardCategory::whereId($category_id)->first();
        return view('admin.gift_cards.edit', compact('category'));
    }

    public function giftCardUpdate(Request $request)
    {
        $category = GiftCardCategory::whereId($request->category_id)->firstOrFail();
        $useDifferentEmailImage = $request->use_different_email_image == '1';
        $emailImageRule = 'nullable|image|mimes:jpeg,png,jpg|max:2048';

        if ($useDifferentEmailImage && empty($category->email_product_image)) {
            $emailImageRule = 'required|image|mimes:jpeg,png,jpg|max:2048';
        }

        $request->validate([
            'name' => [
                'required',
                Rule::unique('gift_card_category', 'product_title')->ignore($request->category_id),
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'use_different_email_image' => 'nullable|in:0,1',
            'email_image' => $emailImageRule
        ]);

        $slug = \Str::slug($request->name);
        $useDifferentEmailImage = $request->use_different_email_image == '1' ? 1 : 0;
        $data = [
            "product_title" => $request->name,
            'slug' => $slug,
            'use_different_email_image' => $useDifferentEmailImage,
        ];
        if($request->has('image'))
        {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName().'-'.time().'.' . $file->getClientOriginalExtension();
            $destinationPath = 'assets/admin/uploads/categories';
            $file->move($destinationPath, $fileName);
            $image =  $destinationPath.'/'.$fileName;
            $data['product_image']=$image;
        }

        if ($useDifferentEmailImage) {
            if ($request->has('email_image')) {
                $emailFile = $request->file('email_image');
                $emailFileName = $emailFile->getClientOriginalName().'-'.time().'.' . $emailFile->getClientOriginalExtension();
                $destinationPath = 'assets/admin/uploads/categories';
                $emailFile->move($destinationPath, $emailFileName);
                $data['email_product_image'] = $destinationPath.'/'.$emailFileName;
            }
        } else {
            $data['email_product_image'] = null;
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
