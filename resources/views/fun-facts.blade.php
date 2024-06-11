@extends('layout.main')
@section('content')
{{-- @php dd($page_content); @endphp --}}
<section class="about-printing">
    <div class="container">
        <div class="about-printing">
            {!! html_entity_decode($page_content['fun-facts-content']) !!}
            {{-- <h3>Fun facts about printing </h3>
            <p>Have you or did you know that we do all our printing in sRGB? <br>
                The printer prints in sRGB, and it will convert the CMYK mode to the sRGB before printing.
                <br>
                sRGB encompasses only 35% of all visible colors.
                <br>
                While it produces more colors than any CMYK color space, The reason is that most of the <br> sRGB
                colour space is printable across a range of devices.
            </p>
            <p>Why do we use RGB or Adobe 1998 because of the greater range of colour’s as CMYK cannot <br> produce
                brighter colours.
                <br>
                These hues are beyond the CMYK range and will come out daker and duller when printed.
            </p>
            <P>We always suggest that you always make your image a little lighter than what is on your <br> display
                or device before printing as the printer will always print daker than what you see on <br> your
                display or device.
            </P>
            <p class="color_yellow"><strong>Setting your files!
                </strong></p>

           <p>Save your files at <strong>300ppi (pixels per inch)</strong> and the correct print size you are ordering <br> example (
                <strong>12 x 18 inches at 300 ppi)</strong> Save your files as .jpeg (our software only accepts .jpeg <br> files) and
                make sure your file name is no longer than 33 characters and if you need more <br> than one copy of
                the file can you please kindly add qty and the number of prints to be <br>printed at the front of
                the file name. Example <strong>(qty2shadowsphotoprinting.JPEG) </strong> and before <br> saving please ensure that the
                colour space is saved in <strong>sRGB, RGB</strong> or <strong>Adobe1998.</strong>
            </p>
            <p>Before printing we convert the files with our printing profile ensuring amazing quality.
            </p>

            <h4>Setting Greyscale Files!
            </h4>
            <p>Please check your files if you are working in a greyscale as if you leave the source space as<br>
                working grey dot 20% Adobe ACE our sure lab order control won’t process the file correctly, and
                this is <br> why they can come out looking green.
            </p>

            <P>Please check you flies are set correctly as sRGB for printing via the following instructions <br>
                below before you upload them for printing!
            </P>
            <P>You will need to open the file into photoshop and go to edit and then to convert to profile and
                select <br> SRGB click ok and save the file.
            </P>
            <P>As we do not change these files that are set as a source space as working grey dot 20% Adobe <br> ACE.
                This is up to you to have your files set correctly for the best quality of printing!
            </P>
            <h4>Have you had your monitor calibrated?
            </h4>
            <p>As this is a good idea for photo editing no matter what your profession.
            </p>
            <p>As a photographer this is especially important! A monitor colour calibration ensures that the
                <br>
                edits you apply to a photo are accurate.
            </p>
            <p>It also helps to ensure that your prints look as good as they did on your monitor and all other
                <br>
                calibrated monitors.
            </p>
            <p>We calibrate or monitor regularly and check the settings in our editing program to make sure <br>
                that
                we are set at <strong> sRGB </strong> or <strong>RGB, Adobe 1998.</strong>
            </p>
            <h4>What colour background do you use in your photo editing software?
            </h4>
            <p>We use a grey background in our photo editing software as grey is useful because it is subtle
                <br> and
                tends to focus attention on the subject. This is a good option for portraiture <br> photography.
            </p>
            <h4>RATIOS – ASPECT RATIO
            </h4>
            <p>Aspect ratio is the relationship of width of an image to its height, of its horizontal to
                vertical <br> dimensions. Some digital cameras let you select an aspect ratio that matches
                standard
                print <br> sizes, such as 6”x4” (3:2 ratio) Some digital cameras default to different ratios,
                the
                most <br> common other than 3:2ratio is 4:3 ratio.
            </p>
            <p>For files not shot in a 3:2 ratio, care must be taken to get the desired aspect ratio with <br>
                editing software as photos will need to be the same ratio as the print selected or <strong>
                    WHITE <br> EDGES
                    WILL APPEAR ON YOUR FINISIED PRINTS.</strong> This also can happen if you have cropped <br>
                your file in some
                way.
            </p>
            <p>If you are selecting a border on your prints and you don’t have the correct ratio, those prints
                <br>
                will have an <strong>odd sized border on some sides,</strong> if you say a
                <strong>14”x11”</strong> you would need to place <br> the
                <strong>14:11 ratio</strong> on a <strong>12”x18”</strong> canvas <strong>(ie:3:2)</strong>
                ratio canvas.
            </p>
            <h4>Question!
            </h4>
            <p>I am unhappy with my photo as I saved a 14”x11” and they all came back out with larger <br> with white
                edges on the side!
            </p>
            <h4>Answer!
            </h4>
            <p>We print your files to <strong> (FIT ON WHATEVER SIZED MEDIA (PAPER) YOU CHOSE). </strong>
            </p>
            <p>You chose 12”x18” so they <strong>FIT on the media (paper) as follows:</strong> The 11” side will
                go to 12” <br> and the 14” side will go to 15.273, so you will have in front of you 15.273”x12”
                prints.
            </p>
            <p>If you want to make <strong>14”x11”</strong> enlargements you will need to make them <strong>
                    14”x11” </strong> on a <br> <strong>12”x18”</strong> canvas
                <strong>
                    (image> Canvas Size in Photoshop),
                </strong> then you simply print them as 12”x18 <br> and you will have the
                14”x11” inside the border you created in Photoshop.
            </p>
            <h4>Question!
            </h4>
            <p>What Pixels should I export out of my Scrapbooking Program?
            </p>
            <h4>Answer!
            </h4>
            <p>If you export at <strong>3600pixels,</strong> and you can use all our sizes for scrapbook prints
                <strong> 8”x8”, 10”x10”</strong> and <br> <strong>12”x12”.</strong>
            </p>
            <h4>Borders
            </h4>
            <p>We recommend a 4mm white border if your prints are to be framed or if you’re not sure <br> about
                the
                ratio.
            </p>
            <p>The 4mm border doesn’t bleed out but sometimes you might notice a slight skew on the <br> white
                edge
                <strong>
                    (less than 5mm),
                </strong> reason for this is that the media <strong>(paper)</strong> must go through a lot
                <br>of guides, and
                it needs a bit of give, so that travels smoothly through the machine. Hence <br> the reason for
                bleeding happens.
            </p>
            <h4>Bleeding</h4>
            <p>Please note our printer will crop <strong> 2-3</strong>% from the outer edge of the image through
                bleeding.
            </p>
            <p>If you do not wish to lose any of the image, please specify borders on your prints and this <br>
                will
                a 4mm white (or another colour if you wish) border. The main problem that is incurred is <br>
                when
                you have text near the edges or need something close to the edge of the photo.</p>
            <p>Question!
            </p>
            <p>Do you have a discount rate for larger order?
            </p>
            <p>Answer!
            </p>
            <p>Yes, at Shadows Photo Printing we offer discounts on bulk orders.
            </p>
            <p>With our bulk orders you can add all different print sizes to the order as well as photo
                canvas<br>
                or scrapbook prints to the order.
            </p>
            <p>This is a great option for professional photographers or to update the family photo album.
            </p>
            <p>As at Shadows Photo Printing we understand that how important your beautiful memories <br> are.
            </p>
            <p>We try to keep our prices affordable for everyone and we even have Afterpay available!
            </p>
            <p> <strong>3 <sup>rd</sup> Party Order’s </strong></p>
            <p>If you choose 3rd party orders, we will not send any invoices to the 3rd party.
            </p>
            <p>We charge a <strong>$2.00</strong> for this service to cover the extra costs of posting and
                handling.
            </p>
            <h4>Credits Not Given
            </h4>
            <h4>No Credits Given for Customers Errors!
            </h4>
            <p>We print the size of the photo that you have requested on the order sheet when you place your
                order.
            </p>
            <p> <strong>If you choose the wrong size this falls back on to you!</strong> As we do not change the
                size of your images.
            </p>
            <h4>Setting Greyscale Files!
            </h4>
            <p>As we do not change these files that are set in the greyscale source space set as working
                grey<br>
                dot 20% Adobe ACE. If you set your files wrong <strong>this fall back on to you!
                </strong> </p>
            <p>Please make sure that you have your images in the correct <strong>RATIOS – ASPECT RATIO </strong>
                before <br> you place your order. </p>
            <p>However, if you need any help or advise on checking the size or setting your files for printing <br>
                your beautiful photos, please feel free to contact our team!
            </p>
            <h4>Damaged in the mailing system!
            </h4>
            <p>We will resend the order, if your prints are damaged in the mail system, however you will <br>
                need to
                contact us with your order number and email us an attachment of the damage order <br> to email
                <a href="mailto:shadowsphotoprinting@outlook.com">shadowsphotoprinting@outlook.com</a>
            </p>
            <h4>If your order is lost in the mail system!
            </h4>
            <p>Please contact us and we will contact the mailing company and follow up on your order, and <br> if the
                order has been lost, we will reprint your order.
            </p>
            <p>If need be, we will then give you a credit.
            </p>
            <h4>If we stuff up – we pay for it!
            </h4> --}}
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