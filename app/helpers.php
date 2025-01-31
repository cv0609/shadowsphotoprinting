<?php

use Intervention\Image\Facades\Image;

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

// function addWaterMark($image = '')
// {
//     // Construct the full path to the original image in storage
//     $imagePath = storage_path('app/public/assets/images/order_images/' . basename($image)); // Use basename to get the filename only
//     $watermarkPath = public_path('assets/images/order_images/watermark.jpg'); // Watermark path

//     // Check if the original image exists
//     if (!file_exists($imagePath)) {
//         return response()->json(['error' => 'Original image not found.'], 404);
//     }

//     // Load the original image
//     $img = Image::make($imagePath);

//     // Apply the watermark
//     $img->insert($watermarkPath, 'bottom-right', 10, 10); // Watermark with padding

//     // Define the path where you want to save the watermarked image
//     $watermarkedImagePath = public_path('assets/images/order_images/watermarked_' . basename($image));

//     // Save the watermarked image
//     $img->save($watermarkedImagePath);

//     // Return the path of the watermarked image
//     return asset('assets/images/order_images/watermarked_' . basename($image)); // Return the URL path
// }

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
        $str = str_replace('original', $size, $str);
        return $str;
    }
}

if (!function_exists('getS3Img2')) {
    function getS3Img2($str, $size){
        $str = str_replace('raw/', '', $str);
        $str = preg_replace('/(.*)(\/[^\/]*?$)/', "$1/$size$2", $str);
        return $str;
    }
}

if (!function_exists('getS3ImgName')) {
    function getS3ImgName($str){
        $str = preg_replace('/.*\/([^\/]*?$)/', "$1", $str);
        return $str;
    }
}



