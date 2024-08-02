@extends('front-end.layout.main')
@php
    $PageDataService = app(App\Services\PageDataService::class);
    $ProductCategories = $PageDataService->getProductCategories();
@endphp
@section('content')
   
<section class="account-page">
    <div class="container">
        <div class="account-wrapper">
            <div class="row">
                @include('front-end.profile.component.account-sidebar')
                <div class="col-md-9">
                    <div class="pangas-can">
                        <div class="endpointtitle">
                            <h2>Addresses
                            </h2>
                            <div class="notices-wrapper">
                                <form action="{{ route('save-address') }}" method="POST" class="input-color">
                                    @csrf
                                    <h3>@if($slug == 'billing') Billing address @else Shipping Address @endif</h3>
                                    <div class="fields__field-wrapper">
                                        <div class="fields-inner">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="billing_first">
                                                        <label>First name * </label>
                                                        <input type="text" name="{{ $slug == 'billing' ? 'fname' : 'ship_fname' }}" value="{{old('fname')}}" placeholder="developer">
                                                        <input type="hidden" name="slug" value="{{$slug}}">
                                                        @error('fname')
                                                          <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @error('ship_fname')
                                                          <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="billing_first">
                                                        <label>Last name * </label>
                                                        <input type="text" name="{{ $slug == 'billing' ? 'lname' : 'ship_lname' }}" value="{{old('lname')}}" placeholder="dev">
                                                        @error('lname')
                                                          <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @error('ship_lname')
                                                          <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fields-inner">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="billing_first">
                                                        <label>Company name (optional)
                                                        </label>
                                                        <input type="text" name="{{ $slug == 'billing' ? 'company_name' : 'ship_company' }}" value="{{old('company_name')}}" placeholder="test">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fields-inner">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="billing_first">
                                                        <label>Country / Region </label>
                                                        <span class="input-wrapper">
                                                            <strong>{{ config('constant.default_country') }}</strong>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fields-inner">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="billing_first">
                                                        <label>Street address * </label>
                                                        <input type="text" name="{{ $slug == 'billing' ? 'street1' : 'ship_street1' }}" value="{{old('street1')}}" placeholder="7 Edward Bennett Drive">
                                                        @error('street1')
                                                          <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @error('ship_street1')
                                                          <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fields-inner">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="billing_first">
                                                        <input type="text" name="{{ $slug == 'billing' ? 'street2' : 'ship_street2' }}" value="{{old('street2')}}"  placeholder="gg">
                                                        @error('street2')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @error('ship_street2')
                                                        <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fields-inner">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="billing_first">
                                                        <label>Suburb * </label>
                                                        <input type="text" name="{{ $slug == 'billing' ? 'suburb' : 'ship_suburb' }}" value="{{old('suburb')}}" placeholder="Suburb *">
                                                        @error('suburb')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @error('ship_suburb')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fields-inner">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="billing_first">
                                                        <label>State
                                                            *
                                                        </label>

                                                        <select class="form-control" id="state" name="{{ $slug == 'billing' ? 'state' : 'ship_state' }}" >
                                                            <option value="">State</option>
                                                            @foreach ($countries->states as $state)
                                                              <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                            @endforeach
                    
                                                        </select>
                                                        @error('state')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @error('ship_state')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fields-inner">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="billing_first">
                                                        <label>Postcode * </label>
                                                        <input type="number" name="{{ $slug == 'billing' ? 'postcode' : 'ship_postcode' }}" value="{{old('postcode')}}"  placeholder="2145 *">
                                                        @error('postcode')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        @error('ship_postcode')
                                                         <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if($slug != 'shipping')
                                        <div class="fields-inner">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="billing_first">
                                                        <label>Phone (optional)
                                                        </label>
                                                        <input type="number" name="phone" value="{{old('phone')}}"  placeholder="2145 *">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fields-inner">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="billing_first">
                                                        <label>Email address * </label>
                                                        <input type="email" name="email"
                                                            placeholder="devavology12@gmail.com" value="{{old('email')}}">
                                                        @error('email')
                                                         <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="fields-inner">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="quanti">
                                                        <button type="submit">Save address</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('scripts')
    <script>
        $('#state').select2({
            placeholder: 'Select state',
            allowClear: false
        });
    </script>
@endsection
