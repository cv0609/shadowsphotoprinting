<?php

use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use App\Models\SalePopupModel;
use App\Models\Coupon;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;

function read_json($file_name = ''){
    $pointer = base_path('resources/pages_json/'.$file_name);
   if(file_exists($pointer)){
    $data = json_decode(file_get_contents($pointer));

   }else{
        $data = null;
    }
    return $data;
}

function get_cart_detail_item_type($item_id,$item_type){
   if(isset($item_id) && !empty($item_id)){
      
   }
}

function addWaterMark($image = '')
{
    // Construct the full path to the original image in storage
    $imagePath = storage_path('app/public/assets/images/order_images/' . basename($image)); // Use basename to get the filename only
    $watermarkPath = public_path('assets/images/order_images/watermark.jpg'); // Watermark path

    // Check if the original image exists
    if (!file_exists($imagePath)) {
        return response()->json(['error' => 'Original image not found.'], 404);
    }

    // Load the original image
    $img = Image::make($imagePath);

    // Apply the watermark
    $img->insert($watermarkPath, 'bottom-right', 10, 10); // Watermark with padding

    // Return the watermarked image directly in the response
    // dd($img->response('jpg'));
    return $img->response('jpg'); // or 'png' depending on your image type
}

if (!function_exists('getWatermarkedImageUrl')) {
    function getWatermarkedImageUrl($imageName)
    {
        // Generate the URL to the watermarked image
        return route('watermark', ['image' => $imageName]);
    }
}

if (!function_exists('getS3Img')) {
    function getS3Img($str, $size){
        // $str = str_replace('original', $size, $str);
        $str = str_replace('raw', $size, $str);
        return $str;
    }
}

if (!function_exists('getS3Img2')) {
    function getS3Img2($str, $size){

         // If size is 'raw', return the original path
        if ($size === 'raw') {
            return $str;
        }
        // For other sizes, remove 'raw/' and add the size folder

        $str = str_replace('raw/', '', $str);
        // $str = preg_replace('/(.*)(\/[^\/]*?$)/', "$1/$size$2", $str);
        $str = preg_replace('/(.*\/[^\/]*?)(\/[^\/]*?$)/', "$1/$size$2", $str);
        return $str;
    }
}

if (!function_exists('getS3ImgName')) {
    function getS3ImgName($str){
        $str = preg_replace('/.*\/([^\/]*?$)/', "$1", $str);
        return $str;
    }
}

if (!function_exists('generateGiftCardCoupon')) {
    function generateGiftCardCoupon($length = 8){
        do {
            $code = strtoupper(Str::random($length));
        } while (Coupon::where('code', $code)->exists()); // Check if it already exists
    
        return $code;
    }
}

if (!function_exists('getSalePopup')) {
    function getSalePopup(){
        $popup = "";
        $popup = SalePopupModel::getTopPrioritySale();
        if(isset($popup) && !empty($popup)){
            return $popup;
        }else{
            return "";
        }
    }
}

if (!function_exists('setAppTimezone')) {
    function setAppTimezone()
    {
        $timezone = request()->cookie('user_timezone', config('app.timezone'));
        // Set Laravel and PHP timezone
        Config::set('app.timezone', $timezone);
        date_default_timezone_set($timezone);
        // Return the timezone for use in JavaScript
        return json_encode($timezone);
    }
}
