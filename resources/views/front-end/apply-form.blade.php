@extends('front-end.layout.main')
@section('styles')
    <style>
        .form-mm {
            background: #000;
            color: #fff;
            padding: 90px 0;
        }

        .form-mm .container {
            max-width: 700px !important;
        }

        .form-mm .header a {
            color: #ffc205;
        }

        #signatureCanvas {
            width: 100%;
            height: 170px;
            border: 0 !important;
        }

        .form-mm .header {
            text-align: center;
            background: #343434;
            padding: 40px 30px;
            border-radius: 15px;
            margin-bottom: 30px;
        }

        .form-mm #ambassadorForm {
            background: #343434;
            padding: 40px 30px;
            border-radius: 15px;
        }

        .form-mm .form-section label {
            display: block;
            font-weight: 500;
            color: #ffc205;
            margin-bottom: 6px;
            font-size: 0.95em;
            margin-top: 10px;
        }

        .form-mm .form-section input,
        .form-mm .form-section textarea {
            font-size: 0.95em;
            box-sizing: border-box;
            margin-bottom: 10px;
            height: 40px;
            transition: border-color 0.3s ease;
            background: #000;
            border: 1px solid #fff;
            color: #fff;
            width: 100%;
            background-color: rgba(0, 0, 0, .07);
            padding: 10px;
            line-height: 20px;
        }

        .form-mm .form-section textarea {
            height: 80px;
            resize: vertical;
        }

        .form-mm .form-section input:focus,
        .form-mm .form-section textarea:focus {
            border-color: #ffffff;
            outline: none;
            border-radius: 4px;
        }

        .form-mm .required {
            color: #e74c3c;
        }

        .form-mm .checkbox-card {
            border: 1px solid #000000;
            border-radius: 4px;
            padding: 10px;
            background-color: #000;
        }

        .form-mm .checkbox-list {
            margin: 0;
            padding-left: 0;
            list-style: none;
        }

        .form-mm .checkbox-list li {
            margin-bottom: 8px;
        }

        .form-mm .checkbox-list label {
            display: flex;
            align-items: center;
            cursor: pointer;
            font-size: 0.95em;
            color: #fff;
        }

        .form-mm .checkbox-list input[type="checkbox"] {
            margin-right: 8px;
            margin-top: 10px;
            vertical-align: top;
            accent-color: #000000;
            width: 16px;
            height: 16px;
        }

        .form-mm #confirmation {
            margin-right: 8px;
            margin-top: 2px;
            vertical-align: top;
            accent-color: #000000;
            width: 16px;
            height: 16px;
        }

        .form-mm .checkbox-list input[type="text"] {
            width: 200px;
            margin-left: 8px;
            vertical-align: middle;
            height: 30px;
            font-size: 0.95em;
            border: 1px solid #dfe6e9;
            border-radius: 4px;
            padding: 4px 8px;
            background: #000;
            color: #fff;
        }

        .form-mm .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        .form-mm button {
            font-size: 0.95em;
            transition: background-color 0.3s ease, transform 0.2s ease;
            width: 48%;
            -webkit-box-shadow: inset 0 0 0 0 transparent;
            box-shadow: inset 0 0 0 0 transparent;
            background-color: #16a085;
            border: 0;
            border-radius: 0;
            display: inline-block;
            color: #fff;
            font-weight: 700;
            padding: 8px 16px;
            line-height: 24px;
            text-decoration: none;
            text-shadow: 0 -1px 0 rgba(0, 0, 0, .1);
            -webkit-transition: box-shadow .2s ease-in-out;
            -o-transition: box-shadow .2s ease-in-out;
            transition: box-shadow .2s ease-in-out;
            height: 40px;
        }

        .form-mm button:hover {
            /* background-color: #000000; */
            /* transform: translateY(-2px); */
            -webkit-box-shadow: inset 0 -4px 0 0 rgba(0, 0, 0, .2);
            box-shadow: inset 0 -4px 0 0 rgba(0, 0, 0, .2);
        }

        .form-mm .clear-btn {
            background-color: #fff;
            color: #ffc205;
            border: 2px solid #dfe6e9;
            margin-right: 0;
        }

        .form-mm .clear-btn:hover {
            background-color: #f1f3f5;
            transform: translateY(-2px);
        }

        .form-mm .footer {
            text-align: center;
            font-size: 0.75em;
            color: #636e72;
            margin-top: 15px;
        }

        .form-mm .footer a {
            color: #ffc205;
            text-decoration: none;
            font-weight: 500;
        }

        .form-mm .footer a:hover {
            text-decoration: underline;
        }

        .form-mm .signature-pad {
            margin-bottom: 10px;
            border: 1px solid #fff;
            color: #fff;
            width: 100%;
            background-color: rgba(0, 0, 0, .07);
            line-height: 20px;
        }

        /* Enhance native date picker appearance */
        .form-mm .form-section input[type="date"] {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background: #000;
            border: 1px solid #fff;
            color: #fff;
            width: 100%;
            background-color: rgba(0, 0, 0, .07);
            padding: 10px;
            line-height: 20px;
        }

        .form-mm .form-section input[type="date"]:focus {
            border-color: #ffc205;
            outline: none;
        }

        .form-mm .form-section input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            /* Invert icon color for white on black */
        }

        button#clearSignature {
            padding: 0 !important;
            border: 0 !important;
            height: auto !important;
            max-width: fit-content;
            width: auto;
            background: transparent !important;
            box-shadow: none !important;
            color: #16a085 !important;
        }

        .banner-sec-new {
            padding: 150px 0;
            text-align: center;
            background-color: #000;
            background-image: url("{{ asset('assets/images/SBAs3.jpg') }}");
            position: relative;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .banner-sec-new .container {
            position: relative;
            z-index: 1;
        }

        .banner-sec-new .div-wrap-head {
            color: #fff;
        }

        .banner-sec-new:after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #0000008f;
        }

        @media (max-width: 480px) {
            .form-mm .form-section {
                margin-bottom: 12px;
            }

            .form-mm .button-container {
                flex-direction: column;
            }

            .form-mm button {
                width: 100%;
                margin-bottom: 10px;
            }

            .form-mm .clear-btn {
                margin-top: 0;
            }
        }
    </style>
    <!-- Signature Pad Library -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.5/dist/signature_pad.umd.min.js"></script>
