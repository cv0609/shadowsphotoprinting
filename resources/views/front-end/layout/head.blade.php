<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
{{-- <title>{{ $page_content['meta_title'] ?? 'Default Title' }}</title> --}}
<title>{{ html_entity_decode(ucfirst($page_content['meta_title']) ?? 'Default Title') }}</title>
<meta name="description" content="{{ $page_content['meta_description'] ?? 'Default Description' }}">
<link rel="icon" href="{{asset('assets/images/favicon.jpg') }}" type="image/x-icon">
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}">
<link rel="stylesheet" href="{{ asset('assets/fonts/stylesheet.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/slick-theme.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
<script src="https://js.stripe.com/v3/"></script>
