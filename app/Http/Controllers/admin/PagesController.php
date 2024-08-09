<?php

namespace App\Http\Controllers\admin;

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

        $is_product_page = '0';

        if(isset($is_product_page) && !empty($is_product_page)){
            $is_product_page = '1';
        }

        $pointer = base_path('resources/pages_json/'.$slug.'.json');

        if(file_exists($pointer)){
            Page::insert(['page_title'=>preg_replace('/[^\w\s]/',' ', $request->page_title),'slug'=>$slug,"added_by_admin"=>Auth::guard('admin')->id(),'is_product_page' => $is_product_page]);
            return redirect()->route('pages.index')->with('success','Page is created successfully');
        }
        else
        {
            return redirect()->route('pages.create')->with('error','You can not add this page');
        }
     }

    public function show($page)
      {
        $detail = Page::where('slug',$page)->with('pageSections')->first();
        $page_fields = read_json($detail->slug.'.json');
        dd($page_fields);
        if(isset($detail->pageSections->content)){
            $content = json_decode($detail->pageSections->content,true);
        }
        else{
          $content = [];
        }

        return view('admin.pages.edit',compact('detail','page_fields','content'));
      }

      public function update(Request $request)
        {
            $page_id = $request->page;

            $page = Page::where(['id' => $page_id])->first();

            $page_fields_data = read_json(strtolower($page->slug) . '.json');

            $sections = ($page_fields_data->sections);
            $this->flie_upload_path = config($page_fields_data->upload_pointer);

            $existing_page_content = $this->getPreContentPage($page_id);
            $pageContent = [];

            foreach ($sections as $section) {
                $fields = $section->fields;

                foreach ($fields as $field) {
                    $field_name = $field->name;
                    $field_value = "";

                    if ($field->type == 'images') {
                        $files = $request->file($field_name);
                        $uploadedImages = [];

                        if (!empty($files)) {
                            foreach ($files as $file) {
                                if ($file->isValid()) {
                                    $uploadFileName = $this->uploadService->handleUploadedImages($file, $this->flie_upload_path, $this->availableImageExtensions);
                                    if ($uploadFileName != "") {
                                        $uploadedImages[] = $uploadFileName;
                                    }
                                }
                            }
                            $field_value = $uploadedImages;
                        } else {
                            $existing_content = json_decode($existing_page_content, true);
                            $field_value = isset($existing_content[$field_name]) ? $existing_content[$field_name] : [];
                        }

                    } elseif (in_array($field->type, ['image', 'video', 'file'])) {
                        $file = $request->file($field_name);
                        if ($file && $file->isValid()) {
                            $uploadFileName = $this->uploadService->handleUploadedImages($file, $this->flie_upload_path, $this->availableImageExtensions);
                            if ($uploadFileName != "") {
                                $field_value = $uploadFileName;
                            }
                        } else {
                            $existing_content = json_decode($existing_page_content, true);
                            $field_value = isset($existing_content[$field_name]) ? $existing_content[$field_name] : null;
                        }
                    } else {
                        $field_value = $request->input($field_name, '');
                    }

                    $pageContent[$field_name] = $field_value;
                }
            }

            $encoded_page_data = json_encode($pageContent);

            // Update or create the PageSection
            PageSection:: updateOrCreate(
                ['page_id' => $page_id],
                ['content' => $encoded_page_data]
            );

            return redirect()->route('pages.index')->with('success', 'Page updated successfully');
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

    public function destroy($page)
     {
        Page::where('slug',$page)->delete();
        return redirect()->route('pages.index')->with('success','Page deleted successfully');
     }
}