@endsection
@section('content')
    <section class="banner-sec-new">
        <div class="container">
            <div class="div-wrap-head">
                <h1>Apply to Be a SPP Photographer Brand Ambassador</h1>
                <p>We're proud to work with talented professional photographers across Australia - and you can be one of
                    them! Selected photographers receive exclusive discounts, opportunities to be showcased on our blog and
                    social media opportunities, and much more...</p>
            </div>
        </div>
    </section>

    @if (Session::has('success'))
        <section class="form-mm">
            <div class="container">
                <div class="header">
                    <p class="alert alert-success text-center">{{ Session::get('success') }}</p>
                </div>
            </div>
        </section>
    @else
        <section class="form-mm">
            <div class="container">
                <div class="header">

                    <p><strong>Eligibility Requirements:</strong></p>
                    <ul style="text-align: center; color: #fff;">
                        <li>You must be 18 years or older.</li>
                        <li>You must reside in Australia.</li>
                    </ul>
                    <p style="color: #e74c3c;">* Indicates required question</p>
                </div>

                <form action="{{ route('apply-form.post') }}" method='post' id="ambassadorForm"
                    onsubmit="return validateForm(event)">
                    @csrf

                    <div class="form-section">
                        <label for="name">Photographer Information<br> First & Last Name <span
                                class="required">*</span></label>
                        <input type="text" id="name" name="name" placeholder="First & Last Name" required
                            value="{{ old('name') }}">
                    </div>
                    <div class="form-section">
                        <label for="location">Where are you located? <span class="required">*</span></label>
                        <input type="text" id="location" name="location" placeholder="location" required
                            value="{{ old('location') }}">
                    </div>
                    <div class="form-section">
                        <label for="business">Does your photography business go by a different name? If so, let us
                            know.</label>
                        <input type="text" id="business" name="business" placeholder="Business Name"
                            value="{{ old('business') }}">
                    </div>
                    <div class="form-section">
                        <label for="email">Email <span class="required">*</span></label>
                        <input type="email" id="email" name="email" placeholder="email" required
                            value="{{ old('email') }}">
                        @error('email')
                            <div style="color: #e74c3c; font-size: 0.85em;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-section">
                        <label for="website">Website <span class="required">*</span></label>
                        <input type="url" id="website" name="website" placeholder="e.g., https://yourwebsite.com"
                            required value="{{ old('website') }}">
                    </div>
                    <div class="form-section">
                        <label for="social">Social Media handle (Instagram, Facebook and/or TikTok) <span
                                class="required">*</span></label>
                        <input type="text" id="social" name="social" placeholder="e.g., @yourhandle" required
                            value="{{ old('social') }}">
                        @error('social')
                            <div style="color: #e74c3c; font-size: 0.85em;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-section">
                        <label>Photography Details <br>What is your photography specialty? You can choose more than one or
                            Other. <span class="required">*</span></label>
                        <div class="checkbox-card">
                            <ul class="checkbox-list">
                                <li><label><input type="checkbox" name="specialty[]" value="wedding"
                                            {{ is_array(old('specialty')) && in_array('wedding', old('specialty')) ? 'checked' : '' }}>
                                        Wedding/Engagement/Couples</label></li>
                                <li><label><input type="checkbox" name="specialty[]" value="newborn"
                                            {{ is_array(old('specialty')) && in_array('newborn', old('specialty')) ? 'checked' : '' }}>
                                            Newborn, Maternity & Family</label></li>
                                <li><label><input type="checkbox" name="specialty[]" value="grad"
                                            {{ is_array(old('specialty')) && in_array('grad', old('specialty')) ? 'checked' : '' }}>
                                            School Photography (Formals, Graduation Ceremonies)</label></li>
                                <li><label><input type="checkbox" name="specialty[]" value="landscape"
                                            {{ is_array(old('specialty')) && in_array('landscape', old('specialty')) ? 'checked' : '' }}>
                                        Landscape/Nature</label></li>
                                <li><label><input type="checkbox" name="specialty[]" value="portraits"
                                            {{ is_array(old('specialty')) && in_array('portraits', old('specialty')) ? 'checked' : '' }}>
                                        Portraits</label></li>
                                <li><label><input type="checkbox" name="specialty[]" value="pets"
                                            {{ is_array(old('specialty')) && in_array('pets', old('specialty')) ? 'checked' : '' }}>
                                        Pets</label></li>
                                <li><label><input type="checkbox" name="specialty[]" value="boudoir"
                                            {{ is_array(old('specialty')) && in_array('boudoir', old('specialty')) ? 'checked' : '' }}>
                                        Boudoir</label></li>
                                <li><label><input type="checkbox" name="specialty[]" value="sports"
                                            {{ is_array(old('specialty')) && in_array('sports', old('specialty')) ? 'checked' : '' }}>
                                        Sports</label></li>
                                <li><label><input type="checkbox" name="specialty[]" value="lifestyle"
                                            {{ is_array(old('specialty')) && in_array('lifestyle', old('specialty')) ? 'checked' : '' }}>
                                        Lifestyle/Fashion</label></li>
                                <li>
                                    <label>
                                        <input type="checkbox" name="specialty[]" value="other"
                                            {{ is_array(old('specialty')) && in_array('other', old('specialty')) ? 'checked' : '' }}>
                                        Other:
                                        <input type="text" name="otherSpecialty" placeholder="Specify"
                                            value="{{ old('otherSpecialty') }}">
                                    </label>
                                </li>
                            </ul>
                        </div>
                        @error('specialty')
                            <div style="color: #e74c3c; font-size: 0.85em;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-section">
                        <label>Tell us anything else about yourself you'd like us to know or why you're interested in
                            becoming an SPP Photographer Brand Ambassador!</label>
                        <textarea id="comments" name="comments" rows="3" placeholder="Why you’re interested...">{{ old('comments') }}</textarea>
                    </div>
                    <!-- <div class="form-section">
                    <label> Terms & Condition:</label>
                    <label><input type="checkbox" id="confirmation" name="confirmation" value="confirmation" required > <span class="required">*</span> By submitting this application, I confirm that all the information provided is accurate. I understand that if approved, I’ll receive exclusive discounts, potential blog and social media features, and other perks as part of the program. I also confirm that I am <strong>18 years or older</strong> and <strong>reside in Australia</strong>.</label>
                </div> -->

                    <div class="form-section">
                        <label>Confirmation<span class="required">*</span><br> <input type="checkbox" id="confirmation"
                                name="confirmation" value="confirmation" required> By submitting this application, I
                            confirm that all the information provided is accurate. I understand that if approved, I’ll
                            receive exclusive discounts, potential blog and social media features, and other perks as part
                            of the program. I also confirm that I am <b>18 years or older</b> and <b>reside in
                                Australia</b>.</label>
                        <div class="form-section">
                            <label for="signature">Digital Signature <span class="required">*</span></label>
                            <div class="signature-pad">
                                <canvas id="signatureCanvas" width="400" height="150"
                                    style="border: 1px solid #fff;"></canvas>
                            </div>
                            <button type="button" id="clearSignature"
                                style="background-color: #fff; color: #ffc205; border: 2px solid #dfe6e9; padding: 5px 10px; margin-top: 5px; border-radius: 6px;">Clear
                                Signature</button>
                            <input type="hidden" id="signatureData" name="signatureData" required>
                        </div>
                    </div>
                    @php
                        // Set timezone to Australia/Sydney (or another Australian timezone if needed)
                        date_default_timezone_set('Australia/Sydney');
                        $australiaDate = date('Y-m-d');
                    @endphp
                    <div class="form-section">
                        <label for="date">Date <span class="required">*</span></label>
                        <input type="date" id="date" name="date" required
                            value="{{ old('date', $australiaDate) }}" readonly>
                    </div>

                    <div class="button-container">
                        <button type="submit">Submit Application</button>
                        <button type="button" class="clear-btn"
                            onclick="document.getElementById('ambassadorForm').reset()">Reset Form</button>
                    </div>
                </form>
                <div class="footer">
                    <p>Security note: Avoid submitting sensitive data. Created by Shadow's Photo Printing. <a
                            href="{{ url('contact-us') }}">Report Issues</a></p>
                </div>
            </div>
        </section>
    @endif
