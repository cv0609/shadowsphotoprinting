@extends('layout.main')
@section('content')
{{-- @php dd($page_content); @endphp --}}
<section class="about-printing">
    <div class="container">
        <div class="about-printing">
            {!! html_entity_decode($page_content['fun-facts-content']) !!}
            {{-- <h3>Fun facts about printing </h3>
            <p>Have you or did you know that we do all our printing in sRGB? <br>
            
        The printer prints in sRGB, and it will convert the CMYK mode to the sRGB before printing. <br>
            
            sRGB encompasses only 35% of all visible colors. <br>
            
        While it produces more colors than any CMYK color space, The reason is that most of the sRGB colour space is printable across a range of devices. <br>
            </p>
            <p>Why do we use RGB or Adobe 1998 because of the greater range of colour’s as CMYK cannot produce brighter colours. <br>
            
            These hues are beyond the CMYK range and will come out daker and duller when printed.
            </p>
            <p class="color_yellow"><strong>Setting your files!
                </strong></p>
            <p>Save your files at <strong>300ppi (pixels per inch) </strong> the correct print size you are ordering example <strong>( 12 x 18 inches at 300 ppi)</strong> Save your files as .jpeg (our software only accepts .jpeg files) and make sure your file name is no longer than 33 characters and if you need more than one copy of the file can you please kindly add qty and the number of prints to be printed at the front of the file name. Example <strong>(qty2shadowsphotoprinting.JPEG)</strong> and before saving please ensure that the colour space is saved in <strong>sRGB, RGB or Adobe1998.</strong>
             </p>
             <p>Before printing we convert the files with our printing profile ensuring amazing quality.
             </p>
             <h4>Have you had your monitor calibrated?
            </h4>
            <p>As this is a good idea for photo editing no matter what your profession. <br>
            
        As a photographer this is especially important! A monitor colour calibration ensures that the
                
                edits you apply to a photo are accurate. <br>
            
            It also helps to ensure that your prints look as good as they did on your monitor and all other
                calibrated monitors. <br>
            
            We calibrate or monitor regularly and check the settings in our editing program to make sure 
                that
                we are set at <strong> sRGB </strong> or <strong>RGB, Adobe 1998.</strong>
            </p>
            <p><strong>What colour background do you use in your photo editing software?</strong></p>
            <p>We use a grey background in our photo editing software as grey is useful because it is subtle
                 and
                tends to focus attention on the subject. This is a good option for <br> portraiture  photography.
            </p>
            <h4>RATIOS – ASPECT RATIO
            </h4>
            <p>Aspect ratio is the relationship of width of an image to its height, of its horizontal to
                vertical  dimensions. Some digital cameras let you select an aspect ratio that <br> matches
                standard
                print  sizes, such as 6”x4” (3:2 ratio) Some digital cameras default to different ratios,
                the
                most common other than 3:2ratio is 4:3 ratio.
            </p>
            <p>For files not shot in a 3:2 ratio, care must be taken to get the desired aspect ratio with 
                editing software as photos will need to be the same ratio as the print selected or <br> <strong>
                    WHITE  EDGES
                    WILL APPEAR ON YOUR FINISIED PRINTS.</strong> This also can happen if you have cropped 
                your file in some
                way.
            </p>
            <p>If you are selecting a border on your prints and you don’t have the correct ratio, those prints
                will have an <strong>odd sized border on some sides,</strong> if you say a
                <strong>14”x11”</strong> you <br> would need to place  the
                <strong>14:11 ratio</strong> on a <strong>12”x18”</strong> canvas <strong>(ie:3:2)</strong>
                ratio canvas.
            </p>
            <h4>Question!
            </h4>
            <p>I am unhappy with my photo as I saved a 14”x11” and they all came back out with larger  with white
                edges on the side!
            </p>
            <h4>Answer!
            </h4>
            <p>We print your files to <strong> (FIT ON WHATEVER SIZED MEDIA (PAPER) YOU CHOSE). </strong>
            </p>
            <p>You chose 12”x18” so they <strong>FIT on the media (paper) as follows:</strong> The 11” side will
                go to 12”  and the 14” side will go to 15.273, so you will have in front of you <br> 15.273”x12”
                prints.
            </p>
            <p>If you want to make <strong>14”x11”</strong> enlargements you will need to make them <strong>
                    14”x11” </strong> on a <strong>12”x18”</strong> canvas
                <strong>
                    (image> Canvas Size in Photoshop),
                </strong> then you simply print  <br> them as 12”x18  and you will have the
                14”x11” inside the border you created in Photoshop.
            </p>
            <h4>Question!
            </h4>
            <p>What Pixels should I export out of my Scrapbooking Program?
            </p>
            <h4>Answer!
            </h4>
            <p>If you export at <strong>3600pixels,</strong> and you can use all our sizes for scrapbook prints
                <strong> 8”x8”, 10”x10”</strong> and  <strong>12”x12”.</strong>
            </p>
            <h4>Borders
            </h4>
            <p>We recommend a 4mm white border if your prints are to be framed or if you’re not sure  about
                the
                ratio.
            </p>
            <p>The 4mm border doesn’t bleed out but sometimes you might notice a slight skew on the  white
                edge
                <strong>
                    (less than 5mm),
                </strong> reason for this is that the media <strong>(paper)</strong> must go <br> through a lot
                of guides, and
                it needs a bit of give, so that travels smoothly through the machine. Hence <br> the reason for
                bleeding happens.
            </p>
            <h4>Bleeding</h4>
            <p>Please note our printer will crop <strong> 2-3</strong>% from the outer edge of the image through
                bleeding.
            </p>
            <p>If you do not wish to lose any of the image, please specify borders on your prints and this
                will
                a 4mm white (or another colour if you wish) border. The main problem  <br> that is incurred is
                when
                you have text near the edges or need something close to the edge of the photo.</p>
            <h4>Question!
            </h4>
            <p>Do you have a discount rate for larger order?
            </p>
            <h4>Answer!
            </h4>
            <p>Yes, at Shadows Photo Printing we offer discounts on bulk orders.
            </p>
            <p>With our bulk orders you can add all different print sizes to the order as well as photo
                canvas
                or scrapbook prints to the order.
            </p>
            <p>This is a great option for professional photographers or to update the family photo album.
            </p>
            <p>As at Shadows Photo Printing we understand that how important your beautiful memories  are.
            </p>
            <p>We try to keep our prices affordable for everyone and we even have Afterpay available!
            </p>
            <p class="color_yellow"><strong>3rd Party Order’s </strong></p>
            <p>If you choose 3rd party orders, we will not send any invoices to the 3rd party.
            </p>
            <p>We charge a <strong>$2.00</strong> for this service to cover the extra costs of posting and
                handling.
            </p>
            <p class="color_yellow"><strong>Credits Not Given</strong></p>
            <p><strong> No Credits Given for Customers Errors!</strong>
            </p>
            <p>We print the size of the photo that you have requested on the order sheet when you place your
                order.
            </p>
            <p> <strong>If you choose the wrong size this falls back on to you!</strong> As we do not change the
                size of your images.
            </p>
           <p>Please make sure that you have your images in the correct <span>RATIOS – ASPECT RATIO</span> before you place your order.

           </p>
            
            
            <p>However, if you need any help or advise on checking the size or setting your files for printing 
                your beautiful photos, please feel free to contact our team!
            </p>
            <p class="color_yellow"><strong>Damaged in the mailing system!</strong></p>
            <p>We will resend the order, if your prints are damaged in the mail system, however you will 
                need to
                contact us with your order number and email us an attachment of the <br> damage order  to email
                <a href="mailto:shadowsphotoprinting@outlook.com">shadowsphotoprinting@outlook.com</a>
            </p>
            <p><strong>If your order is lost in the mail system!</strong></p>

            <p>Please contact us and we will contact the mailing company and follow up on your order, and  if the
                order has been lost, we will reprint your order.
            </p>
            <p>If need be, we will then give you a credit.
            </p>
            <p class="color_yellow"><strong>If we stuff up – we pay for it!</strong></p> --}}
         
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
    </script>
@endsection