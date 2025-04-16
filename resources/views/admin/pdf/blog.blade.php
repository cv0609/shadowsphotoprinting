<!DOCTYPE html>
<html>
<head>
    <title>{{$blog->title}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>

    <h1>{{ $blog->title }}</h1>
    <p>{{ $blog->updated_at->format('F d, Y') }}</p>
    <br/>
    <br/>

<section class="single-article">
    <div class="container">
        <div class="single-arti">
            <div class="category-kt">
                <a href="javascript:void(0)">Uncategorized</a>
            </div>
            <div class="benefit">
                <h3>{{ $blog->title }}</h3>
                <div class="kt_color_gray">
                    <span>{{ $blog->updated_at->format('F d, Y') }}</span>
                    <span>by</span>
                    @php
                        $authorName = !empty($blog->user->first_name)
                            ? trim($blog->user->first_name . ' ' . ($blog->user->last_name ?? ''))
                            : ($blog->user->username ?? 'Terri Pangas');
                    @endphp
                
                    <span> <a href="#">{{$authorName}}</a> </span>

                    <div class="shadtpang">
                        <img src="{{public_path($blog->image)}}" alt="{{asset($blog->image)}}" style="width:100%">
                        {!! html_entity_decode($blog->description) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</body>
</html>