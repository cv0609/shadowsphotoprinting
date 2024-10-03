<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function showWatermarkedImage()
    {
        // Define paths for the image and watermark (use real file paths, not URLs)
        $imagePath = public_path('assets/images/order_images/cart-page.jpg'); // original image path
        $watermarkPath = public_path('assets/images/order_images/watermark.jpg'); // watermark path

        // Load the image
        $img = Image::make($imagePath);

        // Apply the watermark
        $img->insert($watermarkPath, 'bottom-right', 10, 10); // Watermark with padding

        // Save the watermarked image to a temporary location
        // $outputImagePath = storage_path('app/public/images/watermark/watermarked_image.jpg');
        $outputImagePath = public_path('assets/images/watermark/watermarked_image.jpg');
        $img->save($outputImagePath);

        // Return the watermarked image as a response to be displayed in the browser
        return response()->file($outputImagePath);
    }
}
