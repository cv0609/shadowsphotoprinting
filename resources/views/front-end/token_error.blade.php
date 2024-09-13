@extends('front-end.layout.main')
@section('content')

<section class="lost-your-password">
  <div class="container">
      <div class="your-password">
          <div class="row">
              <div class="col-lg-12">
                  <div class="nosidebar">
                    <h5 class="token-expired-msg">{{$message}}</h5>
                  </div>
              </div>
          </div>
      </div>
  </div>
</section>

@endsection
@section('scripts')
  
@endsection
