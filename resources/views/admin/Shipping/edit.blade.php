@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main" style="min-height: 3963px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('shipping-list') }}">Shipping</a></li>
          <li class="breadcrumb-item"><a href="#">Edit Shipping</a></li>
        </ol>
    </nav>
    @if(Session::has('success'))
    <p class="alert alert-success text-center">{{ Session::get('success') }}</p>
  @endif
<div class="">
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Edit Shipping</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br>
                <form action="{{ route('shipping-update') }}" method="POST" id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate="">
                    @csrf
                    <input type="hidden" name="status" value="1">
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="country" >Country <span class="required">*</span>
                        </label>.
                        <div class="col-md-6 col-sm-6 " >
                            <input type="text" id="country" name="country" required="required" class="form-control " value="{{ $shipping->country }}" disabled>
                            @error('country')
                            <span class="text-danger">{{ $message }}</span>
                           @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="shipping_method" >Shipping Method <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <select class="form-control" name="shipping_method">
                               <option value="">Select</option>
                                <option value="0" <?= ($shipping->shipping_method == "0" ? "selected" : "") ?>>Flat</option>
                                <option value="1" <?= ($shipping->shipping_method == "1" ? "selected" : "") ?>>Percent</option>
                            </select>
                            @error('shipping_method')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="amount">Amount <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="number" id="amount" name="amount" required="required" class="form-control" step=".01" value="{{ $shipping->amount }}">
                            @error('amount')
                            <span class="text-danger">{{ $message }}</span>
                           @enderror
                        </div>
                    </div>


                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="is_test_print" >Test Print <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <select class="form-control" name="is_test_print">
                               {{-- <option value="">Select</option> --}}
                                <option value="1" @if($shipping->is_test_print == '1') selected @endif>Yes</option>
                                <option value="0" @if($shipping->is_test_print == '0') selected @endif>No</option>
                            </select>
                            @error('is_test_print')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <input type="hidden" name="shipping_id" value="{{ $shipping->id }}">
                    <div class="ln_solid"></div>
                    <div class="item form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection

@section('custom-script')

@endsection
