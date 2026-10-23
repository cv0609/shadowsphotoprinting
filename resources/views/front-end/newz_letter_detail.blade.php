@extends('front-end.layout.main')
@section('content')
<div class="kt-bc-nomargin" id="recipi">
    <div class="adbreadcrumbs">
        <div class="container">
            <div class="breadcrumbs-wrapper">
                <span><a href="{{ url('/') }}">Home</a></span>
                <span class="bc-delimiter">»</span>
                {{-- <span><a href="#">uncategorized</a></span>
                <span class="bc-delimiter">»</span> --}}
                <span><h1>{{ config('constant.promotions_h1.' . $promotionDetail['slug']) ?? $promotionDetail['title'] }}</h1></span>
            </div>
        </div>
    </div>
</div>

<section class="single-article">
    <div class="container">
        <div class="single-arti">
            {{-- <div class="category-kt">
                <a href="javascript:void(0)">Uncategorized</a>
            </div> --}}
            <div class="benefit">
                {{-- <h3>{{ $promotionDetail['title'] }}</h3> --}}
                <div class="kt_color_gray">
                    {{-- <span>{{ date("F d,Y",$promotionDetail['update_at']) }}</span>
                    <span>by</span>
                    <span> <a href="shadtpang.html">Terri Pangas</a> </span> --}}

                    <div class="shadtpang">
                        @php
                            $contentHtml = $promotionDetail['content'] ?? '';
                            $decodedContent = html_entity_decode($contentHtml);
                            // Demote any inner <h1> in email/newsletter body to <h2> so there's only one page H1
                            $decodedContent = preg_replace('/<(\/?)h1\b/i', '<$1h2', $decodedContent);

                            $imageAltMap = config('constant.promotion_image_alts', []);
                            $defaultAlt = !empty($promotionDetail['title']) 
                                ? ($promotionDetail['title'] . ' - Shadows Photo Printing') 
                                : 'Shadows Photo Printing';

                            $decodedContent = preg_replace_callback('/<img\b([^>]*?)>/i', function($match) use ($imageAltMap, $defaultAlt) {
                                $attrs = $match[1];
                                
                                $src = '';
                                if (preg_match('/src=([\'"])(.*?)\1/i', $attrs, $srcMatch)) {
                                    $src = $srcMatch[2];
                                }

                                $targetAlt = null;
                                if (!empty($src)) {
                                    if (isset($imageAltMap[$src])) {
                                        $targetAlt = $imageAltMap[$src];
                                    } else {
                                        foreach ($imageAltMap as $key => $altVal) {
                                            if (strpos($src, $key) !== false) {
                                                $targetAlt = $altVal;
                                                break;
                                            }
                                        }
                                    }
                                }

                                if ($targetAlt === null) {
                                    if (preg_match('/\balt=([\'"])(.*?)\1/i', $attrs, $existingAltMatch) && trim($existingAltMatch[2]) !== '') {
                                        return $match[0];
                                    }
                                    $targetAlt = $defaultAlt;
                                }

                                $escapedAlt = htmlspecialchars($targetAlt, ENT_QUOTES, 'UTF-8');

                                if (preg_match('/\balt=([\'"]).*?\1/i', $attrs)) {
                                    $newAttrs = preg_replace_callback('/\balt=([\'"]).*?\1/i', function() use ($escapedAlt) {
                                        return 'alt="' . $escapedAlt . '"';
                                    }, $attrs);
                                } else {
                                    $newAttrs = $attrs . ' alt="' . $escapedAlt . '"';
                                }

                                return '<img' . $newAttrs . '>';
                            }, $decodedContent);
                        @endphp
                        {!! $decodedContent !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="previous-link">
    <div class="container">
    <div class="promotion-container">

      @if($previousPromotion != null)
        <div class="previous-box">
            <a href="{{ route('promotion-detail',['slug'=>$previousPromotion->slug]) }}">
                <span class="kt_color_gray">Previous Post</span>
                <span class="kt_postlink_title">{{ $previousPromotion->title }}</span>
            </a>
        </div>
      @endif

       @if($nextPromotion != null)
        <div class="next-link">
            <a href="{{ route('promotion-detail',['slug'=>$nextPromotion->slug]) }}">
                <span class="kt_color_gray">NEXT POST</span>
                <span class="kt_postlink_title">{{ $nextPromotion->title }}</span>
            </a>
        </div>
        @endif
    </div>
    </div>

</section>
@endsection

@section('scripts')
    <script>
        $('.fade-slider').slick({
            autoplay: true,
            dots: true,
            infinite: true,
            speed: 500,
            fade: true,
            cssEase: 'linear'
        });

        $(document).ready(function(){
            $('.restoration-cls').attr('href', "{{ url('home') }}");
        });
    </script>

    <script>
        $(document).ready(function(){
            $('.custom-design').css('margin','0 auto');
        })
    </script>

@endsection
