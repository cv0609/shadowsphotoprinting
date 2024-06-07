<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class UploadService
{

    // public function upload_test(){
    //     return "fun ru7ningh";
    // }

    public function handleUploadedImages(UploadedFile $file, $destinationPath, $availableExtensions)
    {
        $isValid = $this->validateFileExtension($file->getClientOriginalExtension(), $availableExtensions);
        
        if ($isValid) {
            $fileName = rand().'-'.$file->getClientOriginalName();
            $file->move($destinationPath, $fileName);
             return $destinationPath.'/'.$fileName;
        }
        return false;
    }


     public function handleUploadedfile(UploadedFile $file, $destinationPath, $availableExtensions)
    {
        // echo $destinationPath;
        // print_r($availableExtensions);
        // echo $file->getClientOriginalExtension();
        // echo $file->getClientOriginalName();
        // rand() . ''
        // exit;
        $isValid = $this->validateFileExtension($file->getClientOriginalExtension(), $availableExtensions);

        if ($isValid) {
            $fileName = rand().'-'.$file->getClientOriginalName();
            $file->move($destinationPath, $fileName);
            // echo $fileName;
            return $fileName;
        }
        return false;
    }

    private function validateFileExtension($fileExtension, $availableExtensions) {
        if(in_array($fileExtension,$availableExtensions)){
            return true;
        }else{
            return false;
        }
    }
}
