<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminPageRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Page;

class PagesController extends Controller
{
    public function index()
    {
        $pages = Page::get();
        return view('admin.pages.index',compact('pages'));
    }

    public function create()
     {
         return view('admin.pages.add');
     }

    public function store(AdminPageRequest $request)
     {
        $slug = Str::slug($request->page_title);
        Page::insert(['page_title'=>$request->page_title,'slug'=>$slug,"added_by_admin"=>Auth::guard('admin')->id()]);
        return redirect()->route('pages.index')->with('success','Page is created successfully');
     }

    public function show($page)
      {
        $detail = Page::where('slug',$page)->first();
        $page_fields = read_json($detail->slug.'.json');
        return view('admin.pages.edit',compact('detail','page_fields'));
      }

    // public function update(Request $request)
    //  {
    //     $page_fields_data = read_json(strtolower($page->slug) . '.json');

    //     $sections = $page_fields_data->sections;
    //     foreach ($sections as $section) {
    //         $fields = $section->fields;

    //         foreach ($fields as $field) {
    //             $field_name = $field->name;
    //             $field_value = "";
    //             if($field->type =='multiselect')
    //             {
    //                 if (preg_match('/^([^\[]+)/', $field->name, $matches)) {
    //                     // $matches[1] contains the matched part
    //                     $field_name = $matches[1];

    //                 }
    //             }
    //             if (($field->type == 'image' || $field->type == 'video' || $field->type == 'file')) {
    //                 $file = $request->$field_name;

    //                 if (!empty($file)) {

    //                     $uploadFileName = $this->uploadService->handleUploadedImages($file, $this->flie_upload_path, $this->availableImageExtensions);

    //                     if ($uploadFileName != "") {
    //                         $field_value = $uploadFileName;
    //                     }
    //                 } else if (array_key_exists($field_name, $existing_page_content)) {

    //                     $field_value = $existing_page_content[$field_name];
    //                 } else {
    //                     //todo
    //                 }
    //             } else {
    //                 $field_value = $request->$field_name;
    //             }
    //             $pageContent[$field->name] = $field_value;
    //         }
    //     }
    //  }
}
