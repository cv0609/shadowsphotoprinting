@php
   $PageDataService = app(App\Services\PageDataService::class);
   $index = $PageDataService->dashboard_index();
@endphp
<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name='robots' content='{{$index=='1' ? config('constant.dashboard_index.on') : config('constant.dashboard_index.off')}}' />
<meta name="google-site-verification" content="QscuU-oHKOGqK2i0FisB3Hzy7Teuqry8iBl2TaWjFyM" />
{{-- <title>{{ $page_content['meta_title'] ?? 'Default Title' }}</title> --}}
<title>{{ html_entity_decode(ucfirst($page_content['meta_title'] ?? '') ?? 'Default Title') }}</title>
<meta name="description" content="{{ $page_content['meta_description'] ?? 'Default Description' }}">
<link rel="icon" href="{{asset('assets/images/favicon.jpg') }}" type="image/x-icon">

<link rel="canonical" href="{{ request()->url() }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" />

<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" >

<link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}">
<link rel="stylesheet" href="{{ asset('assets/fonts/stylesheet.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/slick-theme.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/brand.css') }}?v=4">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v=9">
<link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}?v=2">
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

<script type="application/ld+json">
   {
     "@context": "https://schema.org",
     "@graph": [
       {
         "@type": "LocalBusiness",
         "@id": "https://shadowsphotoprinting.com.au/#localbusiness",
         "name": "Shadows Affordable Memories",
         "url": "https://shadowsphotoprinting.com.au/",
         "telephone": "+61 2 6602 9424",
         "email": "shadowsphotoprinting@outlook.com",
         "priceRange": "$$",
         "image": "https://shadowsphotoprinting.com.au/assets/images/favicon.jpg ",
         "logo": "https://shadowsphotoprinting.com.au/assets/images/favicon.jpg ",
         "address": {
           "@type": "PostalAddress",
           "addressLocality": "Glenreagh",
           "addressRegion": "NSW",
           "addressCountry": "AU"
         },
         "areaServed": {
           "@type": "Country",
           "name": "Australia"
         }
       },
       {
         "@type": "Organization",
         "@id": "https://shadowsphotoprinting.com.au/#organization",
         "name": "Shadows Affordable Memories",
         "url": "https://shadowsphotoprinting.com.au/",
         "logo": "https://shadowsphotoprinting.com.au/assets/images/favicon.jpg"
       },
       {
         "@type": "WebSite",
         "@id": "https://shadowsphotoprinting.com.au/#website",
         "url": "https://shadowsphotoprinting.com.au/",
         "name": "Shadows Photo Printing",
         "publisher": {
           "@id": "https://shadowsphotoprinting.com.au/#organization"
         }
       },
       {
         "@type": "WebPage",
         "@id": "https://shadowsphotoprinting.com.au/#webpage",
         "url": "https://shadowsphotoprinting.com.au/",
         "name": "Shadows Affordable Memories | Photo Printing Australia",
         "isPartOf": {
           "@id": "https://shadowsphotoprinting.com.au/#website"
         },
         "about": {
           "@id": "https://shadowsphotoprinting.com.au/#localbusiness"
         },
         "primaryImageOfPage": {
           "@type": "ImageObject",
           "url": "https://shadowsphotoprinting.com.au/assets/images/favicon.jpg"
         }
       }
     ]
   }
</script>
   <!-- End Google Tag Manager -->

   <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TC2ZRBMT"
   height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
   <!-- End Google Tag Manager (noscript) -->



