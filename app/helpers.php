<?php
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