@endsection
@section('scripts')
    <script>
        var canvas = document.getElementById('signatureCanvas');
        var signaturePad = new SignaturePad(canvas, {
            backgroundColor: '#000',
            penColor: '#fff'
        });

        // Clear Signature
        document.getElementById('clearSignature').addEventListener('click', function() {
            signaturePad.clear();
            document.getElementById('signatureData').value = '';
        });

        function validateForm(event) {
            event.preventDefault();
            const name = document.getElementById('name').value;
            const location = document.getElementById('location').value;
            const email = document.getElementById('email').value;
            const website = document.getElementById('website').value;
            const social = document.getElementById('social').value;
            const signatureData = document.getElementById('signatureData').value;

            const specialties = document.querySelectorAll('input[name="specialty[]"]:checked');
            const confirmation = document.querySelectorAll('input[name="confirmation"]:checked');

            console.log(name, location, email, website, social, specialties);

            if (!name || !location || !email || !website || !social || specialties.length === 0) {
                alert('Please fill out all required fields and select at least one photography specialty.');
                return false;
            }

            if (signaturePad.isEmpty()) {
                alert('Please Draw your Signature.');
                return false;
            }

            document.getElementById('signatureData').value = signaturePad.toDataURL();

            if (!confirmation) {
                alert('Please fill out all required fields and select at least one photography specialty.');
                return false;
            }

            // alert('Form submitted successfully! (This is a demo alert)');
            event.target.submit();
            return true;
        }


        // Ensure canvas is responsive
        function resizeCanvas() {
            var ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear(); // Clear to redraw on resize
        }

        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();
    </script>
@endsection
