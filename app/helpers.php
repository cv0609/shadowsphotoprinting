<?php

function read_json($file_name = ''){
    $pointer = base_path('resources/pages_json/'.$file_name);
   if(file_exists($pointer)){
    dd(json_decode(file_get_contents($pointer),true));
    $data = json_decode(file_get_contents($pointer));
   
   }else{
        $data = null;
    }
    return $data;
}
