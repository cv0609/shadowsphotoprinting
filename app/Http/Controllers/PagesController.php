<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Blog;
use App\Models\GiftCardCategory;
use App\Models\PhotoForSaleCategory;
use App\Models\PhotoForSaleProduct;
use App\Models\HandCraftCategory;
use App\Models\HandCraftProduct;
use App\Models\PhotoForSaleSizePrices;
use App\Services\PageDataService;
use App\Services\AfterPayService;
use App\Http\Requests\GetAQuoteRequest;
use App\Mail\QuoteMail;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Expr\FuncCall;
use App\Models\ProductCategory;
use App\Models\Newzletter;
use App\Models\Product;

class PagesController extends Controller
{
    private $PageDataServices;
    private $AfterPayService;

    public function __construct(PageDataService $PageDataService, AfterPayService $AfterPayService)
    {
        $this->PageDataServices = $PageDataService;
        $this->AfterPayService = $AfterPayService;
    }
    public function pages(Request $request)
    {
        // Get the full path
        $path = $request->path();
        // Extract the last segment
        $segments = explode('/', $path);
        if (in_array('our-products', $segments) && end($segments) != 'our-products') {
            $slug = ProductCategory::where('name', str_replace('-', ' ', end($segments)))->select('slug')->first();

            $slug = $slug['slug'];
        } else {
            $slug = end($segments);
        }

        // Default to 'home' if the slug is empty
        if (empty($slug)) {
            $slug = 'home';
        }
        $page_info = Page::where('slug', $slug)->with('pageSections')->first();

        if ($page_info && isset($page_info->pageSections) && !empty($page_info->pageSections)) {
            $page_content = json_decode($page_info->pageSections['content'], true);
            $page_content['slug'] = $page_info['slug'];

            if ($page_info['is_product_page'] == '1') {
                return view('front-end/common-product', compact('page_content', 'page_info'));
            } else {
                return view('front-end/' . $slug, compact('page_content', 'page_info'));
            }
        } else {
            abort(404);
        }
    }

    public function pages2(Request $request)
    {
        // Get the full path
        $path = $request->path();

        // Extract the last segment
        $segments = explode('/', $path);
        if (in_array('our-products', $segments) && end($segments) != 'our-products') {
            $slug = ProductCategory::where('name', str_replace('-', ' ', end($segments)))->select('slug')->first();

            $slug = $slug['slug'];
        } else {
            $slug = end($segments);
        }

        // Default to 'home' if the slug is empty
        if (empty($slug)) {
            $slug = 'home';
        }

        $page_info = Page::where('slug', 'home')->with('pageSections')->first();

        if ($page_info && isset($page_info->pageSections) && !empty($page_info->pageSections)) {
            $page_content = json_decode($page_info->pageSections['content'], true);
            $page_content['slug'] = $page_info['slug'];

            if ($page_info['is_product_page'] == '1') {
                return view('front-end/common-product', compact('page_content', 'page_info'));
            } else {
                return view('front-end/' . $slug, compact('page_content', 'page_info'));
            }
        } else {
            abort(404);
        }
    }

    public function blogDetail($slug)
    {
        $blog_details = Blog::where('slug', $slug)->first();

        $previousBlog = Blog::where('id', '<', $blog_details->id)
            ->orderBy('id', 'desc')
            ->first();

        $nextBlog = Blog::where('id', '>', $blog_details->id)
            ->orderBy('id', 'asc')
            ->first();

        $page_content = ["meta_title" => $blog_details['slug'], "meta_description" => $blog_details['description']];
        return view('front-end/blog_detail', compact('blog_details', 'previousBlog', 'nextBlog', 'page_content'));
    }

    public function PhotosForSale(Request $request, $slug = null)
    {
        $page_content = [];

        $page_content = ["meta_title" => config('constant.pages_meta.photos_for_sale.meta_title'), "meta_description" => config('constant.pages_meta.photos_for_sale.meta_description')];

        if ($slug == null) {
            $products = PhotoForSaleProduct::paginate(10);
        } else {
            $caregory = PhotoForSaleCategory::where('slug', $slug)->first();
            $products = PhotoForSaleProduct::where('category_id', $caregory->id)->paginate(10);
        }

        $productCategories = PhotoForSaleCategory::get();

        if (!empty($productCategories)) {
            foreach ($productCategories as $productCategory) {
                if ($request->url() == route('photos-for-sale', ['slug' => $productCategory->slug])) {
                    $page_content = [
                        "meta_title" => $productCategory->slug . ' | Shadows Photo Printing',
                        "meta_description" => config('constant.pages_meta.photos_for_sale.meta_description')
                    ];
                }
            }
        }


        return view('front-end/photos-for-sale', compact('products', 'productCategories', 'page_content'));
    }

