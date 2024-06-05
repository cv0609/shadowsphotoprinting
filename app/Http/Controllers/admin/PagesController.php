<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminPageRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Page;
use App\Models\PageSection;
use App\Services\UploadService;

class PagesController extends Controller
{
    protected $uploadService;
    protected $availableImageExtensions;
    protected $flie_upload_path;

    public function __construct()
    {
        $this->uploadService = new uploadService();
        $this->availableImageExtensions = config('file-upload-extensions.image');
        $this->flie_upload_path = '';

    }
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

    public function update(Request $request)
     {
        $page_id = $request->page;

        $page = Page::where(['id' => $page_id])->first();
        $page_fields_data = read_json(strtolower($page->slug) . '.json');

        $sections = $page_fields_data->sections;
        $this->flie_upload_path = config($page_fields_data->upload_pointer);
        $existing_page_content = $this->getPreContentPage($page_id);

        foreach ($sections as $section) {
            $fields = $section->fields;

            foreach ($fields as $field) {
                $field_name = $field->name;
                $field_value = "";
                if($field->type =='images')
                {
                    if (preg_match('/^([^\[]+)/', $field->name, $matches)) {
                        // $matches[1] contains the matched part
                        $field_name = $matches[1];

                    }
                }
                if (($field->type == 'image' || $field->type == 'video' || $field->type == 'file')) {
                    $file = $request->file($field_name);

                    if (!empty($file)) {

                        $uploadFileName = $this->uploadService->handleUploadedImages($file, $this->flie_upload_path, $this->availableImageExtensions);

                        if ($uploadFileName != "") {
                            $field_value = $uploadFileName;
                        }
                    } else if (array_key_exists($field_name, $existing_page_content)) {

                        $field_value = $existing_page_content[$field_name];
                    } else {
                        //todo
                    }
                } else {
                    $field_value = $request->$field_name;
                }
                $pageContent[$field->name] = $field_value;
            }
        }

        $encoded_page_data = json_encode($pageContent);

        $final_data = array();
        $final_data['page_id'] = $page_id;
        $final_data['content'] = $encoded_page_data;

        if (PageSection::where('page_id', $page_id)->exists()) {

            $update_status = PageSection::where('page_id', $page_id)->update($final_data);
        } else {
            $update_status = PageSection::create($final_data);
        }

        return redirect()->route('pages.index')->with('success','Page updated successfully');
     }

     private function getPreContentPage($page_id)
     {
         $Page_preContent = PageSection::where(['page_id' => $page_id])->select('content')->latest('id')->first();

         if (!empty($Page_preContent['content'])) {
             $data = $Page_preContent['content'];
         } else {
             $data = array();
         }
         return $data;
     }
}
