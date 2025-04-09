@extends('front-end.layout.main')
@section('styles')
<style>

	.photosec {
    background: #000;
	padding: 90px 0;
}
.head-box.custom-wrapper {
    padding: 40px;
    text-align: center;
    max-width: 800px;
    margin: 0 auto 70px auto;
}
.photosec p a {
    text-decoration: underline!important;
    color: #ffc205;
}
.photosec .row {
    align-items: center;
}
</style>

@endsection
@section('content')
<section class="photosec">
    <div class="container ">
		<div class="row">
			<div class="col-12">
				<div class="head-box custom-wrapper">
					<h2  class="hdd">Become a Shadows Photo Printing Photographer Brand Ambassador</h2>
					<p style="margin-bottom: 10px;">and get rewarded for contributing your art & expertise to Shadows!</p>
				</div>
			</div>
		</div>
        <div class="row">
                <!-- Add row here -->
                <div class="col-lg-6">
                    <div class="entry-img">
                        <figure data-aos="fade-right" class="aos-init aos-animate">
                            <img src="{{asset('assets/images/img-left.jpg')}}" alt="Side Image">
                        </figure>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="entry-text">
                        <div class="widget-title aos-init aos-animate" data-aos="fade-left">
                            <h3>Welcome to Shadows Photo Printing</h3>
                            <div class="textwidget">
                                <p style="margin-bottom: 10px;">Become a Shadows Photo Printing Photographer Brand Ambassador and receive rewards for contributing your art and expertise to SPP. </p>

								<p style="margin-bottom: 10px;">Each quarter, we invite our PBAs to contribute their preferred choice of content — including image galleries with download access and rights, prints or photo canvas images, guest blog posts, or Shadows Photo Printings product images. </p>

								<p style="margin-bottom: 10px;">In return, you will receive SPP Shutter Bucks, exclusive access to early promotions, an affiliate link to earn commissions on sales generated, a 20% discount code for your clients, and much more. </p>

								<p style="margin-bottom: 10px;"><a href="{{route('apply-form')}}">SHADOWS PHOTO PRINTING PHOTOGRAPHER BRAND AMBASSADOR APPLICATION</a></p>
								<p><small>*Currently only open to US applicants 18 and older*</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</section>
@endsection