    public function PhotosForSaleDetails($slug = null)
    {
        $productDetails = PhotoForSaleProduct::where('slug', $slug)->first();

        // Get unique size_id records with their size names for the given product_id
        $uniqueSizeRecords = PhotoForSaleSizePrices::with('getSizeById')->select('size_id')
            ->where('product_id', $productDetails->id)
            ->groupBy('size_id')
            ->get();

        $uniqueTyepeRecords = PhotoForSaleSizePrices::with('getTypeById')->select('type_id')
            ->where('product_id', $productDetails->id)
            ->groupBy('type_id')
            ->get();

        $photoForSaleSizePricesData = PhotoForSaleSizePrices::where('product_id', $productDetails->id)->get();

        $relatedProduct = PhotoForSaleProduct::where('slug', '!=', $slug)->paginate(10);

        $page_content = [
            "meta_title" => $productDetails->meta_title . ' | Shadows Photo Printing',
            "meta_description" => $productDetails->meta_description
        ];

        return view('front-end/photos-for-sale-details', compact('productDetails', 'relatedProduct', 'uniqueSizeRecords', 'uniqueTyepeRecords', 'photoForSaleSizePricesData', 'page_content'));
    }


    public function handCraftDetails($slug = null)
    {

        $productDetails = HandCraftProduct::where('slug', $slug)->first();
        $relatedProduct = HandCraftProduct::where('slug', '!=', $slug)->paginate(10);
        $page_content = ["meta_title" => $productDetails['slug'] . ' | Shadows Photo Printing', "meta_description" => $productDetails['product_description']];
        return view('front-end/hand-craft-details', compact('productDetails', 'relatedProduct', 'page_content'));
    }


    public function PhotoForSaleByCategory($slug) {}

    public function giftCard()
    {
        $blogs =  GiftCardCategory::get();
        $page_content = ["meta_title" => config('constant.pages_meta.gift_card.meta_title'), "meta_description" => config('constant.pages_meta.gift_card.meta_description')];

        return view('front-end/giftcard', compact('blogs', 'page_content'));
    }

    public function handCraft($slug = null)
    {
        $page_content = [];
        $page_content = ["meta_title" => $slug . ' | Shadows Photo Printing', "meta_description" => config('constant.pages_meta.hand_craft.meta_description')];

        if ($slug == null) {
            $products = HandCraftProduct::paginate(10);
        } else {
            $caregory = HandCraftCategory::where('slug', $slug)->first();
            $products = HandCraftProduct::where('category_id', $caregory->id)->paginate(10);
            // $page_content = ["meta_title"=>$caregory['slug'],"meta_description"=>$caregory['name']];

        }


        $productCategories = HandCraftCategory::get();


        return view('front-end/hand-craft', compact('products', 'productCategories', 'page_content'));
    }


    public function giftCard_detail($slug)
    {
        $blog_detail = GiftCardCategory::where(["slug" => $slug])->first();
        $related_products =  GiftCardCategory::where("slug", "!=", $slug)->get();
        $page_content = ["meta_title" => $blog_detail['slug'] . ' | Shadows Photo Printing', "meta_description" => $blog_detail['product_description']];

        return view('front-end/giftcard_detail', compact('blog_detail', 'related_products', 'page_content'));
    }

    public function sendQuote(GetAQuoteRequest $request)
    {
        $email = $request->email;

        $data['name'] = $request->name;
        $data['last_name'] = $request->last_name;
        $data['phone_number'] = $request->phone_number;
        $data['requested'] = $request->requested;
        $data['message'] = $request->message;
        $data['email'] = $request->email;

        if (isset($data) && !empty($data)) {
            Mail::to(env('APP_MAIL'))->send(new QuoteMail($data));
        }
        return redirect()->back()->with('success', 'Quote message sent successfully.');
    }

    // public function giftcardSearch(Request $request){
    //    dd($request->all());
    // }

    public function promotions()
    {
        $newzletter = Newzletter::where(['is_active' => '1'])->get();
        $page_content = ["meta_title" => "Promotions", "meta_description" => "Promotions"];
        return view('front-end.newz_letter', compact('newzletter', 'page_content'));
    }

    public function promotionDetail($slug)
    {
        $promotionDetail = Newzletter::where(['slug' => $slug, 'is_active' => '1'])->first();
        $previousPromotion = Newzletter::where('id', '<', $promotionDetail->id)
            ->orderBy('id', 'desc')
            ->first();

        $nextPromotion = Newzletter::where('id', '>', $promotionDetail->id)
            ->orderBy('id', 'asc')
            ->first();
        $page_content = ["meta_title" => "Promotions", "meta_description" => "Promotions"];
        return view('front-end.newz_letter_detail', compact('promotionDetail', 'page_content', 'previousPromotion', 'nextPromotion'));
    }

