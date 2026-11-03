@php
   $PageDataService = app(App\Services\PageDataService::class);
   $index = $PageDataService->dashboard_index();
@endphp
<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name='robots' content='{{$index=='1' ? config('constant.dashboard_index.on') : config('constant.dashboard_index.off')}}' />
<meta name="google-site-verification" content="QscuU-oHKOGqK2i0FisB3Hzy7Teuqry8iBl2TaWjFyM" />
<title>{!! html_entity_decode(ucfirst($page_content['meta_title'] ?? '') ?: 'Default Title', ENT_QUOTES, 'UTF-8') !!}</title>
<meta name="description" content="{!! str_replace('"', '&quot;', html_entity_decode($page_content['meta_description'] ?? 'Default Description', ENT_QUOTES, 'UTF-8')) !!}">
<link rel="icon" href="{{asset('assets/images/favicon.jpg') }}" type="image/x-icon">

<link rel="canonical" href="{{ request()->url() }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" />

<!-- Open Graph Meta Tags -->
<meta property="og:type" content="website">
<meta property="og:site_name" content="Shadows Photo Printing">
<meta property="og:title" content="{!! str_replace('"', '&quot;', html_entity_decode(!empty($page_content['meta_title']) ? $page_content['meta_title'] : 'Photo Prints Online Australia, Professional Photographer Glenreagh NSW, Canvas Photo Printing, Shadows Photo Printing', ENT_QUOTES, 'UTF-8')) !!}">
<meta property="og:description" content="{!! str_replace('"', '&quot;', html_entity_decode(!empty($page_content['meta_description']) ? $page_content['meta_description'] : 'Photo Prints Online in Australia - Professional Photographer & Canvas Photo Printing in Glenreagh NSW - At Shadows Photo Printing we offer a professional photo printing by professional Photographers who take the time to check the quality of your image before we print. Contact us today.', ENT_QUOTES, 'UTF-8')) !!}">
<meta property="og:url" content="{{ request()->is('/') ? 'https://shadowsphotoprinting.com.au/' : request()->url() }}">
<meta property="og:image" content="{{ !empty($page_content['og_image']) ? asset($page_content['og_image']) : 'https://shadowsphotoprinting.com.au/assets/images/logo.png' }}">
<meta property="og:image:alt" content="Professional photo prints and canvas printing by Shadows Photo Printing">
<meta property="og:locale" content="en_AU">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" href="{{ asset('assets/fonts/SourceSansPro-Regular.woff2') }}" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="{{ asset('assets/fonts/SourceSansPro-Bold.woff2') }}" as="font" type="font/woff2" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">

@if(!empty($page_content['main_banner']) && is_array($page_content['main_banner']) && !empty($page_content['main_banner'][0]))
<link rel="preload" as="image" href="{{ asset($page_content['main_banner'][0]) }}" fetchpriority="high">
@endif

<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}">
<link rel="stylesheet" href="{{ asset('assets/fonts/stylesheet.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/slick-theme.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/brand.css') }}?v=4">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v=31">
<link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}?v=6">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
<script src="https://js.stripe.com/v3/"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-27QW0FL5D9"></script>
<script>
   window.dataLayer = window.dataLayer || [];
   function gtag(){dataLayer.push(arguments);}
   gtag('js', new Date());

   gtag('config', 'G-27QW0FL5D9');
 </script>

<noscript>
   <img
      height="1"
      width="1"
      style="display:none"
      alt="fbpx"
      src="https://www.facebook.com/tr?id=3392352141053321&ev=PageView&noscript=1"
   />
</noscript>

<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
   new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
   j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
   'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
   })(window,document,'script','dataLayer','GTM-TC2ZRBMT');</script>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-0B1KL1Y0PL"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag() {dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('js', 'G-0B1KL1Y0PL');
</script>

@include('front-end.layout.schema')
   <!-- End Google Tag Manager -->

   <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TC2ZRBMT"
   height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
   <!-- End Google Tag Manager (noscript) -->



