@extends('front-end.layout.main')
@section('content')
@php
   $PageDataService = app(App\Services\PageDataService::class);
   $productCount = $PageDataService->getShopProductsBySlug();
@endphp

 <div class="banner-slider fade-slider">

    @foreach ($page_content['shop_banner'] as $image)
    <div>
        <div class="image">


            <div class="slider-wrapper">
                <img src="{{ asset($image) }}" alt="{{ pathinfo($image, PATHINFO_FILENAME) }}">
                {{-- <img src="assets/images/Wp2print-starter-2.jpg" alt=""> --}}
            </div>
        </div>
    </div>
    @endforeach

    {{-- <div>
        <div class="image">
            <div class="slider-wrapper">
                <img src="assets/images/Wp2print-starter-3.jpg" alt="">
            </div>
        </div>
    </div> --}}
</div>
<!-- HERO SECTION -->

<section class="instructions">
    <div class="container">
        <div class="instructions-inner">
            <div class="so-widget">
                <h3>{{ $page_content['page_instruction_title'] }}</h3>
                <div class="tinymce ">
                    <ol>
                        {!! $page_content['page_instruction_description'] !!}
                    </ol>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="fcsg-wrap">
                <form id="uploadForm" class="fcsg-form" action="{{ route('shop-detail') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="uploading">
                        <div class="uploading-img" id="img-upload-container">
                            <p>{{ $page_content['select_images_for_upload_title'] }}</p>
                            <div id="selectedFiles"></div>
                            <a id="selectfiles" href="javascript:;" class="button" style="position: relative; z-index: 1;">Select images</a>
                            <a id="uploadfiles" href="javascript:;" class="button" style="display: none;">Upload images</button>
                            <div class="fupload-process" style="display: none; color: #fff">Processing image <span class="fcsg-process-num">1</span> of <span class="fcsg-process-total">1</span></div>
                            <div class="fupload-process" style="display: none; color: #fff">Processing: <span class="fcsg-process-name"></span></div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>


<section class="kad_product_wrapper">
    <div class="container">
        <div class="kad_product">

            <div class="row">

               
                {{-- <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="product-category">
                        <a href="javascript:void(0)">
                            <div class="cat-intrinsic">
                                <img src="assets/images/cart-page.jpg " alt="">
                            </div>
                            <div class="product-cat-title">
                                <h3>product-cat-title-area</h3>
                            </div>
                        </a>
                    </div>
                </div> --}}
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="product-category">
                        <a href="{{ url('our-products/gift-card') }}">
                            <div class="cat-intrinsic">
                                <img src="{{ asset('assets/images/gift-card-scaled.jpeg') }}" alt="">
                            </div>
                            <div class="product-cat-title">
                                <h3>Gift Card <small class="count">({{ $productCount['giftcardCount'] }})</small></h3>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="product-category">
                        <a href="{{ url('our-products/photos-for-sale') }}">
                            <div class="cat-intrinsic">
                                <img src="{{ asset('assets/images/The-Dish-Parkes.jpg') }}" alt="">
                            </div>
                            <div class="product-cat-title">
                                <h3>Image <small class="count">({{ $productCount['photoSaleCount'] }})</small></h3>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

   

</section>
@endsection
@section('scripts')
    <script src="{{ asset('assets/js/plupload.full.min.js') }}"></script>
    <script>
        $('.fade-slider').slick({
            autoplay: true,
            dots: true,
            infinite: true,
            speed: 500,
            fade: true,
            cssEase: 'linear'
        });

        // PLUPLOAD
        var fupload_nmb = 1;
        var is_first_upload = 1;
        var fupload_allowed_nmb = 1000;
        var fuploaded = false;
        var fupload_files = [];
        var fuploaded_files = [];
        var fuploading = false;
        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            file_data_name: 'image',
            browse_button : 'selectfiles', // you can pass an id...
            container: document.getElementById('img-upload-container'), // ... or DOM Element itself
            drop_element: document.getElementById('img-upload-container'), // ... or DOM Element itself
            url : '{{ route("shop-upload-image") }}',
            flash_swf_url : '{{ asset("assets/js/Moxie.swf") }}',
            silverlight_xap_url : '{{ asset("assets/js/Moxie.xap") }}',
            dragdrop: true,
            
            filters : {
                max_file_size : '100mb',
                mime_types: [
                    {title : "Image files", extensions : "jpg,jpeg,gif,png,webp"}
                ]
            },

            init: {
                PostInit: function() {
                    jQuery('#selectedFiles').html('').hide();
                    jQuery('#uploadfiles').hide();
                    jQuery('.fupload-process').hide();

                    document.getElementById('uploadfiles').onclick = function() {
                        if (!fuploading) {
                            fuploading = true;
                            fupload_files = [];
                            jQuery('#selectfiles').hide();
                            jQuery('#uploadfiles').hide();
                            jQuery('.fupload-loading').css('visibility', 'visible');
                            uploader.start();
                        }
                        return false;
                    };
                },
                
                FilesAdded: function(up, files) {
                    jQuery('#selectedFiles').show();
                    jQuery('.fupload-process').hide();
                    jQuery('#uploadfiles').show();
                    plupload.each(files, function(file) {
                        if (fupload_nmb <= fupload_allowed_nmb) {
                            document.getElementById('selectedFiles').innerHTML += '<p id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b> <i></i></p>';
                        }
                        fupload_nmb++;
                    });
                    if (fupload_nmb > fupload_allowed_nmb) {
                        up.splice(fupload_allowed_nmb);
                        jQuery('#selectfiles').addClass('disabled');
                    }
                },
                
                UploadProgress: function(up, file) {
                    document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                },

                
                BeforeUpload: function(up, file) {
                    fupload_files[fupload_files.length] = file;

                    var regex = /(?:\.([^.]+))?$/;
                    for (var i = 0; i < up.files.length; i++) {
                        if (file.id == up.files[i].id) {
                            var ext = regex.exec(up.files[i].name)[1];
                            up.settings.multipart_params['Content-Type'] = file.type;
                            up.settings.multipart_params['_token'] = document.querySelector('#uploadForm [name="_token"]').getAttribute('value');
                            up.settings.multipart_params['is_first_upload'] = is_first_upload;
                        }
                    }
                },

                FileUploaded: function(uploader, file, result) {
                    if( result.status == 200 ){
                        resp = JSON.parse(result.response);
                        console.log(resp);
                        document.querySelector('#uploadForm [name="_token"]').setAttribute('value', resp.csrf_token);
                        is_first_upload = 0;
                    }
                },

                UploadComplete: function(files) {
                    window.location.replace(document.getElementById('uploadForm').getAttribute('action'));
                },

                Error: function(up, err) {
                    alert("Upload error: "+err.message); // err.code
                }
            }
        });

        uploader.init();
    </script>
@endsection