    public function bulkprints()
    {
        $productCategories = ProductCategory::where('slug', '!=', 'photos-for-sale')->where('slug', '!=', 'gift-card')->where('slug', '!=', 'hand-craft')->where('slug', '!=', 'test-print')->where('slug', '!=', 'test')->get();
        $products = Product::paginate(10);

        $page_content = ["meta_title" => "Bulk prints", "meta_description" => "Bulk prints"];
        return view('front-end.bulkprints', compact('productCategories', 'page_content', 'products'));
    }

    public function bulkprints_details($slug)
    {
        $related_products = [];
        $productDetails = Product::where('slug', $slug)->first();
        if (isset($productDetails) && !empty($productDetails)) {
            $category_id = $productDetails->category_id;
            $related_products = Product::where('category_id', $category_id)->get();
        }
        $page_content = ["meta_title" => "Bulk prints details", "meta_description" => "Bulk prints details"];
        return view('front-end.bulkprints-details', compact('productDetails', 'page_content', 'related_products'));
    }

    public function downloadPDF()
    {
        $filePath = public_path('pdf/PhotoBingo10.pdf'); // PDF file path
        // Check if the file exists
        if (file_exists($filePath)) {
            return response()->download($filePath, 'PhotoBingo10.pdf');
        } else {
            return response()->json(['error' => 'File not found.'], 404);
        }
    }
    public function accordion()
    {
    $items = [
    [
        'main_heading' => 'Printing Process & Quality',
        'faqs' => [
            [
                'title' => 'Do you get your printing done from other companies?',
                'content' => 'No, never. All of our photo and canvas prints at Shadows Photo Printing are printed in-house in Australia. We never outsource any stage of the printing process. This guarantees consistent quality, professional handling, and quick turnaround times.'
            ],
            [
                'title' => 'Are your photo prints printed in Australia?',
                'content' => 'Yes, all of our prints are proudly made in Australia at our small family-run printing & gift shop in Glenreagh, NSW. Each order is printed and packed by our local family with love and care.'
            ],
            [
                'title' => 'Do you have professional photo printing services available?',
                'content' => 'Yes, we have professional photo printing with high-quality materials, high colour accuracy, and various sizing options—sent throughout Australia.'
            ],
        ]
        ],
       [
        'main_heading' => 'Products & Custom Orders',
        'faqs' => [
            [
                'title' => 'Do you print scrapbook pages in Glenreagh NSW?',
                'content' => 'Yes! We provide premium scrapbook page printing in Glenreagh NSW in a range of sizes and finishes ideal for memory keeping.'
            ],
            [
                'title' => 'What do I get to order online?',
                'content' => "You can order online photo prints, canvas prints, and wall art from our website. Select your size, upload your photo, and we'll do the rest."
            ],
            [
                'title' => 'Can I have a bulk or custom-sized order?',
                'content' => 'Yes. We do accept bulk orders and the best part is the more you order the better the discount. Yes, we accept print custom sizes. Contact us directly via our website for further information or a custom quote.'
            ]
        ]
    ],
    [
        'main_heading' => 'Shipping & Delivery',
        'faqs' => [
            [
                'title' => 'How long will it take to get my order?',
                'content' => 'We normally print and send orders within 2–5 business days. Delivery times can vary based on your location within Australia.'
            ],
            [
                'title' => 'Do you ship photo prints throughout Australia?',
                'content' => 'Yes, we do ship Australia-wide. Regardless of where you are, your prints will be properly packaged and shipped straight to your doorstep.'
            ],
            [
                'title' => 'Is shipping free?',
                'content' => 'We don’t offer free shipping, but we do have a simple flat-rate shipping fee Australia-wide.
                             If you’d prefer, in-house pick-up is completely free for all online orders.'
            ]
        ]
    ],
    [
        'main_heading' => 'Customer Reviews & Trust',
        'faqs' => [
            [
                'title' => 'Where do I find customer reviews?',
                'content' => "You can also read genuine customer feedback on our social media and Google Business Profile. We adore hearing about how our prints brighten up people's living spaces!"
            ],
            [
                'title' => 'How do I know your service is reliable?',
                'content' => "We have many of satisfied customers and 5-star ratings, so we're dedicated to providing great products and service each and every time as if we stuff up – we pay for it!"
            ],
        ]
    ],
    [
        'main_heading' => 'Care Instructions',
        'faqs' => [
            [
                'title' => 'How should I care for my canvas print?',
                'content' => 'Place your canvas outside of direct sunlight and humid environments. You can lightly dust it with a dry cloth to maintain its freshness.'
            ],
        ]
    ]
];

        return view('front-end.faq', compact('items'));
    }

    public function moreInfo()
    {
        $page_content = [
            'meta_title' => 'More Info - Shadows Photo Printing & Gift Shop',
            'meta_description' => 'Learn more about our professional photography services, high-quality prints, and commitment to preserving your precious memories.',
            'slug' => 'more-info'
        ];

        return view('front-end.more-info', compact('page_content'));
    }
}
