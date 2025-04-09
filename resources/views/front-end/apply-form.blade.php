@extends('front-end.layout.main')
@section('styles')
<style>
    .form-mm {
        background: #000;
        color: #fff;
        padding: 90px 0;
    }
    .form-mm .container {
        max-width: 700px!important;
    }
    .form-mm  .header  a {
        color: #ffc205;
    }
    .form-mm  .header {
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
        color: #fff;
        margin-bottom: 6px;
        font-size: 0.95em;
    }
    .form-mm  .form-section input, .form-mm  .form-section textarea {
        width: 100%;
        padding: 8px 12px;
        border: 2px solid #000000;
        border-radius: 6px;
        font-size: 0.95em;
        box-sizing: border-box;
        margin-bottom: 10px;
        height: 40px;
        transition: border-color 0.3s ease;
        background: #000;
        color: #fff;
    }         
        .form-mm .form-section textarea {
            height: 80px;
            resize: vertical;
        }
       .form-mm  .form-section input:focus,
       .form-mm  .form-section textarea:focus {
            border-color: #ffc205;
            outline: none;
        }
       .form-mm  .required {
            color: #e74c3c;
        }
        .form-mm  .checkbox-card {
            border: 1px solid #000000;
            border-radius: 4px;
            padding: 10px;
            background-color: #000;
        }
       .form-mm  .checkbox-list {
            margin: 0;
            padding-left: 0;
            list-style: none;
        }
       .form-mm  .checkbox-list li {
            margin-bottom: 8px;
        }
       .form-mm  .checkbox-list label {
            display: flex;
            align-items: center;
            cursor: pointer;
            font-size: 0.95em;
            color: #fff;
        }
       .form-mm  .checkbox-list input[type="checkbox"] {
            margin-right: 8px;
            margin-top: 2px;
            vertical-align: top;
            accent-color: #000000; /* Default checkbox color to match screenshot */
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
        }
        .form-mm .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }
        .form-mm button {
            background-color: #ffc205;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 0.95em;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            width: 48%;
        }
        .form-mm  button:hover {
            background-color: #000000;
            transform: translateY(-2px);
        }
       .form-mm  .clear-btn {
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
       .form-mm  .footer a {
            color: #ffc205;
            text-decoration: none;
            font-weight: 500;
        }
        .form-mm  .footer a:hover {
            text-decoration: underline;
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

@endsection
@section('content')
<section class="form-mm">
    <div class="container">
        <div class="header">
            <h1>Shadows Photo Printing Photographer Brand Ambassador</h1>
            <p>Join our network of talented photographers! Enjoy lab credits, exclusive product access, and more. Contact us at <a href="mailto:enquiries@shadowsphotoprinting.com.au">enquiries@shadowsphotoprinting.com.au</a>.</p>
            <p style="color: #e74c3c;">* Indicates required question</p>
        </div>
        <form action="{{route('apply-form.post')}}" method='post' id="ambassadorForm" onsubmit="return validateForm(event)">
        @csrf

            <div class="form-section">
                <label for="name">First & Last Name <span class="required">*</span></label>
                <input type="text" id="name" name="name" required value="{{ old('name') }}">
            </div>
            <div class="form-section">
                <label for="location">Location <span class="required">*</span></label>
                <input type="text" id="location" name="location" required value="{{ old('location') }}">
            </div>
            <div class="form-section">
                <label for="business">Business Name (if different)</label>
                <input type="text" id="business" name="business" value="{{ old('business') }}">
            </div>
            <div class="form-section">
                <label for="email">Email <span class="required">*</span></label>
                <input type="email" id="email" name="email" required value="{{ old('email') }}">
                @error('email')
                    <div style="color: #e74c3c; font-size: 0.85em;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-section">
                <label for="website">Website <span class="required">*</span></label>
                <input type="url" id="website" name="website" placeholder="e.g., https://yourwebsite.com" required value="{{ old('website') }}">
            </div>
            <div class="form-section">
                <label for="social">Social Media Handle <span class="required">*</span></label>
                <input type="text" id="social" name="social" placeholder="e.g., @yourhandle" required value="{{ old('social') }}">
                @error('social')
                    <div style="color: #e74c3c; font-size: 0.85em;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-section">
                <label>What is your photography specialty? You can choose more than one or Other. <span class="required">*</span></label>
                <div class="checkbox-card">
                    <ul class="checkbox-list">
                        <li><label><input type="checkbox" name="specialty[]" value="wedding" {{ is_array(old('specialty')) && in_array('wedding', old('specialty')) ? 'checked' : '' }}> Wedding/Engagement/Couples</label></li>
                        <li><label><input type="checkbox" name="specialty[]" value="newborn" {{ is_array(old('specialty')) && in_array('newborn', old('specialty')) ? 'checked' : '' }}> Newborn/Family</label></li>
                        <li><label><input type="checkbox" name="specialty[]" value="grad" {{ is_array(old('specialty')) && in_array('grad', old('specialty')) ? 'checked' : '' }}> Grad/Senior Photos</label></li>
                        <li><label><input type="checkbox" name="specialty[]" value="landscape" {{ is_array(old('specialty')) && in_array('landscape', old('specialty')) ? 'checked' : '' }}> Landscape/Nature</label></li>
                        <li><label><input type="checkbox" name="specialty[]" value="portraits" {{ is_array(old('specialty')) && in_array('portraits', old('specialty')) ? 'checked' : '' }}> Portraits</label></li>
                        <li><label><input type="checkbox" name="specialty[]" value="pets" {{ is_array(old('specialty')) && in_array('pets', old('specialty')) ? 'checked' : '' }}> Pets</label></li>
                        <li><label><input type="checkbox" name="specialty[]" value="boudoir" {{ is_array(old('specialty')) && in_array('boudoir', old('specialty')) ? 'checked' : '' }}> Boudoir</label></li>
                        <li><label><input type="checkbox" name="specialty[]" value="sports" {{ is_array(old('specialty')) && in_array('sports', old('specialty')) ? 'checked' : '' }}> Sports</label></li>
                        <li><label><input type="checkbox" name="specialty[]" value="lifestyle" {{ is_array(old('specialty')) && in_array('lifestyle', old('specialty')) ? 'checked' : '' }}> Lifestyle/Fashion</label></li>
                        <li>
                            <label>
                                <input type="checkbox" name="specialty[]" value="other" {{ is_array(old('specialty')) && in_array('other', old('specialty')) ? 'checked' : '' }}>
                                Other:
                                <input type="text" name="otherSpecialty" placeholder="Specify" value="{{ old('otherSpecialty') }}">
                            </label>
                        </li>
                    </ul>
                </div>
                @error('specialty')
                    <div style="color: #e74c3c; font-size: 0.85em;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-section">
                <label>Additional Comments</label>
                <textarea id="comments" name="comments" rows="3" placeholder="Why you’re interested...">{{ old('comments') }}</textarea>
            </div>
            <div class="button-container">
                <button type="submit">Submit Application</button>
                <button type="button" class="clear-btn" onclick="document.getElementById('ambassadorForm').reset()">Reset Form</button>
            </div>
        </form>
        <div class="footer">
            <p>Security note: Avoid submitting sensitive data. Created by Shadow's Photo Printing. <a href="#">Report Issues</a></p>
        </div>
    </div>
</section>

@endsection
@section('scripts')
    <script>
        function validateForm(event) {
            event.preventDefault();
            const name = document.getElementById('name').value;
            const location = document.getElementById('location').value;
            const email = document.getElementById('email').value;
            const website = document.getElementById('website').value;
            const social = document.getElementById('social').value;
            const specialties = document.querySelectorAll('input[name="specialty[]"]:checked');
            
            console.log(name,location,email,website,social,specialties);

            if (!name || !location || !email || !website || !social || specialties.length === 0) {
                alert('Please fill out all required fields and select at least one photography specialty.');
                return false;
            }
           // alert('Form submitted successfully! (This is a demo alert)');
           event.target.submit();
            return true;
        }
    </script>
@endsection
